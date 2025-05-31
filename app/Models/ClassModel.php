<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassModel extends Model
{
    protected $table = 'classes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['class', 'is_active'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getAllClassesWithSections()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('classes');
        $builder->select('classes.*, GROUP_CONCAT(sections.section SEPARATOR ", ") as sections');
        $builder->join('class_sections', 'classes.id = class_sections.class_id', 'left');
        $builder->join('sections', 'class_sections.section_id = sections.id', 'left');
        $builder->where('classes.is_active', 'yes');
        $builder->groupBy('classes.id');
        $builder->orderBy('classes.class', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    public function getClassSections($classId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('class_sections');
        $builder->select('class_sections.*, sections.section, classes.class');
        $builder->join('sections', 'class_sections.section_id = sections.id');
        $builder->join('classes', 'class_sections.class_id = classes.id');
        $builder->where('class_sections.class_id', $classId);
        $builder->where('class_sections.is_active', 'yes');
        
        return $builder->get()->getResultArray();
    }

    public function getActiveClasses()
    {
        return $this->where('is_active', 'yes')
                    ->orderBy('class', 'ASC')
                    ->findAll();
    }

    public function getStudentCount($classId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('student_session');
        $builder->join('students', 'student_session.student_id = students.id');
        $builder->where('student_session.class_id', $classId);
        $builder->where('students.is_active', 'yes');
        
        return $builder->countAllResults();
    }
}