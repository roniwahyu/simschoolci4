<?php

namespace App\Models;

use CodeIgniter\Model;

class HostelModel extends Model
{
    protected $table      = 'hostel';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'hostel_name', 
        'type', 
        'address', 
        'intake', 
        'description', 
        'is_active'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'hostel_name'  => 'required|max_length[100]',
        'type'         => 'required|max_length[50]',
        'intake'       => 'permit_empty|numeric',
        'is_active'    => 'permit_empty|in_list[yes,no]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;

    /**
     * Get all active hostels
     *
     * @return array
     */
    public function getActiveHostels()
    {
        return $this->where('is_active', 'yes')->findAll();
    }

    /**
     * Get all hostels
     *
     * @return array
     */
    public function getAllHostels()
    {
        return $this->findAll();
    }

    /**
     * Get hostel by ID
     *
     * @param int $id
     * @return array|null
     */
    public function getHostelById($id)
    {
        return $this->find($id);
    }

    /**
     * Get hostels for dropdown
     *
     * @param bool $activeOnly Whether to include only active hostels
     * @return array
     */
    public function getHostelsDropdown($activeOnly = true)
    {
        $builder = $this->builder();
        
        if ($activeOnly) {
            $builder->where('is_active', 'yes');
        }
        
        $hostels = $builder->get()->getResultArray();
        $dropdown = [];
        
        foreach ($hostels as $hostel) {
            $dropdown[$hostel['id']] = $hostel['hostel_name'];
        }
        
        return $dropdown;
    }

    /**
     * Get hostel with room count
     *
     * @return array
     */
    public function getHostelsWithRoomCount()
    {
        $builder = $this->db->table('hostel h');
        $builder->select('h.*, COUNT(hr.id) as room_count');
        $builder->join('hostel_rooms hr', 'hr.hostel_id = h.id', 'left');
        $builder->groupBy('h.id');
        
        return $builder->get()->getResultArray();
    }
}