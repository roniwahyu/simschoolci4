<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
    <a href="<?= base_url('hostel/new') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Add New Hostel
    </a>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Hostel List</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Hostel Actions:</div>
                        <a class="dropdown-item" href="<?= base_url('room') ?>">Manage Rooms</a>
                        <a class="dropdown-item" href="<?= base_url('roomtype') ?>">Manage Room Types</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if (session()->has('message')) : ?>
                    <div class="alert alert-<?= session('alert-class') ?> alert-dismissible fade show" role="alert">
                        <?= session('message') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <div class="table-responsive">
                    <table class="table table-bordered datatable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>Hostel Name</th>
                                <th>Type</th>
                                <th>Address</th>
                                <th>Rooms</th>
                                <th>Status</th>
                                <th width="15%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($hostels)) : ?>
                                <tr>
                                    <td colspan="7" class="text-center">No hostels found</td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($hostels as $index => $hostel) : ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= esc($hostel['hostel_name']) ?></td>
                                        <td><?= esc($hostel['type']) ?></td>
                                        <td><?= esc($hostel['address']) ?></td>
                                        <td><?= $hostel['room_count'] ?? 0 ?></td>
                                        <td>
                                            <span class="badge <?= $hostel['is_active'] ? 'bg-success' : 'bg-danger' ?>">
                                                <?= $hostel['is_active'] ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('hostel/view/' . $hostel['id']) ?>" class="btn btn-info btn-sm" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= base_url('hostel/edit/' . $hostel['id']) ?>" class="btn btn-primary btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= base_url('hostel/delete/' . $hostel['id']) ?>" class="btn btn-danger btn-sm btn-delete" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hostel Statistics Row -->
<div class="row">
    <!-- Total Hostels Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Hostels</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($hostels) ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-building fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Hostels Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Hostels</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php 
                            $activeCount = 0;
                            foreach ($hostels as $hostel) {
                                if ($hostel['is_active']) {
                                    $activeCount++;
                                }
                            }
                            echo $activeCount;
                            ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Rooms Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Rooms</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php 
                            $totalRooms = 0;
                            foreach ($hostels as $hostel) {
                                $totalRooms += ($hostel['room_count'] ?? 0);
                            }
                            echo $totalRooms;
                            ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-door-open fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hostel Types Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Hostel Types</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php 
                            $hostelTypes = [];
                            foreach ($hostels as $hostel) {
                                if (!in_array($hostel['type'], $hostelTypes)) {
                                    $hostelTypes[] = $hostel['type'];
                                }
                            }
                            echo count($hostelTypes);
                            ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-th-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this hostel? This action cannot be undone and will remove all associated data.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDelete" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle delete confirmation
        const deleteButtons = document.querySelectorAll('.btn-delete');
        const confirmDeleteButton = document.getElementById('confirmDelete');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const deleteUrl = this.getAttribute('href');
                confirmDeleteButton.setAttribute('href', deleteUrl);
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                deleteModal.show();
            });
        });
    });
</script>

<?= $this->endSection() ?>