<?php

namespace App\Controllers;

use App\Models\StudentModel;
use App\Models\ClassModel;
use App\Models\SessionModel;
use CodeIgniter\HTTP\ResponseInterface;

class Student extends BaseController
{
    protected $studentModel;
    protected $classModel;
    protected $sessionModel;
    protected $validation;

    public function __construct()
    {
        $this->studentModel = new StudentModel();
        $this->classModel = new ClassModel();
        $this->sessionModel = new SessionModel();
        $this->validation = \Config\Services::validation();
    }

    /**
     * Display students list
     */
    public function index()
    {
        $data = [
            'title' => 'Students Management - Smart School',
            'students' => $this->studentModel->getAllStudentsWithDetails(),
            'classes' => $this->classModel->findAll(),
            'totalStudents' => $this->studentModel->countAll(),
            'activeStudents' => $this->studentModel->where('is_active', 'yes')->countAllResults(),
            'inactiveStudents' => $this->studentModel->where('is_active', 'no')->countAllResults()
        ];

        return view('student/index', $data);
    }

    /**
     * Show student details
     */
    public function show($id)
    {
        $student = $this->studentModel->getStudentById($id);
        
        if (!$student) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Student not found');
        }

        $data = [
            'title' => 'Student Details - Smart School',
            'student' => $student
        ];

