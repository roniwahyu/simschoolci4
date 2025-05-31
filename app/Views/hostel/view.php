<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
    <div>
        <a href="<?= base_url('hostel/edit/' . $hostel['id']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-edit fa-sm text-white-50"></i> Edit Hostel
        </a>
        <a href="<?= base_url('hostel') ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm ml-2">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Hostels
        </a>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Hostel Details</h6>
                <span class="badge <?= $hostel['is_active'] ? 'bg-success' : 'bg-danger' ?>">
                    <?= $hostel['is_active'] ? 'Active' : 'Inactive' ?>
                </span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Hostel Name</th>
                            <td><?= esc($hostel['hostel_name']) ?></td>
                        </tr>
                        <tr>
                            <th>Type</th>
                            <td><?= esc($hostel['type']) ?></td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td><?= nl2br(esc($hostel['address'])) ?></td>
                        </tr>
                        <tr>
                            <th>Intake Capacity</th>
                            <td><?= esc($hostel['intake']) ?></td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td><?= nl2br(esc($hostel['description'])) ?></td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td><?= date('F j, Y, g:i a', strtotime($hostel['created_at'])) ?></td>
                        </tr>
                        <tr>
                            <th>Last Updated</th>
                            <td><?= date('F j, Y, g:i a', strtotime($hostel['updated_at'])) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Hostel Status Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Hostel Status</h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <i class="fas <?= $hostel['is_active'] ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' ?> fa-4x mb-3"></i>
                    <h5><?= $hostel['is_active'] ? 'Hostel is Active' : 'Hostel is Inactive' ?></h5>
                    <p class="mb-0">
                        <?= $hostel['is_active'] 
                            ? 'This hostel is currently available for room assignments.' 
                            : 'This hostel is currently not available for room assignments.' 
                        ?>
                    </p>
                </div>
                <hr>
                <div class="text-center">
                    <form action="<?= base_url('hostel/toggleStatus/' . $hostel['id']) ?>" method="post" class="d-inline">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" value="PUT">
                        <button type="submit" class="btn btn-<?= $hostel['is_active'] ? 'warning' : 'success' ?> btn-sm">
                            <i class="fas <?= $hostel['is_active'] ? 'fa-ban' : 'fa-check' ?> fa-sm"></i>
                            <?= $hostel['is_active'] ? 'Mark as Inactive' : 'Mark as Active' ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Room Statistics Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Room Statistics</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <div class="text-center">
                        <h2 class="text-primary"><?= $roomCount ?></h2>
                        <p class="mb-0">Total Rooms</p>
                    </div>
                </div>
                <hr>
                <div class="mt-4 text-center small">
                    <a href="<?= base_url('room?hostel_id=' . $hostel['id']) ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-door-open fa-sm"></i> View Rooms
                    </a>
                    <a href="<?= base_url('room/new?hostel_id=' . $hostel['id']) ?>" class="btn btn-success btn-sm ml-2">
                        <i class="fas fa-plus fa-sm"></i> Add Room
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>