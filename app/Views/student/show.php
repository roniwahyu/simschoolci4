<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Student Profile</h2>
            <p class="text-muted">View detailed student information</p>
        </div>
        <div>
            <a href="<?= base_url('students') ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Students
            </a>
            <a href="<?= base_url('students/' . $student['id'] . '/edit') ?>" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Edit Student
            </a>
            <div class="btn-group">
                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-download me-2"></i>Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?= base_url('students/' . $student['id'] . '/id-card') ?>">
                        <i class="fas fa-id-card me-2"></i>ID Card
                    </a></li>
                    <li><a class="dropdown-item" href="<?= base_url('students/' . $student['id'] . '/pdf') ?>">
                        <i class="fas fa-file-pdf me-2"></i>PDF Profile
                    </a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Personal Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Personal Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Admission Number</label>
                            <p class="form-control-plaintext"><?= esc($student['admission_no']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Roll Number</label>
                            <p class="form-control-plaintext"><?= esc($student['roll_no']) ?: 'Not assigned' ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Full Name</label>
                            <p class="form-control-plaintext">
                                <?= esc($student['firstname']) ?>
                                <?= !empty($student['middlename']) ? esc($student['middlename']) . ' ' : '' ?>
                                <?= esc($student['lastname']) ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Date of Birth</label>
                            <p class="form-control-plaintext">
                                <?= date('F d, Y', strtotime($student['dob'])) ?>
                                <small class="text-muted">(<?= date_diff(date_create($student['dob']), date_create('today'))->y ?> years old)</small>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Gender</label>
                            <p class="form-control-plaintext"><?= esc($student['gender']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Blood Group</label>
                            <p class="form-control-plaintext"><?= esc($student['blood_group']) ?: 'Not specified' ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Religion</label>
                            <p class="form-control-plaintext"><?= esc($student['religion']) ?: 'Not specified' ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Caste</label>
                            <p class="form-control-plaintext"><?= esc($student['cast']) ?: 'Not specified' ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Aadhar Number</label>
                            <p class="form-control-plaintext"><?= esc($student['adhar_no']) ?: 'Not provided' ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-phone me-2"></i>Contact Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Mobile Number</label>
                            <p class="form-control-plaintext">
                                <a href="tel:<?= esc($student['mobileno']) ?>" class="text-decoration-none">
                                    <i class="fas fa-phone me-1"></i><?= esc($student['mobileno']) ?>
                                </a>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email Address</label>
                            <p class="form-control-plaintext">
                                <?php if (!empty($student['email'])): ?>
                                    <a href="mailto:<?= esc($student['email']) ?>" class="text-decoration-none">
                                        <i class="fas fa-envelope me-1"></i><?= esc($student['email']) ?>
                                    </a>
                                <?php else: ?>
                                    Not provided
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">State</label>
                            <p class="form-control-plaintext"><?= esc($student['state']) ?: 'Not specified' ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">City</label>
                            <p class="form-control-plaintext"><?= esc($student['city']) ?: 'Not specified' ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Pin Code</label>
                            <p class="form-control-plaintext"><?= esc($student['pincode']) ?: 'Not specified' ?></p>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Current Address</label>
                            <p class="form-control-plaintext"><?= esc($student['current_address']) ?: 'Not provided' ?></p>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Permanent Address</label>
                            <p class="form-control-plaintext"><?= esc($student['permanent_address']) ?: 'Not provided' ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Parent/Guardian Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Parent/Guardian Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Father's Information -->
                        <div class="col-12">
                            <h6 class="text-primary mb-3"><i class="fas fa-male me-2"></i>Father's Information</h6>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Name</label>
                            <p class="form-control-plaintext"><?= esc($student['father_name']) ?: 'Not provided' ?></p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Phone</label>
                            <p class="form-control-plaintext">
                                <?php if (!empty($student['father_phone'])): ?>
                                    <a href="tel:<?= esc($student['father_phone']) ?>" class="text-decoration-none">
                                        <i class="fas fa-phone me-1"></i><?= esc($student['father_phone']) ?>
                                    </a>
                                <?php else: ?>
                                    Not provided
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Occupation</label>
                            <p class="form-control-plaintext"><?= esc($student['father_occupation']) ?: 'Not specified' ?></p>
                        </div>
                        
                        <!-- Mother's Information -->
                        <div class="col-12">
                            <h6 class="text-primary mb-3 mt-3"><i class="fas fa-female me-2"></i>Mother's Information</h6>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Name</label>
                            <p class="form-control-plaintext"><?= esc($student['mother_name']) ?: 'Not provided' ?></p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Phone</label>
                            <p class="form-control-plaintext">
                                <?php if (!empty($student['mother_phone'])): ?>
                                    <a href="tel:<?= esc($student['mother_phone']) ?>" class="text-decoration-none">
                                        <i class="fas fa-phone me-1"></i><?= esc($student['mother_phone']) ?>
                                    </a>
                                <?php else: ?>
                                    Not provided
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Occupation</label>
                            <p class="form-control-plaintext"><?= esc($student['mother_occupation']) ?: 'Not specified' ?></p>
                        </div>
                        
                        <!-- Guardian Information -->
                        <div class="col-12">
                            <h6 class="text-primary mb-3 mt-3"><i class="fas fa-user-shield me-2"></i>Guardian Information</h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Name</label>
                            <p class="form-control-plaintext"><?= esc($student['guardian_name']) ?: 'Not provided' ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Relation</label>
                            <p class="form-control-plaintext"><?= esc($student['guardian_relation']) ?: 'Not specified' ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Phone</label>
                            <p class="form-control-plaintext">
                                <?php if (!empty($student['guardian_phone'])): ?>
                                    <a href="tel:<?= esc($student['guardian_phone']) ?>" class="text-decoration-none">
                                        <i class="fas fa-phone me-1"></i><?= esc($student['guardian_phone']) ?>
                                    </a>
                                <?php else: ?>
                                    Not provided
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Occupation</label>
                            <p class="form-control-plaintext"><?= esc($student['guardian_occupation']) ?: 'Not specified' ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email</label>
                            <p class="form-control-plaintext">
                                <?php if (!empty($student['guardian_email'])): ?>
                                    <a href="mailto:<?= esc($student['guardian_email']) ?>" class="text-decoration-none">
                                        <i class="fas fa-envelope me-1"></i><?= esc($student['guardian_email']) ?>
                                    </a>
                                <?php else: ?>
                                    Not provided
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Address</label>
                            <p class="form-control-plaintext"><?= esc($student['guardian_address']) ?: 'Not provided' ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Additional Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Previous School</label>
                            <p class="form-control-plaintext"><?= esc($student['previous_school']) ?: 'Not specified' ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Admission Date</label>
                            <p class="form-control-plaintext">
                                <?= !empty($student['admission_date']) ? date('F d, Y', strtotime($student['admission_date'])) : 'Not specified' ?>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Height</label>
                            <p class="form-control-plaintext"><?= !empty($student['height']) ? esc($student['height']) . ' cm' : 'Not recorded' ?></p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Weight</label>
                            <p class="form-control-plaintext"><?= !empty($student['weight']) ? esc($student['weight']) . ' kg' : 'Not recorded' ?></p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Measurement Date</label>
                            <p class="form-control-plaintext">
                                <?= !empty($student['measurement_date']) ? date('F d, Y', strtotime($student['measurement_date'])) : 'Not recorded' ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Student Photo & Basic Info -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <?php if (!empty($student['image'])): ?>
                            <img src="<?= base_url('uploads/students/' . $student['image']) ?>" 
                                 class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        <?php else: ?>
                            <div class="bg-light border rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                                 style="width: 150px; height: 150px;">
                                <i class="fas fa-user fa-4x text-muted"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <h4 class="mb-1">
                        <?= esc($student['firstname']) ?>
                        <?= !empty($student['middlename']) ? esc($student['middlename']) . ' ' : '' ?>
                        <?= esc($student['lastname']) ?>
                    </h4>
                    <p class="text-muted mb-2"><?= esc($student['admission_no']) ?></p>
                    <span class="badge <?= $student['is_active'] ? 'bg-success' : 'bg-danger' ?> mb-3">
                        <?= $student['is_active'] ? 'Active' : 'Inactive' ?>
                    </span>
                </div>
            </div>

            <!-- Academic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Academic Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Class</label>
                        <p class="form-control-plaintext"><?= esc($student['class']) ?></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Section</label>
                        <p class="form-control-plaintext"><?= esc($student['section']) ?></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Academic Session</label>
                        <p class="form-control-plaintext"><?= esc($student['session']) ?></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Roll Number</label>
                        <p class="form-control-plaintext"><?= esc($student['roll_no']) ?: 'Not assigned' ?></p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary" onclick="toggleStatus(<?= $student['id'] ?>)">
                            <i class="fas fa-toggle-<?= $student['is_active'] ? 'on' : 'off' ?> me-2"></i>
                            <?= $student['is_active'] ? 'Deactivate' : 'Activate' ?> Student
                        </button>
                        <a href="<?= base_url('students/' . $student['id'] . '/id-card') ?>" class="btn btn-outline-success">
                            <i class="fas fa-id-card me-2"></i>Generate ID Card
                        </a>
                        <button type="button" class="btn btn-outline-info" onclick="sendSMS(<?= $student['id'] ?>)">
                            <i class="fas fa-sms me-2"></i>Send SMS
                        </button>
                        <button type="button" class="btn btn-outline-warning" onclick="sendEmail(<?= $student['id'] ?>)">
                            <i class="fas fa-envelope me-2"></i>Send Email
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="deleteStudent(<?= $student['id'] ?>)">
                            <i class="fas fa-trash me-2"></i>Delete Student
                        </button>
                    </div>
                </div>
            </div>

            <!-- System Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info me-2"></i>System Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">Created:</small><br>
                        <span><?= date('F d, Y g:i A', strtotime($student['created_at'])) ?></span>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Last Updated:</small><br>
                        <span><?= date('F d, Y g:i A', strtotime($student['updated_at'])) ?></span>
                    </div>
                    <div>
                        <small class="text-muted">Student ID:</small><br>
                        <span class="font-monospace"><?= $student['id'] ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function toggleStatus(studentId) {
    const currentStatus = <?= $student['is_active'] ? 'true' : 'false' ?>;
    const action = currentStatus ? 'deactivate' : 'activate';
    
    Swal.fire({
        title: `${action.charAt(0).toUpperCase() + action.slice(1)} Student?`,
        text: `Are you sure you want to ${action} this student?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: currentStatus ? '#d33' : '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: `Yes, ${action}!`
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `<?= base_url('students') ?>/${studentId}/toggle-status`,
                type: 'POST',
                data: {
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('An error occurred while updating student status');
                }
            });
        }
    });
}

function deleteStudent(studentId) {
    Swal.fire({
        title: 'Delete Student?',
        text: 'This action cannot be undone. All student data will be permanently deleted.',
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete!',
        input: 'text',
        inputPlaceholder: 'Type "DELETE" to confirm',
        inputValidator: (value) => {
            if (value !== 'DELETE') {
                return 'Please type "DELETE" to confirm deletion';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `<?= base_url('students') ?>/${studentId}`,
                type: 'DELETE',
                data: {
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(() => {
                            window.location.href = '<?= base_url('students') ?>';
                        }, 1000);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('An error occurred while deleting the student');
                }
            });
        }
    });
}

function sendSMS(studentId) {
    Swal.fire({
        title: 'Send SMS',
        html: `
            <div class="text-start">
                <label class="form-label">Message:</label>
                <textarea id="smsMessage" class="form-control" rows="4" placeholder="Enter your message..."></textarea>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Send SMS',
        preConfirm: () => {
            const message = document.getElementById('smsMessage').value;
            if (!message.trim()) {
                Swal.showValidationMessage('Please enter a message');
                return false;
            }
            return message;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // In a real application, you would send the SMS here
            toastr.info('SMS functionality would be implemented here');
        }
    });
}

function sendEmail(studentId) {
    Swal.fire({
        title: 'Send Email',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label">Subject:</label>
                    <input type="text" id="emailSubject" class="form-control" placeholder="Enter email subject...">
                </div>
                <div class="mb-3">
                    <label class="form-label">Message:</label>
                    <textarea id="emailMessage" class="form-control" rows="4" placeholder="Enter your message..."></textarea>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Send Email',
        preConfirm: () => {
            const subject = document.getElementById('emailSubject').value;
            const message = document.getElementById('emailMessage').value;
            if (!subject.trim() || !message.trim()) {
                Swal.showValidationMessage('Please enter both subject and message');
                return false;
            }
            return { subject, message };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // In a real application, you would send the email here
            toastr.info('Email functionality would be implemented here');
        }
    });
}
</script>
<?= $this->endSection() ?>