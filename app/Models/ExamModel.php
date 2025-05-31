<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamModel extends Model
{
    protected $table = 'exams';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'exam',
        'note',
        'session_id',
        'is_active',
        'created_at',
        'updated_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'exam' => 'required|min_length[2]|max_length[100]',
        'session_id' => 'required|integer',
        'is_active' => 'in_list[yes,no]'
    ];
    
    protected $validationMessages = [
        'exam' => [
            'required' => 'Exam name is required',
            'min_length' => 'Exam name must be at least 2 characters long',
            'max_length' => 'Exam name cannot exceed 100 characters'
        ],
        'session_id' => [
            'required' => 'Session is required',
            'integer' => 'Invalid session selection'
        ]
    ];
    
    /**
     * Get all exams with session details
     */
    public function getAllExamsWithDetails()
    {
        return $this->select('exams.*, sessions.session')
                    ->join('sessions', 'exams.session_id = sessions.id', 'left')
                    ->orderBy('exams.created_at', 'DESC')
                    ->findAll();
    }
    
    /**
     * Get active exams
     */
    public function getActiveExams()
    {
        return $this->where('is_active', 'yes')
                    ->orderBy('exam', 'ASC')
                    ->findAll();
    }
    
    /**
     * Get exam with session details
     */
    public function getExamWithDetails($id)
    {
        return $this->select('exams.*, sessions.session')
                    ->join('sessions', 'exams.session_id = sessions.id', 'left')
                    ->where('exams.id', $id)
                    ->first();
    }
    
    /**
     * Get exams by session
     */
    public function getExamsBySession($sessionId)
    {
        return $this->where('session_id', $sessionId)
                    ->where('is_active', 'yes')
                    ->orderBy('exam', 'ASC')
                    ->findAll();
    }
    
    /**
     * Get upcoming exams (based on exam schedules)
     */
    public function getUpcomingExams($limit = 5)
    {
        $db = \Config\Database::connect();
        
        return $db->table('exam_schedules es')
                  ->select('es.*, e.exam, c.class, c.section, s.name as subject_name')
                  ->join('exams e', 'es.exam_id = e.id')
                  ->join('classes c', 'es.class_id = c.id')
                  ->join('subjects s', 'es.subject_id = s.id')
                  ->where('es.date_of_exam >=', date('Y-m-d'))
                  ->where('e.is_active', 'yes')
                  ->orderBy('es.date_of_exam', 'ASC')
                  ->orderBy('es.time_from', 'ASC')
                  ->limit($limit)
                  ->get()
                  ->getResultArray();
    }
    
    /**
     * Get recent exams
     */
    public function getRecentExams($limit = 5)
    {
        return $this->select('exams.*, sessions.session')
                    ->join('sessions', 'exams.session_id = sessions.id', 'left')
                    ->orderBy('exams.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
    
    /**
     * Get exams for dropdown
     */
    public function getExamsForDropdown($sessionId = null)
    {
        $builder = $this->select('id, exam')
                        ->where('is_active', 'yes');
        
        if ($sessionId) {
            $builder->where('session_id', $sessionId);
        }
        
        $exams = $builder->orderBy('exam', 'ASC')->findAll();
        
        $dropdown = [];
        foreach ($exams as $exam) {
            $dropdown[$exam['id']] = $exam['exam'];
        }
        
        return $dropdown;
    }
    
    /**
     * Get exam statistics
     */
    public function getExamStats()
    {
        $stats = [
            'total' => $this->countAll(),
            'active' => $this->where('is_active', 'yes')->countAllResults(),
            'inactive' => $this->where('is_active', 'no')->countAllResults(),
            'current_session' => $this->getCurrentSessionExamCount()
        ];
        
        return $stats;
    }
    
    /**
     * Get current session exam count
     */
    private function getCurrentSessionExamCount()
    {
        $db = \Config\Database::connect();
        $currentSession = $db->table('sessions')
                            ->where('is_active', 'yes')
                            ->get()
                            ->getRow();
        
        if ($currentSession) {
            return $this->where('session_id', $currentSession->id)
                       ->where('is_active', 'yes')
                       ->countAllResults();
        }
        
        return 0;
    }
    
    /**
     * Search exams
     */
    public function searchExams($keyword)
    {
        return $this->select('exams.*, sessions.session')
                    ->join('sessions', 'exams.session_id = sessions.id', 'left')
                    ->groupStart()
                        ->like('exams.exam', $keyword)
                        ->orLike('exams.note', $keyword)
                        ->orLike('sessions.session', $keyword)
                    ->groupEnd()
                    ->orderBy('exams.exam', 'ASC')
                    ->findAll();
    }
    
    /**
     * Get exam schedules by exam ID
     */
    public function getExamSchedules($examId)
    {
        $db = \Config\Database::connect();
        
        return $db->table('exam_schedules es')
                  ->select('es.*, c.class, c.section, s.name as subject_name, s.code as subject_code')
                  ->join('classes c', 'es.class_id = c.id')
                  ->join('subjects s', 'es.subject_id = s.id')
                  ->where('es.exam_id', $examId)
                  ->orderBy('es.date_of_exam', 'ASC')
                  ->orderBy('es.time_from', 'ASC')
                  ->get()
                  ->getResultArray();
    }
    
    /**
     * Get exam results summary
     */
    public function getExamResultsSummary($examId)
    {
        $db = \Config\Database::connect();
        
        return $db->table('exam_results er')
                  ->select('c.class, c.section, COUNT(*) as total_students, 
                           AVG(er.marks) as average_marks,
                           MAX(er.marks) as highest_marks,
                           MIN(er.marks) as lowest_marks')
                  ->join('students s', 'er.student_id = s.id')
                  ->join('student_session ss', 's.id = ss.student_id')
                  ->join('classes c', 'ss.class_id = c.id')
                  ->where('er.exam_id', $examId)
                  ->groupBy('c.id')
                  ->orderBy('c.class', 'ASC')
                  ->get()
                  ->getResultArray();
    }
    
    /**
     * Get exam performance by subject
     */
    public function getExamPerformanceBySubject($examId)
    {
        $db = \Config\Database::connect();
        
        return $db->table('exam_results er')
                  ->select('s.name as subject_name, s.code as subject_code,
                           COUNT(*) as total_students,
                           AVG(er.marks) as average_marks,
                           MAX(er.marks) as highest_marks,
                           MIN(er.marks) as lowest_marks,
                           SUM(CASE WHEN er.marks >= er.pass_marks THEN 1 ELSE 0 END) as passed_students')
                  ->join('subjects s', 'er.subject_id = s.id')
                  ->where('er.exam_id', $examId)
                  ->groupBy('s.id')
                  ->orderBy('s.name', 'ASC')
                  ->get()
                  ->getResultArray();
    }
    
    /**
     * Bulk update exam status
     */
    public function bulkUpdateStatus($ids, $status)
    {
        return $this->whereIn('id', $ids)
                    ->set('is_active', $status)
                    ->set('updated_at', date('Y-m-d H:i:s'))
                    ->update();
    }
    
    /**
     * Check if exam has schedules
     */
    public function hasSchedules($examId)
    {
        $db = \Config\Database::connect();
        return $db->table('exam_schedules')
                  ->where('exam_id', $examId)
                  ->countAllResults() > 0;
    }
    
    /**
     * Check if exam has results
     */
    public function hasResults($examId)
    {
        $db = \Config\Database::connect();
        return $db->table('exam_results')
                  ->where('exam_id', $examId)
                  ->countAllResults() > 0;
    }
    
    /**
     * Get exam completion status
     */
    public function getExamCompletionStatus($examId)
    {
        $db = \Config\Database::connect();
        
        $totalSchedules = $db->table('exam_schedules')
                            ->where('exam_id', $examId)
                            ->countAllResults();
        
        $completedSchedules = $db->table('exam_schedules')
                                ->where('exam_id', $examId)
                                ->where('date_of_exam <', date('Y-m-d'))
                                ->countAllResults();
        
        return [
            'total_schedules' => $totalSchedules,
            'completed_schedules' => $completedSchedules,
            'completion_percentage' => $totalSchedules > 0 ? round(($completedSchedules / $totalSchedules) * 100, 2) : 0
        ];
    }
}