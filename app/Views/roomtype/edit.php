<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
    <a href="<?= base_url('roomtype') ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Room Types
    </a>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Edit Room Type</h6>
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
                
                <form action="<?= base_url('roomtype/update/' . $roomType['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="PUT">
                    
                    <div class="form-group mb-3">
                        <label for="room_type">Room Type Name</label>
                        <input type="text" class="form-control" id="room_type" name="room_type" value="<?= old('room_type') ?? $roomType['room_type'] ?>" required>
                        <small class="form-text text-muted">Enter a descriptive name for the room type (e.g., Single Room, Double Room, Deluxe)</small>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?= old('description') ?? $roomType['description'] ?></textarea>
                        <small class="form-text text-muted">Provide details about this room type, including amenities and features</small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Update Room Type</button>
                    <a href="<?= base_url('roomtype') ?>" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>