<?php

namespace App\Models;

use CodeIgniter\Model;

class RoomModel extends Model
{
    protected $table      = 'hostel_rooms';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'hostel_id', 
        'room_type_id', 
        'room_no', 
        'no_of_bed', 
        'cost_per_bed', 
        'title', 
        'description'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'hostel_id'     => 'required|numeric',
        'room_type_id'  => 'required|numeric',
        'room_no'       => 'required|max_length[200]',
        'no_of_bed'     => 'required|numeric',
        'cost_per_bed'  => 'required|numeric',
        'title'         => 'required|max_length[200]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;

    /**
     * Get all rooms with related data
     *
     * @return array
     */
    public function getAllRooms()
    {
        return $this->select('hostel_rooms.*, hostel.hostel_name, room_types.room_type')
            ->join('hostel', 'hostel.id = hostel_rooms.hostel_id')
            ->join('room_types', 'room_types.id = hostel_rooms.room_type_id')
            ->findAll();
    }

    /**
     * Get room by ID with related data
     *
     * @param int $id
     * @return array|null
     */
    public function getRoomById($id)
    {
        return $this->select('hostel_rooms.*, hostel.hostel_name, room_types.room_type')
            ->join('hostel', 'hostel.id = hostel_rooms.hostel_id')
            ->join('room_types', 'room_types.id = hostel_rooms.room_type_id')
            ->where('hostel_rooms.id', $id)
            ->first();
    }

    /**
     * Get rooms by hostel ID
     *
     * @param int $hostelId
     * @return array
     */
    public function getRoomsByHostel($hostelId)
    {
        return $this->where('hostel_id', $hostelId)->findAll();
    }

    /**
     * Get rooms by room type ID
     *
     * @param int $roomTypeId
     * @return array
     */
    public function getRoomsByType($roomTypeId)
    {
        return $this->where('room_type_id', $roomTypeId)->findAll();
    }

    /**
     * Get available rooms (with available beds)
     *
     * @return array
     */
    public function getAvailableRooms()
    {
        // This is a simplified example. In a real application, you would need to
        // check against room allocations or bookings to determine availability
        return $this->where('no_of_bed >', 0)->findAll();
    }
}