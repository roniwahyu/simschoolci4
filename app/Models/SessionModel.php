<?php

namespace App\Models;

use CodeIgniter\Model;

class SessionModel extends Model
{
    protected $table = 'sessions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'session', 'is_active', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }
    
    /**
     * Get the current active academic session
     * 
     * @return object|null The current active session or null if none found
     */
    public function getCurrentSession()
    {
        return $this->where('is_active', 1)->first('object');
    }
}