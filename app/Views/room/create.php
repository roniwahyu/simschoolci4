<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
    <a href="<?= base_url('room') ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Rooms
    </a>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Add New Room</h6>
            </div>
            <div class="card-body">
                <?php if (session()->has('errors')) : ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach (session('errors') as $error) : ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form action="<?= base_url('room/create') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="hostel_id">Hostel</label>
                                <select name="hostel_id" id="hostel_id" class="form-control select2" required>
                                    <option value="">Select Hostel</option>
                                    <?php foreach ($hostels as $hostel) : ?>
                                        <option value="<?= $hostel['id'] ?>" <?= old('hostel_id') == $hostel['id'] ? 'selected' : '' ?>>
                                            <?= esc($hostel['hostel_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="room_type_id">Room Type</label>
                                <select name="room_type_id" id="room_type_id" class="form-control select2" required>
                                    <option value="">Select Room Type</option>
                                    <?php foreach ($roomTypes as $type) : ?>
                                        <option value="<?= $type['id'] ?>" <?= old('room_type_id') == $type['id'] ? 'selected' : '' ?>>
                                            <?= esc($type['room_type']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="room_no">Room Number</label>
                                <input type="text" class="form-control" id="room_no" name="room_no" value="<?= old('room_no') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Room Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?= old('title') ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="no_of_bed">Number of Beds</label>
                                <input type="number" class="form-control" id="no_of_bed" name="no_of_bed" value="<?= old('no_of_bed') ?? 1 ?>" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cost_per_bed">Cost per Bed</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" class="form-control" id="cost_per_bed" name="cost_per_bed" value="<?= old('cost_per_bed') ?? 0 ?>" min="0" step="0.01" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?= old('description') ?></textarea>
                    </div>
                    
                    <div class="form-group mb-3">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="active" name="active" value="1" <?= old('active') == '1' ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="active">Active</label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Save Room</button>
                    <a href="<?= base_url('room') ?>" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>