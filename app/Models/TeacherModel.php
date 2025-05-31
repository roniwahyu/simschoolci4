<?php

namespace App\Models;

use CodeIgniter\Model;

class TeacherModel extends Model
{
    protected $table = 'staff';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'employee_id', 'lang_id', 'department_id', 'designation_id', 'qualification',
        'work_exp', 'name', 'surname', 'father_name', 'mother_name', 'contact_no',
        'emergency_contact_no', 'email', 'dob', 'marital_status', 'date_of_joining',
        'date_of_leaving', 'local_address', 'permanent_address', 'note', 'image',
        'password', 'gender', 'account_title', 'bank_account_no', 'bank_name',
        'ifsc_code', 'bank_branch', 'payscale', 'basic_salary', 'epf_no', 'contract_type',
        'shift', 'location', 'facebook', 'twitter', 'linkedin', 'instagram', 'resume',
        'joining_letter', 'resignation_letter', 'other_document_name', 'other_document_file',
        'user_id', 'is_active', 'verification_code', 'zoom_api_key', 'zoom_api_secret',
        'disable_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getAllTeachersWithDetails()
    {
        return $this->select('staff.*, departments.department_name, staff_designation.designation')
                    ->join('departments', 'staff.department_id = departments.id', 'left')
                    ->join('staff_designation', 'staff.designation_id = staff_designation.id', 'left')
                    ->where('staff.is_active', 'yes')
                    ->orderBy('staff.name', 'ASC')
                    ->findAll();
    }

    public function getTeacherById($id)
    {
        return $this->select('staff.*, departments.department_name, staff_designation.designation')
                    ->join('departments', 'staff.department_id = departments.id', 'left')
                    ->join('staff_designation', 'staff.designation_id = staff_designation.id', 'left')
                    ->where('staff.id', $id)
                    ->first();
    }

    public function getTeachersByDepartment($departmentId)
    {
        return $this->select('staff.*, departments.department_name, staff_designation.designation')
                    ->join('departments', 'staff.department_id = departments.id', 'left')
                    ->join('staff_designation', 'staff.designation_id = staff_designation.id', 'left')
                    ->where('staff.department_id', $departmentId)
                    ->where('staff.is_active', 'yes')
                    ->orderBy('staff.name', 'ASC')
                    ->findAll();
    }

    public function searchTeachers($searchTerm)
    {
        return $this->select('staff.*, departments.department_name, staff_designation.designation')
                    ->join('departments', 'staff.department_id = departments.id', 'left')
                    ->join('staff_designation', 'staff.designation_id = staff_designation.id', 'left')
                    ->groupStart()
                        ->like('staff.name', $searchTerm)
                        ->orLike('staff.surname', $searchTerm)
                        ->orLike('staff.employee_id', $searchTerm)
                        ->orLike('staff.email', $searchTerm)
                    ->groupEnd()
                    ->where('staff.is_active', 'yes')
                    ->orderBy('staff.name', 'ASC')
                    ->findAll();
    }

    public function getActiveTeachers()
    {
        return $this->where('is_active', 'yes')
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    public function getTeacherSubjects($teacherId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('subject_timetable');
        $builder->select('subject_group_subjects.name as subject_name, subject_group_subjects.code as subject_code, classes.class, sections.section');
        $builder->join('subject_group_subjects', 'subject_timetable.subject_group_subject_id = subject_group_subjects.id');
        $builder->join('classes', 'subject_timetable.class_id = classes.id');
        $builder->join('sections', 'subject_timetable.section_id = sections.id');
        $builder->where('subject_timetable.staff_id', $teacherId);
        $builder->groupBy('subject_group_subjects.id, classes.id, sections.id');
        
        return $builder->get()->getResultArray();
    }
}