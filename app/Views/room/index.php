<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
    <a href="<?= base_url('room/new') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Add New Room
    </a>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Room List</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Room Actions:</div>
                        <a class="dropdown-item" href="<?= base_url('roomtype') ?>">Manage Room Types</a>
                        <a class="dropdown-item" href="<?= base_url('hostel') ?>">Manage Hostels</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" id="exportRooms">Export Room Data</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered datatable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Room No</th>
                                <th>Title</th>
                                <th>Hostel</th>
                                <th>Room Type</th>
                                <th>Beds</th>
                                <th>Cost per Bed</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($rooms)) : ?>
                                <tr>
                                    <td colspan="7" class="text-center">No rooms found</td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($rooms as $room) : ?>
                                    <tr>
                                        <td><?= esc($room['room_no']) ?></td>
                                        <td><?= esc($room['title']) ?></td>
                                        <td><?= esc($room['hostel_name']) ?></td>
                                        <td><?= esc($room['room_type']) ?></td>
                                        <td><?= esc($room['no_of_bed']) ?></td>
                                        <td><?= number_format($room['cost_per_bed'], 2) ?></td>
                                        <td>
                                            <a href="<?= base_url('room/view/' . $room['id']) ?>" class="btn btn-info btn-sm" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= base_url('room/edit/' . $room['id']) ?>" class="btn btn-primary btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= base_url('room/delete/' . $room['id']) ?>" class="btn btn-danger btn-sm btn-delete" title="Delete">
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

<!-- Room Statistics Row -->
<div class="row">
    <!-- Total Rooms Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Rooms</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($rooms) ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-door-open fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Beds Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Beds</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php 
                            $totalBeds = 0;
                            foreach ($rooms as $room) {
                                $totalBeds += $room['no_of_bed'];
                            }
                            echo $totalBeds;
                            ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-bed fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Average Cost Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Average Cost per Bed</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php 
                            $totalCost = 0;
                            $roomCount = count($rooms);
                            foreach ($rooms as $room) {
                                $totalCost += $room['cost_per_bed'];
                            }
                            echo $roomCount > 0 ? number_format($totalCost / $roomCount, 2) : '0.00';
                            ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Room Types Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Room Types</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php 
                            $roomTypes = [];
                            foreach ($rooms as $room) {
                                if (!in_array($room['room_type'], $roomTypes)) {
                                    $roomTypes[] = $room['room_type'];
                                }
                            }
                            echo count($roomTypes);
                            ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-th-large fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>