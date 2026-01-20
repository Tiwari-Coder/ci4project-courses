<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CoursesModel;
use App\Models\CourseTermsModel;

class Courses extends BaseController
{
    public function create()
{
    $termsModel = new CourseTermsModel();

    // Get all terms grouped by taxonomy
    $taxonomies = $termsModel->where('parent_id IS NOT NULL')->findAll();

    $groupedTerms = [];
    foreach($taxonomies as $term){
        $groupedTerms[$term['taxonomy']][] = $term;
    }

    return view('admin/courses/create', [
        'groupedTerms' => $groupedTerms
    ]);
}


    public function store()
{
    $coursesModel = new \App\Models\CoursesModel();
    $db = \Config\Database::connect();

    // 1️⃣ Save course
    $courseId = $coursesModel->insert([
        'title'   => $this->request->getPost('title'),
        'content' => $this->request->getPost('content'),
    ]);

    // 2️⃣ Save terms (license, level, training)
    $terms = $this->request->getPost('terms') ?? [];

    foreach ($terms as $termId) {
        $db->table('course_term_relations')->insert([
            'course_id' => $courseId,
            'term_id'   => $termId
        ]);
    }

    // 3️⃣ 🔥 Redirect to course list (THIS FIXES YOUR ISSUE)
    return redirect()->to(site_url('admin/courses'))
                     ->with('success', 'Course added successfully');
}


public function index()
{
    $coursesModel = new CoursesModel();
    $termsModel   = new CourseTermsModel();
    $db = \Config\Database::connect();

    // 🔹 All terms (dynamic)
    $terms = $termsModel
                ->where('parent_id !=', 0)
                ->findAll();

    $groupedTerms = [];
    foreach ($terms as $term) {
        $groupedTerms[$term['taxonomy']][] = $term;
    }

    // 🔹 All courses
    $courses = $coursesModel->findAll();

    // 🔹 Attach terms to each course
    foreach ($courses as &$course) {
        $course['terms'] = $db->table('course_term_relations ctr')
            ->select('ct.id, ct.name, ct.taxonomy')
            ->join('course_terms ct', 'ct.id = ctr.term_id')
            ->where('ctr.course_id', $course['id'])
            ->get()
            ->getResultArray();
    }
    unset($course);

    return view('admin/courses/dashboard', [
        'groupedTerms' => $groupedTerms,
        'courses'      => $courses
    ]);
}




public function edit($id)
{
    $coursesModel = new \App\Models\CoursesModel();
    $termsModel   = new \App\Models\CourseTermsModel();
    $db = \Config\Database::connect();

    // 1️⃣ Course
    $course = $coursesModel->find($id);
    if (!$course) {
        die('Course not found');
    }

    // 2️⃣ All terms
    $terms = $termsModel->where('parent_id IS NOT NULL')->findAll();

    // 3️⃣ Selected terms
    $selectedTerms = [];
    $rows = $db->table('course_term_relations')
               ->where('course_id', $id)
               ->get()
               ->getResultArray();

    foreach ($rows as $row) {
        $selectedTerms[] = $row['term_id'];
    }

    // 4️⃣ Load view
    return view('admin/courses/edit', [
        'course' => $course,
        'terms' => $terms,
        'selectedTerms' => $selectedTerms
    ]);
}

public function update($id)
{
    $coursesModel = new CoursesModel();
    $db = \Config\Database::connect();

    $coursesModel->update($id, [
        'title'   => $this->request->getPost('title'),
        'content' => $this->request->getPost('content')
    ]);

    // Reset relations
    $db->table('course_term_relations')->where('course_id', $id)->delete();

    foreach ($this->request->getPost('terms') ?? [] as $termId) {
        $db->table('course_term_relations')->insert([
            'course_id' => $id,
            'term_id'   => $termId
        ]);
    }

    return redirect()->to('/admin/courses');
}

public function delete($id)
{
    $db = \Config\Database::connect();

    $db->table('course_term_relations')->where('course_id', $id)->delete();
    (new CoursesModel())->delete($id);

    return redirect()->to('/admin/courses');
}
public function ajaxCourses()
{
    $coursesModel = new \App\Models\CoursesModel();
    $db = \Config\Database::connect();

    $courses = $coursesModel->findAll();

    foreach ($courses as &$course) {
        $course['terms'] = $db->table('course_term_relations ctr')
            ->select('ct.name, ct.taxonomy')
            ->join('course_terms ct', 'ct.id = ctr.term_id')
            ->where('ctr.course_id', $course['id'])
            ->get()
            ->getResultArray();
    }

    return $this->response->setJSON($courses);
}


}
