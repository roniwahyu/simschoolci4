<?php

namespace App\Models;

use CodeIgniter\Model;

class FeesModel extends Model
{
    protected $table = 'fee_receipt';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'student_id',
        'fee_groups_feetype_id',
        'amount_detail',
        'amount',
        'amount_discount',
        'amount_fine',
        'description',
        'date',
        'payment_mode',
        'received_by',
        'created_at',
        'updated_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'student_id' => 'required|integer',
        'fee_groups_feetype_id' => 'required|integer',
        'amount' => 'required|decimal|greater_than[0]',
        'date' => 'required|valid_date',
        'payment_mode' => 'required|in_list[cash,cheque,dd,bank_transfer,card,online]'
    ];
    
    protected $validationMessages = [
        'student_id' => [
            'required' => 'Student is required',
            'integer' => 'Invalid student selection'
        ],
        'fee_groups_feetype_id' => [
            'required' => 'Fee type is required',
            'integer' => 'Invalid fee type selection'
        ],
        'amount' => [
            'required' => 'Amount is required',
            'decimal' => 'Amount must be a valid number',
            'greater_than' => 'Amount must be greater than 0'
        ],
        'date' => [
            'required' => 'Payment date is required',
            'valid_date' => 'Invalid date format'
        ],
        'payment_mode' => [
            'required' => 'Payment mode is required',
            'in_list' => 'Invalid payment mode'
        ]
    ];
    
    /**
     * Get all fee receipts with student and fee type details
     */
    public function getAllFeesWithDetails()
    {
        return $this->select('fee_receipt.*, 
                            students.firstname, students.lastname, students.admission_no,
                            fee_groups_feetype.amount as fee_amount,
                            feetype.name as fee_type_name,
                            feegroup.name as fee_group_name')
                    ->join('students', 'fee_receipt.student_id = students.id')
                    ->join('fee_groups_feetype', 'fee_receipt.fee_groups_feetype_id = fee_groups_feetype.id')
                    ->join('feetype', 'fee_groups_feetype.feetype_id = feetype.id')
                    ->join('feegroup', 'fee_groups_feetype.fee_groups_id = feegroup.id')
                    ->orderBy('fee_receipt.date', 'DESC')
                    ->findAll();
    }
    
    /**
     * Get fee receipts by student ID
     */
    public function getFeesByStudent($studentId)
    {
        return $this->select('fee_receipt.*, 
                            fee_groups_feetype.amount as fee_amount,
                            feetype.name as fee_type_name,
                            feegroup.name as fee_group_name')
                    ->join('fee_groups_feetype', 'fee_receipt.fee_groups_feetype_id = fee_groups_feetype.id')
                    ->join('feetype', 'fee_groups_feetype.feetype_id = feetype.id')
                    ->join('feegroup', 'fee_groups_feetype.fee_groups_id = feegroup.id')
                    ->where('fee_receipt.student_id', $studentId)
                    ->orderBy('fee_receipt.date', 'DESC')
                    ->findAll();
    }
    
    /**
     * Get recent fee payments
     */
    public function getRecentPayments($limit = 10)
    {
        return $this->select('fee_receipt.*, 
                            students.firstname, students.lastname, students.admission_no,
                            feetype.name as fee_type_name')
                    ->join('students', 'fee_receipt.student_id = students.id')
                    ->join('fee_groups_feetype', 'fee_receipt.fee_groups_feetype_id = fee_groups_feetype.id')
                    ->join('feetype', 'fee_groups_feetype.feetype_id = feetype.id')
                    ->orderBy('fee_receipt.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
    
    /**
     * Get fee collection statistics
     */
    public function getFeeStats()
    {
        $stats = [
            'total_collection' => $this->selectSum('amount')->first()['amount'] ?? 0,
            'monthly_collection' => $this->getMonthlyCollection(),
            'daily_collection' => $this->getDailyCollection(),
            'yearly_collection' => $this->getYearlyCollection(),
            'payment_modes' => $this->getPaymentModeStats()
        ];
        
        return $stats;
    }
    
    /**
     * Get monthly fee collection
     */
    public function getMonthlyCollection($month = null, $year = null)
    {
        $month = $month ?? date('m');
        $year = $year ?? date('Y');
        
        return $this->selectSum('amount')
                    ->where('MONTH(date)', $month)
                    ->where('YEAR(date)', $year)
                    ->first()['amount'] ?? 0;
    }
    
    /**
     * Get daily fee collection
     */
    public function getDailyCollection($date = null)
    {
        $date = $date ?? date('Y-m-d');
        
        return $this->selectSum('amount')
                    ->where('date', $date)
                    ->first()['amount'] ?? 0;
    }
    
    /**
     * Get yearly fee collection
     */
    public function getYearlyCollection($year = null)
    {
        $year = $year ?? date('Y');
        
        return $this->selectSum('amount')
                    ->where('YEAR(date)', $year)
                    ->first()['amount'] ?? 0;
    }
    
    /**
     * Get payment mode statistics
     */
    public function getPaymentModeStats()
    {
        return $this->select('payment_mode, COUNT(*) as count, SUM(amount) as total_amount')
                    ->groupBy('payment_mode')
                    ->orderBy('total_amount', 'DESC')
                    ->findAll();
    }
    
    /**
     * Get fee collection trend (monthly)
     */
    public function getFeeCollectionTrend($year = null)
    {
        $year = $year ?? date('Y');
        
        return $this->select('MONTH(date) as month, MONTHNAME(date) as month_name, 
                            SUM(amount) as total_amount, COUNT(*) as total_receipts')
                    ->where('YEAR(date)', $year)
                    ->groupBy('MONTH(date)')
                    ->orderBy('MONTH(date)', 'ASC')
                    ->findAll();
    }
    
    /**
     * Get fee defaulters
     */
    public function getFeeDefaulters()
    {
        $db = \Config\Database::connect();
        
        // This is a complex query to find students who haven't paid their fees
        // For now, returning a simplified version
        return $db->query("
            SELECT DISTINCT s.id, s.firstname, s.lastname, s.admission_no,
                   c.class, c.section,
                   SUM(fgf.amount) as total_due,
                   COALESCE(SUM(fr.amount), 0) as total_paid,
                   (SUM(fgf.amount) - COALESCE(SUM(fr.amount), 0)) as balance
            FROM students s
            JOIN student_session ss ON s.id = ss.student_id
            JOIN classes c ON ss.class_id = c.id
            JOIN fee_session_groups fsg ON c.id = fsg.class_id
            JOIN fee_groups_feetype fgf ON fsg.fee_groups_id = fgf.fee_groups_id
            LEFT JOIN fee_receipt fr ON s.id = fr.student_id AND fgf.id = fr.fee_groups_feetype_id
            WHERE s.is_active = 'yes'
            GROUP BY s.id
            HAVING balance > 0
            ORDER BY balance DESC
        ")->getResultArray();
    }
    
    /**
     * Get student fee summary
     */
    public function getStudentFeeSummary($studentId)
    {
        $db = \Config\Database::connect();
        
        return $db->query("
            SELECT 
                fgf.id as fee_groups_feetype_id,
                ft.name as fee_type,
                fg.name as fee_group,
                fgf.amount as total_amount,
                COALESCE(SUM(fr.amount), 0) as paid_amount,
                (fgf.amount - COALESCE(SUM(fr.amount), 0)) as balance,
                CASE 
                    WHEN (fgf.amount - COALESCE(SUM(fr.amount), 0)) <= 0 THEN 'Paid'
                    ELSE 'Pending'
                END as status
            FROM fee_groups_feetype fgf
            JOIN feetype ft ON fgf.feetype_id = ft.id
            JOIN feegroup fg ON fgf.fee_groups_id = fg.id
            JOIN fee_session_groups fsg ON fg.id = fsg.fee_groups_id
            JOIN student_session ss ON fsg.class_id = ss.class_id
            LEFT JOIN fee_receipt fr ON ss.student_id = fr.student_id AND fgf.id = fr.fee_groups_feetype_id
            WHERE ss.student_id = ?
            GROUP BY fgf.id
            ORDER BY ft.name
        ", [$studentId])->getResultArray();
    }
    
    /**
     * Search fee receipts
     */
    public function searchFees($keyword)
    {
        return $this->select('fee_receipt.*, 
                            students.firstname, students.lastname, students.admission_no,
                            feetype.name as fee_type_name')
                    ->join('students', 'fee_receipt.student_id = students.id')
                    ->join('fee_groups_feetype', 'fee_receipt.fee_groups_feetype_id = fee_groups_feetype.id')
                    ->join('feetype', 'fee_groups_feetype.feetype_id = feetype.id')
                    ->groupStart()
                        ->like('students.firstname', $keyword)
                        ->orLike('students.lastname', $keyword)
                        ->orLike('students.admission_no', $keyword)
                        ->orLike('feetype.name', $keyword)
                        ->orLike('fee_receipt.description', $keyword)
                    ->groupEnd()
                    ->orderBy('fee_receipt.date', 'DESC')
                    ->findAll();
    }
    
    /**
     * Get fee receipt with full details
     */
    public function getFeeReceiptDetails($id)
    {
        return $this->select('fee_receipt.*, 
                            students.firstname, students.lastname, students.admission_no,
                            students.father_name, students.mother_name,
                            fee_groups_feetype.amount as fee_amount,
                            feetype.name as fee_type_name,
                            feegroup.name as fee_group_name,
                            classes.class, classes.section')
                    ->join('students', 'fee_receipt.student_id = students.id')
                    ->join('student_session', 'students.id = student_session.student_id')
                    ->join('classes', 'student_session.class_id = classes.id')
                    ->join('fee_groups_feetype', 'fee_receipt.fee_groups_feetype_id = fee_groups_feetype.id')
                    ->join('feetype', 'fee_groups_feetype.feetype_id = feetype.id')
                    ->join('feegroup', 'fee_groups_feetype.fee_groups_id = feegroup.id')
                    ->where('fee_receipt.id', $id)
                    ->first();
    }
    
    /**
     * Get class-wise fee collection
     */
    public function getClassWiseFeeCollection()
    {
        $db = \Config\Database::connect();
        
        return $db->table('fee_receipt fr')
                  ->select('c.class, c.section, 
                           COUNT(*) as total_receipts,
                           SUM(fr.amount) as total_collection')
                  ->join('students s', 'fr.student_id = s.id')
                  ->join('student_session ss', 's.id = ss.student_id')
                  ->join('classes c', 'ss.class_id = c.id')
                  ->groupBy('c.id')
                  ->orderBy('c.class', 'ASC')
                  ->get()
                  ->getResultArray();
    }
    
    /**
     * Get fee type wise collection
     */
    public function getFeeTypeWiseCollection()
    {
        return $this->select('feetype.name as fee_type, 
                            COUNT(*) as total_receipts,
                            SUM(fee_receipt.amount) as total_collection')
                    ->join('fee_groups_feetype', 'fee_receipt.fee_groups_feetype_id = fee_groups_feetype.id')
                    ->join('feetype', 'fee_groups_feetype.feetype_id = feetype.id')
                    ->groupBy('feetype.id')
                    ->orderBy('total_collection', 'DESC')
                    ->findAll();
    }
    
    /**
     * Generate receipt number
     */
    public function generateReceiptNumber()
    {
        $year = date('Y');
        $month = date('m');
        
        $lastReceipt = $this->select('id')
                           ->where('YEAR(created_at)', $year)
                           ->where('MONTH(created_at)', $month)
                           ->orderBy('id', 'DESC')
                           ->first();
        
        $sequence = $lastReceipt ? ($lastReceipt['id'] % 10000) + 1 : 1;
        
        return 'FR' . $year . $month . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Get pending fee amount for a student
     */
    public function getPendingFeeAmount($studentId)
    {
        $summary = $this->getStudentFeeSummary($studentId);
        $totalPending = 0;
        
        foreach ($summary as $fee) {
            if ($fee['balance'] > 0) {
                $totalPending += $fee['balance'];
            }
        }
        
        return $totalPending;
    }
    
    /**
     * Check if student has pending fees
     */
    public function hasPendingFees($studentId)
    {
        return $this->getPendingFeeAmount($studentId) > 0;
    }
}