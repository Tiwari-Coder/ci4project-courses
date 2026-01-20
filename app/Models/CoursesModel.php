<?php

namespace App\Models;

use CodeIgniter\Model;

class CoursesModel extends Model
{
    protected $table = 'courses';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'content'];

   public function getCoursesByTerms(array $termIds)
{
    $builder = $this->db->table('courses');
    $builder->select('courses.*');

    if (!empty($termIds)) {
        $builder->join(
            'course_term_relations',
            'course_term_relations.course_id = courses.id',
            'inner'
        );
        $builder->whereIn('course_term_relations.term_id', $termIds);
        $builder->groupBy('courses.id');
    }

    return $builder->get()->getResultArray();
}

}
