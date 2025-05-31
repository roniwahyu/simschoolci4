<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Edit Student</h2>
            <p class="text-muted">Update student information and enrollment details</p>
        </div>
        <div>
            <a href="<?= base_url('students') ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Students
            </a>
            <a href="<?= base_url('students/' . $student['id']) ?>" class="btn btn-outline-info">
                <i class="fas fa-eye me-2"></i>View Profile
            </a>
        </div>
    </div>

    <!-- Student Form -->
    <form action="<?= base_url('students/' . $student['id'] . '/update') ?>" method="post" enctype="multipart/form-data" id="studentForm">
        <?= csrf_field() ?>
        <input type="hidden" name="_method" value="PUT">
        
        <div class="row">
            <!-- Personal Information -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-user me-2"></i>Personal Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Admission Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?= isset($validation) && $validation->hasError('admission_no') ? 'is-invalid' : '' ?>" 
                                       name="admission_no" value="<?= old('admission_no', $student['admission_no']) ?>" required>
                                <?php if (isset($validation) && $validation->hasError('admission_no')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('admission_no') ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Roll Number</label>
                                <input type="text" class="form-control" name="roll_no" value="<?= old('roll_no', $student['roll_no']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?= isset($validation) && $validation->hasError('firstname') ? 'is-invalid' : '' ?>" 
                                       name="firstname" value="<?= old('firstname', $student['firstname']) ?>" required>
                                <?php if (isset($validation) && $validation->hasError('firstname')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('firstname') ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Middle Name</label>
                                <input type="text" class="form-control" name="middlename" value="<?= old('middlename', $student['middlename']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?= isset($validation) && $validation->hasError('lastname') ? 'is-invalid' : '' ?>" 
                                       name="lastname" value="<?= old('lastname', $student['lastname']) ?>" required>
                                <?php if (isset($validation) && $validation->hasError('lastname')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('lastname') ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" class="form-control <?= isset($validation) && $validation->hasError('dob') ? 'is-invalid' : '' ?>" 
                                       name="dob" value="<?= old('dob', $student['dob']) ?>" required>
                                <?php if (isset($validation) && $validation->hasError('dob')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('dob') ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <select class="form-select <?= isset($validation) && $validation->hasError('gender') ? 'is-invalid' : '' ?>" 
                                        name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male" <?= old('gender', $student['gender']) == 'Male' ? 'selected' : '' ?>>Male</option>
                                    <option value="Female" <?= old('gender', $student['gender']) == 'Female' ? 'selected' : '' ?>>Female</option>
                                    <option value="Other" <?= old('gender', $student['gender']) == 'Other' ? 'selected' : '' ?>>Other</option>
                                </select>
                                <?php if (isset($validation) && $validation->hasError('gender')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('gender') ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Blood Group</label>
                                <select class="form-select" name="blood_group">
                                    <option value="">Select Blood Group</option>
                                    <option value="A+" <?= old('blood_group', $student['blood_group']) == 'A+' ? 'selected' : '' ?>>A+</option>
                                    <option value="A-" <?= old('blood_group', $student['blood_group']) == 'A-' ? 'selected' : '' ?>>A-</option>
                                    <option value="B+" <?= old('blood_group', $student['blood_group']) == 'B+' ? 'selected' : '' ?>>B+</option>
                                    <option value="B-" <?= old('blood_group', $student['blood_group']) == 'B-' ? 'selected' : '' ?>>B-</option>
                                    <option value="AB+" <?= old('blood_group', $student['blood_group']) == 'AB+' ? 'selected' : '' ?>>AB+</option>
                                    <option value="AB-" <?= old('blood_group', $student['blood_group']) == 'AB-' ? 'selected' : '' ?>>AB-</option>
                                    <option value="O+" <?= old('blood_group', $student['blood_group']) == 'O+' ? 'selected' : '' ?>>O+</option>
                                    <option value="O-" <?= old('blood_group', $student['blood_group']) == 'O-' ? 'selected' : '' ?>>O-</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Religion</label>
                                <input type="text" class="form-control" name="religion" value="<?= old('religion', $student['religion']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Caste</label>
                                <input type="text" class="form-control" name="cast" value="<?= old('cast', $student['cast']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Aadhar Number</label>
                                <input type="text" class="form-control" name="adhar_no" value="<?= old('adhar_no', $student['adhar_no']) ?>" maxlength="12">
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
                                <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control <?= isset($validation) && $validation->hasError('mobileno') ? 'is-invalid' : '' ?>" 
                                       name="mobileno" value="<?= old('mobileno', $student['mobileno']) ?>" required>
                                <?php if (isset($validation) && $validation->hasError('mobileno')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('mobileno') ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>" 
                                       name="email" value="<?= old('email', $student['email']) ?>">
                                <?php if (isset($validation) && $validation->hasError('email')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('email') ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">State</label>
                                <input type="text" class="form-control" name="state" value="<?= old('state', $student['state']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">City</label>
                                <input type="text" class="form-control" name="city" value="<?= old('city', $student['city']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Pin Code</label>
                                <input type="text" class="form-control" name="pincode" value="<?= old('pincode', $student['pincode']) ?>" maxlength="6">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Current Address</label>
                                <textarea class="form-control" name="current_address" rows="3"><?= old('current_address', $student['current_address']) ?></textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Permanent Address</label>
                                <textarea class="form-control" name="permanent_address" rows="3"><?= old('permanent_address', $student['permanent_address']) ?></textarea>
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
                            <div class="col-md-6">
                                <label class="form-label">Father's Name</label>
                                <input type="text" class="form-control" name="father_name" value="<?= old('father_name', $student['father_name']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Father's Phone</label>
                                <input type="tel" class="form-control" name="father_phone" value="<?= old('father_phone', $student['father_phone']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Father's Occupation</label>
                                <input type="text" class="form-control" name="father_occupation" value="<?= old('father_occupation', $student['father_occupation']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mother's Name</label>
                                <input type="text" class="form-control" name="mother_name" value="<?= old('mother_name', $student['mother_name']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mother's Phone</label>
                                <input type="tel" class="form-control" name="mother_phone" value="<?= old('mother_phone', $student['mother_phone']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mother's Occupation</label>
                                <input type="text" class="form-control" name="mother_occupation" value="<?= old('mother_occupation', $student['mother_occupation']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Guardian Name</label>
                                <input type="text" class="form-control" name="guardian_name" value="<?= old('guardian_name', $student['guardian_name']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Guardian Relation</label>
                                <input type="text" class="form-control" name="guardian_relation" value="<?= old('guardian_relation', $student['guardian_relation']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Guardian Phone</label>
                                <input type="tel" class="form-control" name="guardian_phone" value="<?= old('guardian_phone', $student['guardian_phone']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Guardian Occupation</label>
                                <input type="text" class="form-control" name="guardian_occupation" value="<?= old('guardian_occupation', $student['guardian_occupation']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Guardian Email</label>
                                <input type="email" class="form-control" name="guardian_email" value="<?= old('guardian_email', $student['guardian_email']) ?>">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Guardian Address</label>
                                <textarea class="form-control" name="guardian_address" rows="3"><?= old('guardian_address', $student['guardian_address']) ?></textarea>
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
                                <label class="form-label">Previous School</label>
                                <input type="text" class="form-control" name="previous_school" value="<?= old('previous_school', $student['previous_school']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Admission Date</label>
                                <input type="date" class="form-control" name="admission_date" value="<?= old('admission_date', $student['admission_date']) ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Height (cm)</label>
                                <input type="number" class="form-control" name="height" value="<?= old('height', $student['height']) ?>" step="0.1">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Weight (kg)</label>
                                <input type="number" class="form-control" name="weight" value="<?= old('weight', $student['weight']) ?>" step="0.1">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Measurement Date</label>
                                <input type="date" class="form-control" name="measurement_date" value="<?= old('measurement_date', $student['measurement_date']) ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Student Photo -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-camera me-2"></i>Student Photo</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <div id="imagePreview" class="mb-3">
                                <?php if (!empty($student['image'])): ?>
                                    <img src="<?= base_url('uploads/students/' . $student['image']) ?>" 
                                         class="img-fluid rounded" style="max-width: 200px; max-height: 200px;">
                                <?php else: ?>
                                    <div class="bg-light border rounded d-flex align-items-center justify-content-center" style="width: 200px; height: 200px; margin: 0 auto;">
                                        <i class="fas fa-user fa-4x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <input type="file" class="form-control <?= isset($validation) && $validation->hasError('image') ? 'is-invalid' : '' ?>" 
                                   name="image" id="imageInput" accept="image/*">
                            <?php if (isset($validation) && $validation->hasError('image')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('image') ?></div>
                            <?php endif; ?>
                            <small class="text-muted">Max size: 2MB. Formats: JPG, PNG, GIF</small>
                        </div>
                    </div>
                </div>

                <!-- Academic Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Academic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Class <span class="text-danger">*</span></label>
                            <select class="form-select <?= isset($validation) && $validation->hasError('class_id') ? 'is-invalid' : '' ?>" 
                                    name="class_id" id="classSelect" required>
                                <option value="">Select Class</option>
                                <?php foreach ($classes as $class): ?>
                                    <option value="<?= $class['id'] ?>" <?= old('class_id', $student['class_id']) == $class['id'] ? 'selected' : '' ?>>
                                        <?= esc($class['class']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($validation) && $validation->hasError('class_id')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('class_id') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Section <span class="text-danger">*</span></label>
                            <select class="form-select <?= isset($validation) && $validation->hasError('section_id') ? 'is-invalid' : '' ?>" 
                                    name="section_id" id="sectionSelect" required>
                                <option value="">Select Section</option>
                                <?php foreach ($sections as $section): ?>
                                    <option value="<?= $section['id'] ?>" <?= old('section_id', $student['section_id']) == $section['id'] ? 'selected' : '' ?>>
                                        <?= esc($section['section']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($validation) && $validation->hasError('section_id')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('section_id') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Academic Session <span class="text-danger">*</span></label>
                            <select class="form-select <?= isset($validation) && $validation->hasError('session_id') ? 'is-invalid' : '' ?>" 
                                    name="session_id" required>
                                <option value="">Select Session</option>
                                <?php foreach ($sessions as $session): ?>
                                    <option value="<?= $session['id'] ?>" <?= old('session_id', $student['session_id']) == $session['id'] ? 'selected' : '' ?>>
                                        <?= esc($session['session']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($validation) && $validation->hasError('session_id')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('session_id') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="is_active">
                                <option value="1" <?= old('is_active', $student['is_active']) == '1' ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?= old('is_active', $student['is_active']) == '0' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Update Student
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                                <i class="fas fa-undo me-2"></i>Reset Changes
                            </button>
                            <a href="<?= base_url('students/' . $student['id']) ?>" class="btn btn-outline-info">
                                <i class="fas fa-eye me-2"></i>View Profile
                            </a>
                            <a href="<?= base_url('students') ?>" class="btn btn-outline-danger">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Store original form data
    const originalFormData = new FormData(document.getElementById('studentForm'));
    
    // Image preview
    $('#imageInput').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').html(`<img src="${e.target.result}" class="img-fluid rounded" style="max-width: 200px; max-height: 200px;">`);
            };
            reader.readAsDataURL(file);
        }
    });

    // Load sections when class is selected
    $('#classSelect').on('change', function() {
        const classId = $(this).val();
        const sectionSelect = $('#sectionSelect');
        const currentSectionId = '<?= $student['section_id'] ?>';
        
        sectionSelect.html('<option value="">Loading...</option>');
        
        if (classId) {
            // In a real application, you would make an AJAX call to get sections
            // For now, we'll use demo data
            const sections = {
                '1': [{'id': 1, 'section': 'A'}, {'id': 2, 'section': 'B'}],
                '2': [{'id': 3, 'section': 'A'}, {'id': 4, 'section': 'B'}, {'id': 5, 'section': 'C'}],
                '3': [{'id': 6, 'section': 'A'}, {'id': 7, 'section': 'B'}]
            };
            
            sectionSelect.html('<option value="">Select Section</option>');
            
            if (sections[classId]) {
                sections[classId].forEach(function(section) {
                    const selected = section.id == currentSectionId ? 'selected' : '';
                    sectionSelect.append(`<option value="${section.id}" ${selected}>${section.section}</option>`);
                });
            }
        } else {
            sectionSelect.html('<option value="">Select Section</option>');
        }
    });

    // Form validation
    $('#studentForm').on('submit', function(e) {
        let isValid = true;
        const requiredFields = ['admission_no', 'firstname', 'lastname', 'dob', 'gender', 'mobileno', 'class_id', 'section_id', 'session_id'];
        
        requiredFields.forEach(function(field) {
            const input = $(`[name="${field}"]`);
            if (!input.val().trim()) {
                input.addClass('is-invalid');
                isValid = false;
            } else {
                input.removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            toastr.error('Please fill in all required fields');
        }
    });

    // Remove validation errors on input
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
});

function resetForm() {
    Swal.fire({
        title: 'Reset Changes?',
        text: 'This will revert all changes to the original values.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, reset it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Reset form to original values
            location.reload();
        }
    });
}
</script>
<?= $this->endSection() ?>