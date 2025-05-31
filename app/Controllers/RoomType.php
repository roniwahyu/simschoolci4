<?php

namespace App\Controllers;

use App\Models\RoomTypeModel;
use CodeIgniter\HTTP\ResponseInterface;

class RoomType extends BaseController
{
    protected $roomTypeModel;
    protected $session;
    
    public function __construct()
    {
        $this->roomTypeModel = new RoomTypeModel();
        $this->session = \Config\Services::session();
    }

    /**
     * Display room type list
     *
     * @return string
     */
    public function index()
    {
        $data = [
            'title' => 'Room Types',
            'roomTypes' => $this->roomTypeModel->getActiveRoomTypes(),
        ];

        return view('roomtype/index', $data);
    }

    /**
     * Display room type creation form
     *
     * @return string
     */
    public function new()
    {
        $data = [
            'title' => 'Add New Room Type',
        ];

        return view('roomtype/create', $data);
    }

    /**
     * Create a new room type
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $rules = [
            'room_type' => 'required|max_length[200]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'room_type' => $this->request->getPost('room_type'),
            'description' => $this->request->getPost('description'),
        ];

        $this->roomTypeModel->insert($data);
        $this->session->setFlashdata('success', 'Room type added successfully');

        return redirect()->to('/roomtype');
    }

    /**
     * Display room type edit form
     *
     * @param int $id
     * @return string
     */
    public function edit($id = null)
    {
        if ($id === null) {
            return redirect()->to('/roomtype');
        }

        $roomType = $this->roomTypeModel->find($id);

        if ($roomType === null) {
            $this->session->setFlashdata('error', 'Room type not found');
            return redirect()->to('/roomtype');
        }

        $data = [
            'title' => 'Edit Room Type',
            'roomType' => $roomType,
        ];

        return view('roomtype/edit', $data);
    }

    /**
     * Update room type data
     *
     * @param int $id
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        if ($id === null) {
            return redirect()->to('/roomtype');
        }

        $rules = [
            'room_type' => 'required|max_length[200]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'room_type' => $this->request->getPost('room_type'),
            'description' => $this->request->getPost('description'),
        ];

        $this->roomTypeModel->update($id, $data);
        $this->session->setFlashdata('success', 'Room type updated successfully');

        return redirect()->to('/roomtype');
    }

    /**
     * Delete a room type
     *
     * @param int $id
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        if ($id === null) {
            return redirect()->to('/roomtype');
        }

        $roomType = $this->roomTypeModel->find($id);

        if ($roomType === null) {
            $this->session->setFlashdata('error', 'Room type not found');
            return redirect()->to('/roomtype');
        }

        // Check if room type is in use before deleting
        $db = \Config\Database::connect();
        $roomCount = $db->table('hostel_rooms')
            ->where('room_type_id', $id)
            ->countAllResults();

        if ($roomCount > 0) {
            $this->session->setFlashdata('error', 'Cannot delete room type. It is being used by ' . $roomCount . ' room(s).');
            return redirect()->to('/roomtype');
        }

        $this->roomTypeModel->delete($id);
        $this->session->setFlashdata('success', 'Room type deleted successfully');

        return redirect()->to('/roomtype');
    }
}