        return view('student/show', $data);
    }

    /**
     * Show add student form
     */
    public function create()
    {
        $data = [
            'title' => 'Add New Student - Smart School',
            'classes' => $this->classModel->getAllClassesWithSections(),
            'sessions' => $this->sessionModel->findAll(),
            'validation' => $this->validation
        ];

        return view('student/create', $data);
    }

    /**
     * Store new student
     */
    public function store()
    {
        $rules = [
            'admission_no' => 'required|is_unique[students.admission_no]',
            'firstname' => 'required|min_length[2]|max_length[50]',
            'lastname' => 'required|min_length[2]|max_length[50]',
            'email' => 'permit_empty|valid_email|is_unique[students.email]',
            'mobileno' => 'required|numeric|min_length[10]|max_length[15]',
            'dob' => 'required|valid_date',
            'gender' => 'required|in_list[Male,Female,Other]',
            'class_id' => 'required|integer',
            'section_id' => 'required|integer',
            'session_id' => 'required|integer',
            'image' => 'permit_empty|uploaded[image]|max_size[image,2048]|is_image[image]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Handle image upload
            $imageName = null;
            $image = $this->request->getFile('image');
            if ($image && $image->isValid() && !$image->hasMoved()) {
                $imageName = $image->getRandomName();
                $image->move(ROOTPATH . 'public/uploads/student_images', $imageName);
            }

            // Prepare student data
            $studentData = [
                'admission_no' => $this->request->getPost('admission_no'),
                'roll_no' => $this->request->getPost('roll_no'),
                'admission_date' => $this->request->getPost('admission_date') ?: date('Y-m-d'),
                'firstname' => $this->request->getPost('firstname'),
                'middlename' => $this->request->getPost('middlename'),
                'lastname' => $this->request->getPost('lastname'),
                'image' => $imageName,
                'mobileno' => $this->request->getPost('mobileno'),
                'email' => $this->request->getPost('email'),
                'state' => $this->request->getPost('state'),
                'city' => $this->request->getPost('city'),
                'pincode' => $this->request->getPost('pincode'),
                'religion' => $this->request->getPost('religion'),
                'cast' => $this->request->getPost('cast'),
                'dob' => $this->request->getPost('dob'),
                'gender' => $this->request->getPost('gender'),
                'current_address' => $this->request->getPost('current_address'),
                'permanent_address' => $this->request->getPost('permanent_address'),
                'blood_group' => $this->request->getPost('blood_group'),
                'adhar_no' => $this->request->getPost('adhar_no'),
                'father_name' => $this->request->getPost('father_name'),
                'father_phone' => $this->request->getPost('father_phone'),
                'father_occupation' => $this->request->getPost('father_occupation'),
                'mother_name' => $this->request->getPost('mother_name'),
                'mother_phone' => $this->request->getPost('mother_phone'),
                'mother_occupation' => $this->request->getPost('mother_occupation'),
                'guardian_name' => $this->request->getPost('guardian_name'),
                'guardian_relation' => $this->request->getPost('guardian_relation'),
                'guardian_phone' => $this->request->getPost('guardian_phone'),
                'guardian_occupation' => $this->request->getPost('guardian_occupation'),
                'guardian_address' => $this->request->getPost('guardian_address'),
                'guardian_email' => $this->request->getPost('guardian_email'),
                'is_active' => 'yes',
                'previous_school' => $this->request->getPost('previous_school'),
                'height' => $this->request->getPost('height'),
                'weight' => $this->request->getPost('weight'),
                'measurement_date' => $this->request->getPost('measurement_date')
            ];

            // Insert student
            $studentId = $this->studentModel->insert($studentData);

            // Insert student session record
            $sessionData = [
                'student_id' => $studentId,
                'class_id' => $this->request->getPost('class_id'),
                'section_id' => $this->request->getPost('section_id'),
                'session_id' => $this->request->getPost('session_id'),
                'created_at' => date('Y-m-d H:i:s')
            ];

            $db->table('student_session')->insert($sessionData);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }

            return redirect()->to('/students')->with('success', 'Student added successfully!');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Failed to add student: ' . $e->getMessage());
        }
    }

    /**
     * Show edit student form
     */
    public function edit($id)
    {
        $student = $this->studentModel->getStudentById($id);
        
        if (!$student) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Student not found');
        }

        $data = [
            'title' => 'Edit Student - Smart School',
            'student' => $student,
            'classes' => $this->classModel->getAllClassesWithSections(),
            'sessions' => $this->sessionModel->findAll(),
            'validation' => $this->validation
        ];

        return view('student/edit', $data);
    }

    /**
     * Update student
     */
    public function update($id)
    {
        $student = $this->studentModel->find($id);
        
        if (!$student) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Student not found');
        }

        $rules = [
            'admission_no' => "required|is_unique[students.admission_no,id,{$id}]",
            'firstname' => 'required|min_length[2]|max_length[50]',
            'lastname' => 'required|min_length[2]|max_length[50]',
            'email' => "permit_empty|valid_email|is_unique[students.email,id,{$id}]",
            'mobileno' => 'required|numeric|min_length[10]|max_length[15]',
            'dob' => 'required|valid_date',
            'gender' => 'required|in_list[Male,Female,Other]',
            'image' => 'permit_empty|uploaded[image]|max_size[image,2048]|is_image[image]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        try {
            // Handle image upload
            $imageName = $student['image'];
            $image = $this->request->getFile('image');
            if ($image && $image->isValid() && !$image->hasMoved()) {
                // Delete old image
                if ($imageName && file_exists(ROOTPATH . 'public/uploads/student_images/' . $imageName)) {
                    unlink(ROOTPATH . 'public/uploads/student_images/' . $imageName);
                }
                
                $imageName = $image->getRandomName();
                $image->move(ROOTPATH . 'public/uploads/student_images', $imageName);
            }

            // Prepare student data
            $studentData = [
                'admission_no' => $this->request->getPost('admission_no'),
                'roll_no' => $this->request->getPost('roll_no'),
                'admission_date' => $this->request->getPost('admission_date'),
                'firstname' => $this->request->getPost('firstname'),
                'middlename' => $this->request->getPost('middlename'),
                'lastname' => $this->request->getPost('lastname'),
                'image' => $imageName,
                'mobileno' => $this->request->getPost('mobileno'),
                'email' => $this->request->getPost('email'),
                'state' => $this->request->getPost('state'),
                'city' => $this->request->getPost('city'),
                'pincode' => $this->request->getPost('pincode'),
                'religion' => $this->request->getPost('religion'),
                'cast' => $this->request->getPost('cast'),
                'dob' => $this->request->getPost('dob'),
                'gender' => $this->request->getPost('gender'),
                'current_address' => $this->request->getPost('current_address'),
                'permanent_address' => $this->request->getPost('permanent_address'),
                'blood_group' => $this->request->getPost('blood_group'),
                'adhar_no' => $this->request->getPost('adhar_no'),
                'father_name' => $this->request->getPost('father_name'),
                'father_phone' => $this->request->getPost('father_phone'),
                'father_occupation' => $this->request->getPost('father_occupation'),
                'mother_name' => $this->request->getPost('mother_name'),
                'mother_phone' => $this->request->getPost('mother_phone'),
                'mother_occupation' => $this->request->getPost('mother_occupation'),
                'guardian_name' => $this->request->getPost('guardian_name'),
                'guardian_relation' => $this->request->getPost('guardian_relation'),
                'guardian_phone' => $this->request->getPost('guardian_phone'),
                'guardian_occupation' => $this->request->getPost('guardian_occupation'),
                'guardian_address' => $this->request->getPost('guardian_address'),
                'guardian_email' => $this->request->getPost('guardian_email'),
                'is_active' => $this->request->getPost('is_active') ?: 'yes',
                'previous_school' => $this->request->getPost('previous_school'),
                'height' => $this->request->getPost('height'),
                'weight' => $this->request->getPost('weight'),
                'measurement_date' => $this->request->getPost('measurement_date')
            ];

            // Update student
            $this->studentModel->update($id, $studentData);

            return redirect()->to('/students')->with('success', 'Student updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update student: ' . $e->getMessage());
        }
    }

    /**
     * Delete student
     */
    public function delete($id)
    {
        $student = $this->studentModel->find($id);
        
        if (!$student) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Student not found'
            ]);
        }

        try {
            // Delete student image if exists
            if ($student['image'] && file_exists(ROOTPATH . 'public/uploads/student_images/' . $student['image'])) {
                unlink(ROOTPATH . 'public/uploads/student_images/' . $student['image']);
            }

            // Delete student record (cascade will handle related records)
            $this->studentModel->delete($id);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Student deleted successfully'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete student: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Toggle student status (active/inactive)
     */
    public function toggleStatus($id)
    {
        $student = $this->studentModel->find($id);
        
        if (!$student) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Student not found'
            ]);
        }

        try {
            $newStatus = $student['is_active'] === 'yes' ? 'no' : 'yes';
            $this->studentModel->update($id, ['is_active' => $newStatus]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Student status updated successfully',
                'new_status' => $newStatus
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update student status: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Search students (AJAX)
     */
    public function search()
    {
        $searchTerm = $this->request->getGet('term');
        $classId = $this->request->getGet('class_id');
        $sectionId = $this->request->getGet('section_id');
        
        if ($searchTerm) {
            $students = $this->studentModel->searchStudents($searchTerm);
        } elseif ($classId) {
            $students = $this->studentModel->getStudentsByClass($classId, $sectionId);
        } else {
            $students = $this->studentModel->getAllStudentsWithDetails();
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $students
        ]);
    }

    /**
     * Export students data
     */
    public function export($format = 'excel')
    {
        $students = $this->studentModel->getAllStudentsWithDetails();
        
        if ($format === 'pdf') {
            return $this->exportToPDF($students);
        } else {
            return $this->exportToExcel($students);
        }
    }

    /**
     * Export to Excel
     */
    private function exportToExcel($students)
    {
        $filename = 'students_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // CSV headers
        fputcsv($output, [
            'Admission No', 'Roll No', 'Name', 'Email', 'Mobile', 'Class', 'Section',
            'Father Name', 'Mother Name', 'DOB', 'Gender', 'Address', 'Status'
        ]);
        
        // CSV data
        foreach ($students as $student) {
            fputcsv($output, [
                $student['admission_no'],
                $student['roll_no'],
                $student['firstname'] . ' ' . $student['lastname'],
                $student['email'],
                $student['mobileno'],
                $student['class_name'] ?? 'N/A',
                $student['section_name'] ?? 'N/A',
                $student['father_name'],
                $student['mother_name'],
                $student['dob'],
                $student['gender'],
                $student['current_address'],
                $student['is_active']
            ]);
        }
        
        fclose($output);
        exit;
    }

    /**
     * Export to PDF
     */
    private function exportToPDF($students)
    {
        // For now, return a simple HTML that can be printed as PDF
        $data = [
            'title' => 'Students Report',
            'students' => $students,
            'generated_at' => date('Y-m-d H:i:s')
        ];
        
        return view('student/pdf_export', $data);
    }

    /**
     * Bulk import students
     */
    public function import()
    {
        $data = [
            'title' => 'Import Students - Smart School',
            'classes' => $this->classModel->getAllClassesWithSections(),
            'sessions' => $this->sessionModel->findAll()
        ];

        return view('student/import', $data);
    }

    /**
     * Process bulk import
     */
    public function processImport()
    {
        $file = $this->request->getFile('import_file');
        
        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'Please select a valid CSV file');
        }

        try {
            $handle = fopen($file->getTempName(), 'r');
            $header = fgetcsv($handle); // Skip header row
            
            $imported = 0;
            $errors = [];
            
            while (($data = fgetcsv($handle)) !== false) {
                try {
                    // Map CSV data to student fields
                    $studentData = [
                        'admission_no' => $data[0],
                        'firstname' => $data[1],
                        'lastname' => $data[2],
                        'email' => $data[3],
                        'mobileno' => $data[4],
                        'dob' => $data[5],
                        'gender' => $data[6],
                        'father_name' => $data[7],
                        'mother_name' => $data[8],
                        'is_active' => 'yes'
                    ];
                    
                    $this->studentModel->insert($studentData);
                    $imported++;
                    
                } catch (\Exception $e) {
                    $errors[] = "Row {$imported}: " . $e->getMessage();
                }
            }
            
            fclose($handle);
            
            $message = "Successfully imported {$imported} students.";
            if (!empty($errors)) {
                $message .= " Errors: " . implode(', ', array_slice($errors, 0, 5));
            }
            
            return redirect()->to('/students')->with('success', $message);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Generate student ID card
     */
    public function idCard($id)
    {
        $student = $this->studentModel->getStudentById($id);
        
        if (!$student) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Student not found');
        }

        $data = [
            'title' => 'Student ID Card',
            'student' => $student
        ];

        return view('student/id_card', $data);
    }
}