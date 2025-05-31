<?php

namespace App\Models;

use CodeIgniter\Model;

class RoomTypeModel extends Model
{
    protected $table      = 'room_types';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'room_type', 
        'description'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'room_type'    => 'required|max_length[200]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;

    /**
     * Get all active room types
     *
     * @return array
     */
    public function getActiveRoomTypes()
    {
        return $this->findAll();
    }

    /**
     * Get room type by ID
     *
     * @param int $id
     * @return array|null
     */
    public function getRoomTypeById($id)
    {
        return $this->find($id);
    }

    /**
     * Get room types for dropdown
     *
     * @return array
     */
    public function getRoomTypesDropdown()
    {
        $roomTypes = $this->findAll();
        $dropdown = [];
        
        foreach ($roomTypes as $type) {
            $dropdown[$type['id']] = $type['room_type'];
        }
        
        return $dropdown;
    }
}