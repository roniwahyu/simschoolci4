<?php

namespace App\Models;

use CodeIgniter\Model;

class SubjectModel extends Model
{
    protected $table = 'subjects';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name',
        'code',
        'type',
        'class_id',
        'teacher_id',
        'is_active',
        'created_at',
        'updated_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[100]',
        'code' => 'required|min_length[2]|max_length[20]|is_unique[subjects.code,id,{id}]',
        'type' => 'required|in_list[theory,practical,both]',
        'class_id' => 'required|integer',
        'is_active' => 'in_list[yes,no]'
    ];
    
    protected $validationMessages = [
        'name' => [
            'required' => 'Subject name is required',
            'min_length' => 'Subject name must be at least 2 characters long',
            'max_length' => 'Subject name cannot exceed 100 characters'
        ],
        'code' => [
            'required' => 'Subject code is required',
            'min_length' => 'Subject code must be at least 2 characters long',
            'max_length' => 'Subject code cannot exceed 20 characters',
            'is_unique' => 'Subject code already exists'
        ],
        'type' => [
            'required' => 'Subject type is required',
            'in_list' => 'Subject type must be theory, practical, or both'
        ],
        'class_id' => [
            'required' => 'Class is required',
            'integer' => 'Invalid class selection'
        ]
    ];
    
    /**
     * Get all subjects with class and teacher details
     */
    public function getAllSubjectsWithDetails()
    {
        return $this->select('subjects.*, classes.class, classes.section, 
                            staff.name as teacher_name, staff.surname as teacher_surname')
                    ->join('classes', 'subjects.class_id = classes.id', 'left')
                    ->join('staff', 'subjects.teacher_id = staff.id', 'left')
                    ->orderBy('classes.class', 'ASC')
                    ->orderBy('subjects.name', 'ASC')
                    ->findAll();
    }
    
    /**
     * Get subjects by class ID
     */
    public function getSubjectsByClass($classId)
    {
        return $this->where('class_id', $classId)
                    ->where('is_active', 'yes')
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }
    
    /**
     * Get subjects by teacher ID
     */
    public function getSubjectsByTeacher($teacherId)
    {
        return $this->select('subjects.*, classes.class, classes.section')
                    ->join('classes', 'subjects.class_id = classes.id', 'left')
                    ->where('subjects.teacher_id', $teacherId)
                    ->where('subjects.is_active', 'yes')
                    ->orderBy('classes.class', 'ASC')
                    ->orderBy('subjects.name', 'ASC')
                    ->findAll();
    }
    
    /**
     * Get active subjects
     */
    public function getActiveSubjects()
    {
        return $this->where('is_active', 'yes')
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }
    
    /**
     * Get subject with class and teacher details
     */
    public function getSubjectWithDetails($id)
    {
        return $this->select('subjects.*, classes.class, classes.section, 
                            staff.name as teacher_name, staff.surname as teacher_surname,
                            staff.employee_id')
                    ->join('classes', 'subjects.class_id = classes.id', 'left')
                    ->join('staff', 'subjects.teacher_id = staff.id', 'left')
                    ->where('subjects.id', $id)
                    ->first();
    }
    
    /**
     * Get subjects count by type
     */
    public function getSubjectCountByType()
    {
        return $this->select('type, COUNT(*) as count')
                    ->where('is_active', 'yes')
                    ->groupBy('type')
                    ->findAll();
    }
    
    /**
     * Get subjects for dropdown
     */
    public function getSubjectsForDropdown($classId = null)
    {
        $builder = $this->select('id, name, code')
                        ->where('is_active', 'yes');
        
        if ($classId) {
            $builder->where('class_id', $classId);
        }
        
        $subjects = $builder->orderBy('name', 'ASC')->findAll();
        
        $dropdown = [];
        foreach ($subjects as $subject) {
            $dropdown[$subject['id']] = $subject['name'] . ' (' . $subject['code'] . ')';
        }
        
        return $dropdown;
    }
    
    /**
     * Check if subject code exists
     */
    public function isCodeExists($code, $excludeId = null)
    {
        $builder = $this->where('code', $code);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    /**
     * Get subjects statistics
     */
    public function getSubjectsStats()
    {
        $stats = [
            'total' => $this->countAll(),
            'active' => $this->where('is_active', 'yes')->countAllResults(),
            'inactive' => $this->where('is_active', 'no')->countAllResults(),
            'theory' => $this->where('type', 'theory')->where('is_active', 'yes')->countAllResults(),
            'practical' => $this->where('type', 'practical')->where('is_active', 'yes')->countAllResults(),
            'both' => $this->where('type', 'both')->where('is_active', 'yes')->countAllResults()
        ];
        
        return $stats;
    }
    
    /**
     * Search subjects
     */
    public function searchSubjects($keyword)
    {
        return $this->select('subjects.*, classes.class, classes.section, 
                            staff.name as teacher_name, staff.surname as teacher_surname')
                    ->join('classes', 'subjects.class_id = classes.id', 'left')
                    ->join('staff', 'subjects.teacher_id = staff.id', 'left')
                    ->groupStart()
                        ->like('subjects.name', $keyword)
                        ->orLike('subjects.code', $keyword)
                        ->orLike('classes.class', $keyword)
                        ->orLike('staff.name', $keyword)
                    ->groupEnd()
                    ->orderBy('subjects.name', 'ASC')
                    ->findAll();
    }
    
    /**
     * Get recent subjects
     */
    public function getRecentSubjects($limit = 5)
    {
        return $this->select('subjects.*, classes.class, classes.section')
                    ->join('classes', 'subjects.class_id = classes.id', 'left')
                    ->orderBy('subjects.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
    
    /**
     * Bulk update subjects status
     */
    public function bulkUpdateStatus($ids, $status)
    {
        return $this->whereIn('id', $ids)
                    ->set('is_active', $status)
                    ->set('updated_at', date('Y-m-d H:i:s'))
                    ->update();
    }
    
    /**
     * Get subjects by multiple classes
     */
    public function getSubjectsByClasses($classIds)
    {
        return $this->select('subjects.*, classes.class, classes.section')
                    ->join('classes', 'subjects.class_id = classes.id', 'left')
                    ->whereIn('subjects.class_id', $classIds)
                    ->where('subjects.is_active', 'yes')
                    ->orderBy('classes.class', 'ASC')
                    ->orderBy('subjects.name', 'ASC')
                    ->findAll();
    }
}