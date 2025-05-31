<?php

namespace App\Controllers;

use App\Models\HostelModel;
use App\Models\RoomModel;
use CodeIgniter\HTTP\ResponseInterface;

class Hostel extends BaseController
{
    protected $hostelModel;
    protected $roomModel;
    protected $session;
    
    public function __construct()
    {
        $this->hostelModel = new HostelModel();
        $this->roomModel = new RoomModel();
        $this->session = \Config\Services::session();
    }

    /**
     * Display hostel list
     *
     * @return string
     */
    public function index()
    {
        $data = [
            'title' => 'Hostel Management',
            'hostels' => $this->hostelModel->getHostelsWithRoomCount(),
        ];

        return view('hostel/index', $data);
    }

    /**
     * Display hostel creation form
     *
     * @return string
     */
    public function new()
    {
        $data = [
            'title' => 'Add New Hostel',
        ];

        return view('hostel/create', $data);
    }

    /**
     * Create a new hostel
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $rules = [
            'hostel_name' => 'required|max_length[100]',
            'type' => 'required|max_length[50]',
            'intake' => 'permit_empty|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'hostel_name' => $this->request->getPost('hostel_name'),
            'type' => $this->request->getPost('type'),
            'address' => $this->request->getPost('address'),
            'intake' => $this->request->getPost('intake'),
            'description' => $this->request->getPost('description'),
            'is_active' => 'yes',
        ];

        $this->hostelModel->insert($data);
        $this->session->setFlashdata('success', 'Hostel added successfully');

        return redirect()->to('/hostel');
    }

    /**
     * Display hostel edit form
     *
     * @param int $id
     * @return string
     */
    public function edit($id = null)
    {
        if ($id === null) {
            return redirect()->to('/hostel');
        }

        $hostel = $this->hostelModel->find($id);

        if ($hostel === null) {
            $this->session->setFlashdata('error', 'Hostel not found');
            return redirect()->to('/hostel');
        }

        $data = [
            'title' => 'Edit Hostel',
            'hostel' => $hostel,
        ];

        return view('hostel/edit', $data);
    }

    /**
     * Update hostel data
     *
     * @param int $id
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        if ($id === null) {
            return redirect()->to('/hostel');
        }

        $rules = [
            'hostel_name' => 'required|max_length[100]',
            'type' => 'required|max_length[50]',
            'intake' => 'permit_empty|numeric',
            'is_active' => 'permit_empty|in_list[yes,no]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'hostel_name' => $this->request->getPost('hostel_name'),
            'type' => $this->request->getPost('type'),
            'address' => $this->request->getPost('address'),
            'intake' => $this->request->getPost('intake'),
            'description' => $this->request->getPost('description'),
            'is_active' => $this->request->getPost('is_active') ?? 'no',
        ];

        $this->hostelModel->update($id, $data);
        $this->session->setFlashdata('success', 'Hostel updated successfully');

        return redirect()->to('/hostel');
    }

    /**
     * Delete a hostel
     *
     * @param int $id
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        if ($id === null) {
            return redirect()->to('/hostel');
        }

        $hostel = $this->hostelModel->find($id);

        if ($hostel === null) {
            $this->session->setFlashdata('error', 'Hostel not found');
            return redirect()->to('/hostel');
        }

        // Check if hostel has rooms before deleting
        $roomCount = $this->roomModel->where('hostel_id', $id)->countAllResults();

        if ($roomCount > 0) {
            $this->session->setFlashdata('error', 'Cannot delete hostel. It has ' . $roomCount . ' room(s) assigned to it.');
            return redirect()->to('/hostel');
        }

        $this->hostelModel->delete($id);
        $this->session->setFlashdata('success', 'Hostel deleted successfully');

        return redirect()->to('/hostel');
    }

    /**
     * View hostel details with rooms
     *
     * @param int $id
     * @return string
     */
    public function view($id = null)
    {
        if ($id === null) {
            return redirect()->to('/hostel');
        }

        $hostel = $this->hostelModel->find($id);

        if ($hostel === null) {
            $this->session->setFlashdata('error', 'Hostel not found');
            return redirect()->to('/hostel');
        }

        $data = [
            'title' => 'Hostel Details',
            'hostel' => $hostel,
            'rooms' => $this->roomModel->getRoomsByHostel($id),
        ];

        return view('hostel/view', $data);
    }

    /**
     * Toggle hostel active status
     *
     * @param int $id
     * @return ResponseInterface
     */
    public function toggleStatus($id = null)
    {
        if ($id === null) {
            return redirect()->to('/hostel');
        }

        $hostel = $this->hostelModel->find($id);

        if ($hostel === null) {
            $this->session->setFlashdata('error', 'Hostel not found');
            return redirect()->to('/hostel');
        }

        $newStatus = ($hostel['is_active'] === 'yes') ? 'no' : 'yes';
        $this->hostelModel->update($id, ['is_active' => $newStatus]);

        $statusText = ($newStatus === 'yes') ? 'activated' : 'deactivated';
        $this->session->setFlashdata('success', 'Hostel ' . $statusText . ' successfully');

        return redirect()->to('/hostel');
    }
}