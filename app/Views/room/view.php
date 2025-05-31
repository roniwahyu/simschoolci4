<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
    <div>
        <a href="<?= base_url('room/edit/' . $room['id']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-edit fa-sm text-white-50"></i> Edit Room
        </a>
        <a href="<?= base_url('room') ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm ml-2">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Rooms
        </a>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Room Details</h6>
                <span class="badge <?= $room['active'] ? 'bg-success' : 'bg-danger' ?>">
                    <?= $room['active'] ? 'Active' : 'Inactive' ?>
                </span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Room Number</th>
                            <td><?= esc($room['room_no']) ?></td>
                        </tr>
                        <tr>
                            <th>Room Title</th>
                            <td><?= esc($room['title']) ?></td>
                        </tr>
                        <tr>
                            <th>Hostel</th>
                            <td><?= esc($room['hostel_name']) ?></td>
                        </tr>
                        <tr>
                            <th>Room Type</th>
                            <td><?= esc($room['room_type']) ?></td>
                        </tr>
                        <tr>
                            <th>Number of Beds</th>
                            <td><?= esc($room['no_of_bed']) ?></td>
                        </tr>
                        <tr>
                            <th>Cost per Bed</th>
                            <td>$<?= number_format($room['cost_per_bed'], 2) ?></td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td><?= nl2br(esc($room['description'])) ?></td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td><?= date('F j, Y, g:i a', strtotime($room['created_at'])) ?></td>
                        </tr>
                        <tr>
                            <th>Last Updated</th>
                            <td><?= date('F j, Y, g:i a', strtotime($room['updated_at'])) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Room Status Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Room Status</h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <i class="fas <?= $room['active'] ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' ?> fa-4x mb-3"></i>
                    <h5><?= $room['active'] ? 'Room is Active' : 'Room is Inactive' ?></h5>
                    <p class="mb-0">
                        <?= $room['active'] 
                            ? 'This room is currently available for assignment.' 
                            : 'This room is currently not available for assignment.' 
                        ?>
                    </p>
                </div>
                <hr>
                <div class="text-center">
                    <form action="<?= base_url('room/toggleStatus/' . $room['id']) ?>" method="post" class="d-inline">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" value="PUT">
                        <button type="submit" class="btn btn-<?= $room['active'] ? 'warning' : 'success' ?> btn-sm">
                            <i class="fas <?= $room['active'] ? 'fa-ban' : 'fa-check' ?> fa-sm"></i>
                            <?= $room['active'] ? 'Mark as Inactive' : 'Mark as Active' ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Room Cost Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Room Cost Summary</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <div class="text-center">
                        <h2 class="text-primary">$<?= number_format($room['cost_per_bed'] * $room['no_of_bed'], 2) ?></h2>
                        <p class="mb-0">Total Room Value</p>
                    </div>
                </div>
                <hr>
                <div class="mt-4 text-center small">
                    <div class="mb-1">
                        <i class="fas fa-circle text-primary"></i> Cost per Bed: $<?= number_format($room['cost_per_bed'], 2) ?>
                    </div>
                    <div class="mb-1">
                        <i class="fas fa-circle text-success"></i> Number of Beds: <?= $room['no_of_bed'] ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>