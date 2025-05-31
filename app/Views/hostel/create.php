<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
    <a href="<?= base_url('hostel') ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Hostels
    </a>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Add New Hostel</h6>
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
                
                <form action="<?= base_url('hostel/create') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="hostel_name">Hostel Name</label>
                                <input type="text" class="form-control" id="hostel_name" name="hostel_name" value="<?= old('hostel_name') ?>" required>
                                <small class="form-text text-muted">Enter a unique name for the hostel</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type">Hostel Type</label>
                                <select name="type" id="type" class="form-control select2" required>
                                    <option value="">Select Type</option>
                                    <option value="Boys" <?= old('type') == 'Boys' ? 'selected' : '' ?>>Boys</option>
                                    <option value="Girls" <?= old('type') == 'Girls' ? 'selected' : '' ?>>Girls</option>
                                    <option value="Combined" <?= old('type') == 'Combined' ? 'selected' : '' ?>>Combined</option>
                                    <option value="Other" <?= old('type') == 'Other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="address">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3"><?= old('address') ?></textarea>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="intake">Intake Capacity</label>
                                <input type="number" class="form-control" id="intake" name="intake" value="<?= old('intake') ?? 0 ?>" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"><?= old('description') ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" <?= old('is_active') == '1' ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="is_active">Active</label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Save Hostel</button>
                    <a href="<?= base_url('hostel') ?>" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>