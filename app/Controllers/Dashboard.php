<?php

namespace App\Controllers;

use App\Models\StudentModel;
use App\Models\TeacherModel;
use App\Models\ClassModel;
use App\Models\SessionModel;
use App\Models\SubjectModel;
use App\Models\ExamModel;
use App\Models\FeesModel;

class Dashboard extends BaseController
{
    protected $studentModel;
    protected $teacherModel;
    protected $classModel;
    protected $sessionModel;
    protected $subjectModel;
    protected $examModel;
    protected $feesModel;

    public function __construct()
    {
        $this->studentModel = new StudentModel();
        $this->teacherModel = new TeacherModel();
        $this->classModel = new ClassModel();
        $this->sessionModel = new SessionModel();
        $this->subjectModel = new SubjectModel();
        $this->examModel = new ExamModel();
        $this->feesModel = new FeesModel();
    }

    private function getDashboardData($isDemo)
    {
        if ($isDemo) {
            return [
                'title' => 'Dashboard - Smart School (Demo Mode)',
                'pageTitle' => 'Dashboard Overview',
                'totalStudents' => 150,
                'activeStudents' => 142,
                'inactiveStudents' => 8,
                'totalTeachers' => 25,
                'activeTeachers' => 24,
                'totalClasses' => 12,
                'totalSections' => 24,
                'totalSubjects' => 35,
                'currentSession' => (object)['name' => '2024-2025 Academic Year', 'is_active' => 1],
                'recentStudents' => $this->getDemoStudents(),
                'upcomingEvents' => $this->getDemoEvents(),
                'recentActivities' => $this->getDemoActivities(),
                'monthlyStats' => $this->getDemoMonthlyStats(),
                'feeStats' => $this->getDemoFeeStats(),
                'isDemo' => true
            ];
        }

        return [
            'title' => 'Dashboard - Smart School',
            'pageTitle' => 'Dashboard Overview',
            'totalStudents' => $this->studentModel->countAll(),
            'activeStudents' => $this->studentModel->where('is_active', 'yes')->countAllResults(),
            'inactiveStudents' => $this->studentModel->where('is_active', 'no')->countAllResults(),
            'totalTeachers' => $this->teacherModel->countAll(),
            'activeTeachers' => $this->teacherModel->where('is_active', 'yes')->countAllResults(),
            'totalClasses' => $this->classModel->countAll(),
            'totalSections' => $this->getSectionCount(),
            'totalSubjects' => $this->subjectModel->countAll(),
            'currentSession' => $this->sessionModel->getCurrentSession(),
            'recentStudents' => $this->studentModel->getRecentStudents(5),
            'upcomingEvents' => $this->getUpcomingEvents(),
            'recentActivities' => $this->getRecentActivities(),
            'monthlyStats' => $this->getMonthlyStats(),
            'feeStats' => $this->getFeeStats(),
            'isDemo' => false
        ];
    }

    public function index()
    {
        // Check if in demo mode
        $isDemo = session()->get('is_demo', false);
        
        $data = $this->getDashboardData($isDemo);

        return view('dashboard/index', $data);
    }

    /**
     * Students Management Page
     */
    public function students()
    {
        return redirect()->to(base_url('students'));
    }

    /**
     * Teachers Management Page
     */
    public function teachers()
    {
        $data = [
            'title' => 'Teachers Management - Smart School',
            'pageTitle' => 'Teachers Management',
            'teachers' => $this->teacherModel->getAllTeachersWithDetails(),
            'totalTeachers' => $this->teacherModel->countAll(),
            'activeTeachers' => $this->teacherModel->where('is_active', 'yes')->countAllResults(),
            'subjects' => $this->subjectModel->findAll()
        ];

        return view('dashboard/teachers', $data);
    }

    /**
     * Classes Management Page
     */
    public function classes()
    {
        $data = [
            'title' => 'Classes Management - Smart School',
            'pageTitle' => 'Classes & Sections',
            'classes' => $this->classModel->getAllClassesWithSections(),
            'totalClasses' => $this->classModel->countAll(),
            'totalSections' => $this->getSectionCount()
        ];

        return view('dashboard/classes', $data);
    }

    /**
     * Subjects Management Page
     */
    public function subjects()
    {
        $data = [
            'title' => 'Subjects Management - Smart School',
            'pageTitle' => 'Subjects Management',
            'subjects' => $this->subjectModel->getAllSubjectsWithDetails(),
            'totalSubjects' => $this->subjectModel->countAll(),
            'classes' => $this->classModel->findAll()
        ];

        return view('dashboard/subjects', $data);
    }

