<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'admission_no', 'roll_no', 'admission_date', 'firstname', 'middlename', 'lastname',
        'image', 'mobileno', 'email', 'state', 'city', 'pincode', 'religion', 'cast',
        'dob', 'gender', 'current_address', 'permanent_address', 'category_id', 'route_id',
        'school_house_id', 'blood_group', 'vehroute_id', 'hostel_room_id', 'adhar_no',
        'samagra_id', 'bank_account_no', 'bank_name', 'ifsc_code', 'guardian_is',
        'father_name', 'father_phone', 'father_occupation', 'mother_name', 'mother_phone',
        'mother_occupation', 'guardian_name', 'guardian_relation', 'guardian_phone',
        'guardian_occupation', 'guardian_address', 'guardian_email', 'father_pic',
        'mother_pic', 'guardian_pic', 'is_active', 'previous_school', 'height', 'weight',
        'measurement_date', 'app_key', 'parent_app_key', 'disable_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getAllStudentsWithDetails()
    {
        return $this->select('students.*, classes.class as class_name, sections.section as section_name, sessions.session')
                    ->join('student_session', 'students.id = student_session.student_id')
                    ->join('classes', 'student_session.class_id = classes.id')
                    ->join('sections', 'student_session.section_id = sections.id')
                    ->join('sessions', 'student_session.session_id = sessions.id')
                    ->where('students.is_active', 'yes')
                    ->orderBy('students.admission_no', 'ASC')
                    ->findAll();
    }

    public function getRecentStudents($limit = 5)
    {
        return $this->select('students.*, classes.class as class_name, sections.section as section_name')
                    ->join('student_session', 'students.id = student_session.student_id')
                    ->join('classes', 'student_session.class_id = classes.id')
                    ->join('sections', 'student_session.section_id = sections.id')
                    ->where('students.is_active', 'yes')
                    ->orderBy('students.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    public function getStudentById($id)
    {
        return $this->select('students.*, classes.class as class_name, sections.section as section_name, sessions.session')
                    ->join('student_session', 'students.id = student_session.student_id')
                    ->join('classes', 'student_session.class_id = classes.id')
                    ->join('sections', 'student_session.section_id = sections.id')
                    ->join('sessions', 'student_session.session_id = sessions.id')
                    ->where('students.id', $id)
                    ->first();
    }

    public function getStudentsByClass($classId, $sectionId = null)
    {
        $builder = $this->select('students.*, classes.class as class_name, sections.section as section_name')
                        ->join('student_session', 'students.id = student_session.student_id')
                        ->join('classes', 'student_session.class_id = classes.id')
                        ->join('sections', 'student_session.section_id = sections.id')
                        ->where('student_session.class_id', $classId)
                        ->where('students.is_active', 'yes');
        
        if ($sectionId) {
            $builder->where('student_session.section_id', $sectionId);
        }
        
        return $builder->orderBy('students.roll_no', 'ASC')->findAll();
    }

    public function searchStudents($searchTerm)
    {
        return $this->select('students.*, classes.class as class_name, sections.section as section_name')
                    ->join('student_session', 'students.id = student_session.student_id')
                    ->join('classes', 'student_session.class_id = classes.id')
                    ->join('sections', 'student_session.section_id = sections.id')
                    ->groupStart()
                        ->like('students.firstname', $searchTerm)
                        ->orLike('students.lastname', $searchTerm)
                        ->orLike('students.admission_no', $searchTerm)
                        ->orLike('students.roll_no', $searchTerm)
                    ->groupEnd()
                    ->where('students.is_active', 'yes')
                    ->orderBy('students.firstname', 'ASC')
                    ->findAll();
    }
}