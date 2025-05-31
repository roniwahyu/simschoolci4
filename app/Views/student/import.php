<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Import Students</h2>
            <p class="text-muted">Bulk import students from Excel/CSV file</p>
        </div>
        <div>
            <a href="<?= base_url('students') ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Students
            </a>
            <a href="<?= base_url('students/import/template') ?>" class="btn btn-success">
                <i class="fas fa-download me-2"></i>Download Template
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Import Form -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-upload me-2"></i>Upload Student Data</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('students/import') ?>" method="post" enctype="multipart/form-data" id="importForm">
                        <?= csrf_field() ?>
                        
                        <div class="mb-4">
                            <label class="form-label">Select File <span class="text-danger">*</span></label>
                            <input type="file" class="form-control <?= isset($validation) && $validation->hasError('import_file') ? 'is-invalid' : '' ?>" 
                                   name="import_file" id="importFile" accept=".xlsx,.xls,.csv" required>
                            <?php if (isset($validation) && $validation->hasError('import_file')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('import_file') ?></div>
                            <?php endif; ?>
                            <div class="form-text">Supported formats: Excel (.xlsx, .xls) and CSV (.csv). Maximum file size: 10MB</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Import Options</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="skip_duplicates" id="skipDuplicates" value="1" checked>
                                <label class="form-check-label" for="skipDuplicates">
                                    Skip duplicate admission numbers
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="update_existing" id="updateExisting" value="1">
                                <label class="form-check-label" for="updateExisting">
                                    Update existing students if admission number matches
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="validate_only" id="validateOnly" value="1">
                                <label class="form-check-label" for="validateOnly">
                                    Validate data only (don't import)
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Default Academic Session <span class="text-danger">*</span></label>
                            <select class="form-select" name="default_session_id" required>
                                <option value="">Select Session</option>
                                <?php if (isset($sessions)): ?>
                                    <?php foreach ($sessions as $session): ?>
                                        <option value="<?= $session['id'] ?>"><?= esc($session['session']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <div class="form-text">This session will be used for students where session is not specified in the file</div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                                <i class="fas fa-undo me-2"></i>Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-2"></i>Import Students
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Import Results -->
            <?php if (isset($importResults)): ?>
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Import Results</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h3 text-primary"><?= $importResults['total_rows'] ?></div>
                                <div class="text-muted">Total Rows</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h3 text-success"><?= $importResults['successful'] ?></div>
                                <div class="text-muted">Successful</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h3 text-warning"><?= $importResults['skipped'] ?></div>
                                <div class="text-muted">Skipped</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h3 text-danger"><?= $importResults['failed'] ?></div>
                                <div class="text-muted">Failed</div>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($importResults['errors'])): ?>
                    <div class="alert alert-danger">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Import Errors:</h6>
                        <ul class="mb-0">
                            <?php foreach ($importResults['errors'] as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($importResults['warnings'])): ?>
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-circle me-2"></i>Warnings:</h6>
                        <ul class="mb-0">
                            <?php foreach ($importResults['warnings'] as $warning): ?>
                                <li><?= esc($warning) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <!-- Import Instructions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Import Instructions</h5>
                </div>
                <div class="card-body">
                    <ol class="mb-0">
                        <li class="mb-2">Download the import template using the button above</li>
                        <li class="mb-2">Fill in the student data in the template</li>
                        <li class="mb-2">Save the file in Excel (.xlsx) or CSV format</li>
                        <li class="mb-2">Upload the file using the form</li>
                        <li class="mb-2">Review the import results</li>
                    </ol>
                </div>
            </div>

            <!-- Required Fields -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-asterisk me-2"></i>Required Fields</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-1"><i class="fas fa-check text-success me-2"></i>Admission Number</li>
                        <li class="mb-1"><i class="fas fa-check text-success me-2"></i>First Name</li>
                        <li class="mb-1"><i class="fas fa-check text-success me-2"></i>Last Name</li>
                        <li class="mb-1"><i class="fas fa-check text-success me-2"></i>Date of Birth</li>
                        <li class="mb-1"><i class="fas fa-check text-success me-2"></i>Gender</li>
                        <li class="mb-1"><i class="fas fa-check text-success me-2"></i>Mobile Number</li>
                        <li class="mb-1"><i class="fas fa-check text-success me-2"></i>Class</li>
                        <li class="mb-1"><i class="fas fa-check text-success me-2"></i>Section</li>
                    </ul>
                </div>
            </div>

            <!-- Data Format Guidelines -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-format-list-bulleted me-2"></i>Data Format Guidelines</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <strong>Date of Birth:</strong><br>
                            <small class="text-muted">Format: YYYY-MM-DD (e.g., 2010-05-15)</small>
                        </li>
                        <li class="mb-2">
                            <strong>Gender:</strong><br>
                            <small class="text-muted">Male, Female, or Other</small>
                        </li>
                        <li class="mb-2">
                            <strong>Mobile Number:</strong><br>
                            <small class="text-muted">10-digit number without country code</small>
                        </li>
                        <li class="mb-2">
                            <strong>Blood Group:</strong><br>
                            <small class="text-muted">A+, A-, B+, B-, AB+, AB-, O+, O-</small>
                        </li>
                        <li class="mb-2">
                            <strong>Class & Section:</strong><br>
                            <small class="text-muted">Must exist in the system</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // File validation
    $('#importFile').on('change', function() {
        const file = this.files[0];
        if (file) {
            const fileSize = file.size / 1024 / 1024; // Convert to MB
            const allowedTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
                                'application/vnd.ms-excel', 'text/csv'];
            
            if (fileSize > 10) {
                toastr.error('File size must be less than 10MB');
                this.value = '';
                return;
            }
            
            if (!allowedTypes.includes(file.type) && !file.name.toLowerCase().endsWith('.csv')) {
                toastr.error('Please select a valid Excel or CSV file');
                this.value = '';
                return;
            }
            
            toastr.success('File selected successfully');
        }
    });

    // Form submission
    $('#importForm').on('submit', function(e) {
        const fileInput = $('#importFile')[0];
        if (!fileInput.files.length) {
            e.preventDefault();
            toastr.error('Please select a file to import');
            return;
        }

        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Importing...').prop('disabled', true);

        // Reset button state after 30 seconds (in case of timeout)
        setTimeout(() => {
            submitBtn.html(originalText).prop('disabled', false);
        }, 30000);
    });

    // Checkbox logic
    $('#updateExisting').on('change', function() {
        if (this.checked) {
            $('#skipDuplicates').prop('checked', false);
        }
    });

    $('#skipDuplicates').on('change', function() {
        if (this.checked) {
            $('#updateExisting').prop('checked', false);
        }
    });
});

function resetForm() {
    Swal.fire({
        title: 'Reset Form?',
        text: 'This will clear all form data.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, reset it!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('importForm').reset();
            toastr.success('Form has been reset');
        }
    });
}
</script>
<?= $this->endSection() ?>