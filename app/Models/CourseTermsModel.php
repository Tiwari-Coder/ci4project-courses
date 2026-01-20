<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseTermsModel extends Model
{
    protected $table = 'course_terms';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    // ⭐ THIS IS MANDATORY
    protected $allowedFields = [
        'name',
        'taxonomy',
        'parent_id'
    ];

    /**
     * Get license type terms (for checkbox)
     */
    public function getLicenseTerms()
    {
        return $this->where('taxonomy', 'license_type')
                    ->where('parent_id IS NOT NULL')
                    ->findAll();
    }
}
