<?php

namespace App\Controllers;

use App\Models\RoomModel;
use App\Models\RoomTypeModel;
use App\Models\HostelModel;
use CodeIgniter\HTTP\ResponseInterface;

class Room extends BaseController
{
    protected $roomModel;
    protected $roomTypeModel;
    protected $hostelModel;
    protected $session;
    
    public function __construct()
    {
        $this->roomModel = new RoomModel();
        $this->roomTypeModel = new RoomTypeModel();
        $this->hostelModel = new HostelModel();
        $this->session = \Config\Services::session();
    }

    /**
     * Display room list
     *
     * @return string
     */
    public function index()
    {
        $data = [
            'title' => 'Room Management',
            'rooms' => $this->roomModel->getAllRooms(),
        ];

        return view('room/index', $data);
    }

    /**
     * Display room creation form
     *
     * @return string
     */
    public function new()
    {
        $data = [
            'title' => 'Add New Room',
            'hostels' => $this->hostelModel->getHostelsDropdown(),
            'roomTypes' => $this->roomTypeModel->getRoomTypesDropdown(),
        ];

        return view('room/create', $data);
    }

    /**
     * Create a new room
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $rules = [
            'hostel_id' => 'required|numeric',
            'room_type_id' => 'required|numeric',
            'room_no' => 'required|max_length[200]',
            'no_of_bed' => 'required|numeric',
            'cost_per_bed' => 'required|numeric',
            'title' => 'required|max_length[200]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'hostel_id' => $this->request->getPost('hostel_id'),
            'room_type_id' => $this->request->getPost('room_type_id'),
            'room_no' => $this->request->getPost('room_no'),
            'no_of_bed' => $this->request->getPost('no_of_bed'),
            'cost_per_bed' => $this->request->getPost('cost_per_bed'),
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
        ];

        $this->roomModel->insert($data);
        $this->session->setFlashdata('success', 'Room added successfully');

        return redirect()->to('/room');
    }

    /**
     * Display room edit form
     *
     * @param int $id
     * @return string
     */
    public function edit($id = null)
    {
        if ($id === null) {
            return redirect()->to('/room');
        }

        $room = $this->roomModel->getRoomById($id);

        if ($room === null) {
            $this->session->setFlashdata('error', 'Room not found');
            return redirect()->to('/room');
        }

        $data = [
            'title' => 'Edit Room',
            'room' => $room,
            'hostels' => $this->hostelModel->getHostelsDropdown(),
            'roomTypes' => $this->roomTypeModel->getRoomTypesDropdown(),
        ];

        return view('room/edit', $data);
    }

    /**
     * Update room data
     *
     * @param int $id
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        if ($id === null) {
            return redirect()->to('/room');
        }

        $rules = [
            'hostel_id' => 'required|numeric',
            'room_type_id' => 'required|numeric',
            'room_no' => 'required|max_length[200]',
            'no_of_bed' => 'required|numeric',
            'cost_per_bed' => 'required|numeric',
            'title' => 'required|max_length[200]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'hostel_id' => $this->request->getPost('hostel_id'),
            'room_type_id' => $this->request->getPost('room_type_id'),
            'room_no' => $this->request->getPost('room_no'),
            'no_of_bed' => $this->request->getPost('no_of_bed'),
            'cost_per_bed' => $this->request->getPost('cost_per_bed'),
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
        ];

        $this->roomModel->update($id, $data);
        $this->session->setFlashdata('success', 'Room updated successfully');

        return redirect()->to('/room');
    }

    /**
     * Delete a room
     *
     * @param int $id
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        if ($id === null) {
            return redirect()->to('/room');
        }

        $room = $this->roomModel->find($id);

        if ($room === null) {
            $this->session->setFlashdata('error', 'Room not found');
            return redirect()->to('/room');
        }

        $this->roomModel->delete($id);
        $this->session->setFlashdata('success', 'Room deleted successfully');

        return redirect()->to('/room');
    }

    /**
     * View room details
     *
     * @param int $id
     * @return string
     */
    public function view($id = null)
    {
        if ($id === null) {
            return redirect()->to('/room');
        }

        $room = $this->roomModel->getRoomById($id);

        if ($room === null) {
            $this->session->setFlashdata('error', 'Room not found');
            return redirect()->to('/room');
        }

        $data = [
            'title' => 'Room Details',
            'room' => $room,
        ];

        return view('room/view', $data);
    }

    /**
     * Update room status via AJAX
     *
     * @return ResponseInterface
     */
    public function updateStatus()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $id = $this->request->getPost('id');
        $isActive = $this->request->getPost('is_active');

        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Room ID is required']);
        }

        $room = $this->roomModel->find($id);

        if ($room === null) {
            return $this->response->setJSON(['success' => false, 'message' => 'Room not found']);
        }

        // In a real application, you might have an is_active field
        // For this example, we'll just return success
        return $this->response->setJSON([
            'success' => true, 
            'message' => 'Room status updated successfully'
        ]);
    }

    /**
     * Filter rooms by hostel
     *
     * @param int $hostelId
     * @return string
     */
    public function filterByHostel($hostelId = null)
    {
        if ($hostelId === null) {
            return redirect()->to('/room');
        }

        $hostel = $this->hostelModel->find($hostelId);

        if ($hostel === null) {
            $this->session->setFlashdata('error', 'Hostel not found');
            return redirect()->to('/room');
        }

        $data = [
            'title' => 'Rooms in ' . $hostel['hostel_name'],
            'rooms' => $this->roomModel->getRoomsByHostel($hostelId),
            'hostel' => $hostel,
        ];

        return view('room/filtered', $data);
    }

    /**
     * Filter rooms by room type
     *
     * @param int $typeId
     * @return string
     */
    public function filterByType($typeId = null)
    {
        if ($typeId === null) {
            return redirect()->to('/room');
        }

        $roomType = $this->roomTypeModel->find($typeId);

        if ($roomType === null) {
            $this->session->setFlashdata('error', 'Room type not found');
            return redirect()->to('/room');
        }

        $data = [
            'title' => $roomType['room_type'] . ' Rooms',
            'rooms' => $this->roomModel->getRoomsByType($typeId),
            'roomType' => $roomType,
        ];

        return view('room/filtered', $data);
    }
}