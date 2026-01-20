<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CourseTermsModel;

class CourseTerms extends BaseController
{
    public function index()
    {
        $model = new CourseTermsModel();
        return view('admin/terms/index', [
            'terms' => $model->findAll()
        ]);
    }

    public function create()
    {
        return view('admin/terms/create');
    }

   public function store()
{
    $model = new \App\Models\CourseTermsModel();

    $termName = $this->request->getPost('name');
    $taxonomy = $this->request->getPost('taxonomy');
    $newLabel = $this->request->getPost('new_taxonomy_label');

    // ✅ Agar NEW Term Type diya gaya hai
    if (!empty($newLabel)) {
        // slug banao
        $taxonomy = strtolower(str_replace(' ', '_', trim($newLabel)));
    }

    // ❗ Safety check
    if (empty($taxonomy)) {
        return redirect()->back()->with('error', 'Please select or add Term Type');
    }

    $data = [
        'name'      => $termName,
        'taxonomy'  => $taxonomy,
        'parent_id' => 1
    ];

    if (!$model->insert($data)) {
        dd($model->errors());
    }

    return redirect()->to('/admin/terms')->with('success', 'Term added successfully');
}


public function edit($id)
{
    $model = new CourseTermsModel();

    return view('admin/terms/edit', [
        'term' => $model->find($id)
    ]);
}

public function update($id)
{
    $model = new CourseTermsModel();

    $model->update($id, [
        'name'     => $this->request->getPost('name'),
        'taxonomy' => $this->request->getPost('taxonomy')
    ]);

    return redirect()->to('/admin/terms');
}

public function delete($id)
{
    $model = new CourseTermsModel();

    // 🔥 First delete relations
    $db = \Config\Database::connect();
    $db->table('course_term_relations')->where('term_id', $id)->delete();

    // 🔥 Then delete term
    $model->delete($id);

    return redirect()->to('/admin/terms');
}


}
