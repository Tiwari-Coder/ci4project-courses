<?php

namespace App\Controllers;

use App\Models\CoursesModel;
use App\Models\CourseTermsModel;
use CodeIgniter\Controller;

class Courses extends Controller
{
   public function index()
{
    $coursesModel = new CoursesModel();
    $termsModel   = new CourseTermsModel();

    // 🔹 GET filters
    $termIds = $this->request->getGet('terms') ?? [];
    $version = $this->request->getGet('filter_version');
    $keyword = $this->request->getGet('keyword');
    $page    = (int) ($this->request->getGet('page') ?? 1);
    $perPage = 6;

    // 🔹 Base builder
    $builder = $coursesModel->select('courses.*')->distinct();

    if (!empty($termIds)) {
        $builder->join('course_term_relations ctr', 'ctr.course_id = courses.id');
        $builder->whereIn('ctr.term_id', $termIds);
    }

    if (!empty($version)) {
        $builder->where('course_version', $version);
    }

    if (!empty($keyword)) {
        $builder->groupStart()
                ->like('title', $keyword)
                ->orLike('content', $keyword)
                ->groupEnd();
    }

    // 🔹 Pagination
    $totalCourses = $builder->countAllResults(false);
    $courses = $builder
        ->limit($perPage, ($page - 1) * $perPage)
        ->get()
        ->getResultArray();

    /* ===============================
       🔥 FILTER SIDEBAR DATA (MISSING PART)
    ================================ */

    // 1️⃣ Unique taxonomies
    $taxonomies = $termsModel
        ->select('taxonomy')
        ->groupBy('taxonomy')
        ->findAll();

    // 2️⃣ Taxonomy wise terms
    $groupedTerms = [];
    foreach ($taxonomies as $tax) {
        $groupedTerms[$tax['taxonomy']] =
            $termsModel->where('taxonomy', $tax['taxonomy'])->findAll();
    }

    /* =============================== */

    // 🔹 AJAX request → only course list
    if ($this->request->isAJAX()) {
        return view('courses/partials/course_list', [
            'courses' => $courses,
            'currentPage' => $page,
            'perPage' => $perPage,
            'totalCourses' => $totalCourses
        ]);
    }

    // 🔹 Normal page load
    return view('courses/index', [
        'groupedTerms' => $groupedTerms,   // 🔥 VERY IMPORTANT
        'courses'      => $courses,
        'currentPage'  => $page,
        'perPage'      => $perPage,
        'totalCourses' => $totalCourses
    ]);
}

}