    /**
     * Exams Management Page
     */
    public function exams()
    {
        $data = [
            'title' => 'Exams Management - Smart School',
            'pageTitle' => 'Examinations',
            'exams' => $this->examModel->getAllExamsWithDetails(),
            'totalExams' => $this->examModel->countAll(),
            'upcomingExams' => $this->examModel->getUpcomingExams(),
            'classes' => $this->classModel->findAll()
        ];

        return view('dashboard/exams', $data);
    }

    /**
     * Library Management Page
     */
    public function library()
    {
        $data = [
            'title' => 'Library Management - Smart School',
            'pageTitle' => 'Library Management',
            'totalBooks' => $this->getBookCount(),
            'availableBooks' => $this->getAvailableBookCount(),
            'issuedBooks' => $this->getIssuedBookCount(),
            'recentIssues' => $this->getRecentBookIssues()
        ];

        return view('dashboard/library', $data);
    }

    /**
     * Fees Management Page
     */
    public function fees()
    {
        $data = [
            'title' => 'Fees Management - Smart School',
            'pageTitle' => 'Fee Collection',
            'feeStats' => $this->getFeeStats(),
            'recentPayments' => $this->getRecentFeePayments(),
            'pendingFees' => $this->getPendingFees()
        ];

        return view('dashboard/fees', $data);
    }

    /**
     * Transport Management Page
     */
    public function transport()
    {
        $data = [
            'title' => 'Transport Management - Smart School',
            'pageTitle' => 'Transport Management',
            'totalRoutes' => $this->getRouteCount(),
            'totalVehicles' => $this->getVehicleCount(),
            'studentsUsingTransport' => $this->getTransportStudentCount()
        ];

        return view('dashboard/transport', $data);
    }

    /**
     * Hostel Management Page
     */
    public function hostel()
    {
        $data = [
            'title' => 'Hostel Management - Smart School',
            'pageTitle' => 'Hostel Management',
            'totalRooms' => $this->getHostelRoomCount(),
            'occupiedRooms' => $this->getOccupiedRoomCount(),
            'hostelStudents' => $this->getHostelStudentCount()
        ];

        return view('hostel/index', $data);
    }

    /**
     * Reports Page
     */
    public function reports()
    {
        $data = [
            'title' => 'Reports - Smart School',
            'pageTitle' => 'Reports & Analytics',
            'studentReports' => $this->getStudentReports(),
            'feeReports' => $this->getFeeReports(),
            'examReports' => $this->getExamReports()
        ];

        return view('dashboard/reports', $data);
    }

    /**
     * Settings Page
     */
    public function settings()
    {
        $data = [
            'title' => 'Settings - Smart School',
            'pageTitle' => 'System Settings',
            'schoolSettings' => $this->getSchoolSettings(),
            'sessions' => $this->sessionModel->findAll()
        ];

        return view('dashboard/settings', $data);
    }

    // Helper Methods

    private function getSectionCount()
    {
        $db = \Config\Database::connect();
        return $db->table('sections')->countAll();
    }

    private function getRecentActivities()
    {
        return [
            [
                'type' => 'student_admission',
                'message' => 'New student John Doe admitted to Class 10-A',
                'time' => '2 hours ago',
                'icon' => 'fas fa-user-plus',
                'color' => 'success'
            ],
            [
                'type' => 'fee_payment',
                'message' => 'Fee payment received from Jane Smith',
                'time' => '4 hours ago',
                'icon' => 'fas fa-money-bill-wave',
                'color' => 'info'
            ],
            [
                'type' => 'exam_scheduled',
                'message' => 'Mid-term exam scheduled for Class 9',
                'time' => '1 day ago',
                'icon' => 'fas fa-clipboard-list',
                'color' => 'warning'
            ]
        ];
    }

    private function getMonthlyStats()
    {
        $currentMonth = date('Y-m');
        
        return [
            'newAdmissions' => $this->studentModel
                ->where('DATE_FORMAT(created_at, "%Y-%m")', $currentMonth)
                ->countAllResults(),
            'feeCollection' => $this->getMonthlyFeeCollection(),
            'examsConducted' => $this->getMonthlyExamCount()
        ];
    }

    private function getFeeStats()
    {
        $db = \Config\Database::connect();
        
        return [
            'totalCollection' => $db->table('fee_receipt_no')
                ->select('SUM(amount) as total')
                ->get()
                ->getRow(),
            'monthlyCollection' => $this->getMonthlyFeeCollection(),
            'pendingAmount' => $this->getPendingFeeAmount()
        ];
    }

