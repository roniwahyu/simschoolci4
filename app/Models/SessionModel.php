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
}