    private function getBookCount()
    {
        $db = \Config\Database::connect();
        return $db->table('books')->countAll();
    }

    private function getAvailableBookCount()
    {
        $db = \Config\Database::connect();
        return $db->table('books')->where('available', 'yes')->countAllResults();
    }

    private function getIssuedBookCount()
    {
        $db = \Config\Database::connect();
        return $db->table('book_issues')->where('is_returned', 0)->countAllResults();
    }

    private function getRecentBookIssues()
    {
        $db = \Config\Database::connect();
        return $db->table('book_issues bi')
            ->select('bi.*, b.book_title, s.firstname, s.lastname')
            ->join('books b', 'bi.book_id = b.id')
            ->join('libarary_members lm', 'bi.member_id = lm.id')
            ->join('students s', 'lm.member_id = s.id')
            ->where('lm.member_type', 'student')
            ->orderBy('bi.created_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();
    }

    private function getMonthlyFeeCollection()
    {
        $db = \Config\Database::connect();
        $currentMonth = date('Y-m');
        
        return $db->table('fee_receipt_no')
            ->select('SUM(amount) as total')
            ->where('DATE_FORMAT(created_at, "%Y-%m")', $currentMonth)
            ->get()
            ->getRow();
    }

    private function getPendingFeeAmount()
    {
        return 50000;
    }

    private function getMonthlyExamCount()
    {
        $db = \Config\Database::connect();
        $currentMonth = date('Y-m');
        
        return $db->table('exams')
            ->where('DATE_FORMAT(created_at, "%Y-%m")', $currentMonth)
            ->countAllResults();
    }

    private function getRecentFeePayments()
    {
        $db = \Config\Database::connect();
        return $db->table('fee_receipt fr')
            ->select('fr.*, s.firstname, s.lastname, s.admission_no')
            ->join('students s', 'fr.student_id = s.id')
            ->orderBy('fr.created_at', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();
    }

    private function getPendingFees()
    {
        return [];
    }

    private function getRouteCount()
    {
        $db = \Config\Database::connect();
        return $db->table('vehicle_routes')->countAll();
    }

    private function getVehicleCount()
    {
        $db = \Config\Database::connect();
        return $db->table('vehicles')->countAll();
    }

    private function getTransportStudentCount()
    {
        $db = \Config\Database::connect();
        return $db->table('student_session')
            ->where('vehroute_id IS NOT NULL')
            ->countAllResults();
    }

    private function getHostelRoomCount()
    {
        $db = \Config\Database::connect();
        return $db->table('hostel_rooms')->countAll();
    }

    private function getOccupiedRoomCount()
    {
        $db = \Config\Database::connect();
        return $db->table('student_session')
            ->where('hostel_room_id IS NOT NULL')
            ->countAllResults();
    }

    private function getHostelStudentCount()
    {
        $db = \Config\Database::connect();
        return $db->table('student_session')
            ->where('hostel_room_id IS NOT NULL')
            ->countAllResults();
    }

    private function getStudentReports()
    {
        return [
            'classWiseCount' => $this->getClassWiseStudentCount(),
            'genderWiseCount' => $this->getGenderWiseStudentCount(),
            'admissionTrend' => $this->getAdmissionTrend()
        ];
    }

    private function getFeeReports()
    {
        return [
            'collectionTrend' => $this->getFeeCollectionTrend(),
            'defaulters' => $this->getFeeDefaulters()
        ];
    }

    private function getExamReports()
    {
        return [
            'upcomingExams' => $this->examModel->getUpcomingExams(),
            'recentResults' => $this->getRecentExamResults()
        ];
    }

    private function getClassWiseStudentCount()
    {
        $db = \Config\Database::connect();
        return $db->table('student_session ss')
            ->select('c.class, COUNT(*) as count')
            ->join('classes c', 'ss.class_id = c.id')
            ->groupBy('c.id')
            ->get()
            ->getResultArray();
    }

    private function getGenderWiseStudentCount()
    {
        return $this->studentModel
            ->select('gender, COUNT(*) as count')
            ->where('is_active', 'yes')
            ->groupBy('gender')
            ->findAll();
    }

    private function getAdmissionTrend()
    {
        return $this->studentModel
            ->select('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at >=', date('Y-01-01'))
            ->groupBy('month')
            ->orderBy('month')
            ->findAll();
    }

    private function getFeeCollectionTrend()
    {
        $db = \Config\Database::connect();
        return $db->table('fee_receipt')
            ->select('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as total')
            ->where('created_at >=', date('Y-01-01'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->getResultArray();
    }

    private function getFeeDefaulters()
    {
        return [];
    }

    private function getRecentExamResults()
    {
        $db = \Config\Database::connect();
        return $db->table('exam_results er')
            ->select('er.*, e.exam, s.firstname, s.lastname')
            ->join('exams e', 'er.exam_id = e.id')
            ->join('students s', 'er.student_id = s.id')
            ->orderBy('er.created_at', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();
    }

    private function getSchoolSettings()
    {
        $db = \Config\Database::connect();
        $settings = $db->table('sch_settings')->get()->getResultArray();
        
        $settingsArray = [];
        foreach ($settings as $setting) {
            $settingsArray[$setting['name']] = $setting['value'];
        }
        
        return $settingsArray;
    }

    private function getDemoActivities()
    {
        return [
            [
                'type' => 'student_admission',
                'message' => 'Demo: New student admission completed',
                'time' => '1 hour ago',
                'icon' => 'fas fa-user-plus',
                'color' => 'success'
            ],
            [
                'type' => 'fee_payment',
                'message' => 'Demo: Fee payment processed',
                'time' => '3 hours ago',
                'icon' => 'fas fa-money-bill-wave',
                'color' => 'info'
            ]
        ];
    }

    private function getDemoMonthlyStats()
    {
        return [
            'newAdmissions' => 15,
            'feeCollection' => 125000,
            'examsConducted' => 3
        ];
    }

    private function getDemoFeeStats()
    {
        return [
            'totalCollection' => 2500000,
            'monthlyCollection' => 125000,
            'pendingAmount' => 75000
        ];
    }

    private function getUpcomingEvents()
    {
        // This would typically fetch from an events table
        // For now, return sample data
        return [
            [
                'title' => 'Parent-Teacher Meeting',
                'start_date' => date('Y-m-d', strtotime('+3 days')),
                'time' => '10:00 AM',
                'note' => 'Annual parent-teacher conference to discuss student progress and academic performance.'
            ],
            [
                'title' => 'Science Fair',
                'start_date' => date('Y-m-d', strtotime('+1 week')),
                'time' => '2:00 PM',
                'note' => 'Students will showcase their innovative science projects and experiments.'
            ],
            [
                'title' => 'Sports Day',
                'start_date' => date('Y-m-d', strtotime('+2 weeks')),
                'time' => '9:00 AM',
                'note' => 'Annual sports competition featuring various athletic events and competitions.'
            ]
        ];
    }
    
    private function getDemoStudents()
    {
        return [
            [
                'id' => 1,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@demo.com',
                'class_name' => 'Grade 10-A',
                'admission_date' => '2024-01-15'
            ],
            [
                'id' => 2,
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'jane.smith@demo.com',
                'class_name' => 'Grade 9-B',
                'admission_date' => '2024-01-20'
            ],
            [
                'id' => 3,
                'first_name' => 'Mike',
                'last_name' => 'Johnson',
                'email' => 'mike.johnson@demo.com',
                'class_name' => 'Grade 11-A',
                'admission_date' => '2024-01-25'
            ],
            [
                'id' => 4,
                'first_name' => 'Sarah',
                'last_name' => 'Williams',
                'email' => 'sarah.williams@demo.com',
                'class_name' => 'Grade 10-B',
                'admission_date' => '2024-02-01'
            ],
            [
                'id' => 5,
                'first_name' => 'David',
                'last_name' => 'Brown',
                'email' => 'david.brown@demo.com',
                'class_name' => 'Grade 12-A',
                'admission_date' => '2024-02-05'
            ]
        ];
    }
    
    private function getDemoEvents()
    {
        return [
            [
                'title' => 'Demo Parent-Teacher Conference',
                'start_date' => date('Y-m-d', strtotime('+2 days')),
                'time' => '10:00 AM',
                'note' => 'Demo event: Meet with teachers to discuss your child\'s academic progress and development.'
            ],
            [
                'title' => 'Demo Science Exhibition',
                'start_date' => date('Y-m-d', strtotime('+5 days')),
                'time' => '2:00 PM',
                'note' => 'Demo event: Students showcase innovative science projects and research findings.'
            ],
            [
                'title' => 'Demo Annual Sports Meet',
                'start_date' => date('Y-m-d', strtotime('+1 week')),
                'time' => '9:00 AM',
                'note' => 'Demo event: Annual athletic competition featuring track and field events.'
            ],
            [
                'title' => 'Demo Cultural Program',
                'start_date' => date('Y-m-d', strtotime('+10 days')),
                'time' => '4:00 PM',
                'note' => 'Demo event: Cultural celebration featuring music, dance, and artistic performances.'
            ]
        ];
    }
}