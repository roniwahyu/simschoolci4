<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Students Management</h2>
            <p class="text-muted">Manage all student records and information</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="importStudents()">
                <i class="fas fa-upload me-2"></i>Import Students
            </button>
            <div class="dropdown">
                <button class="btn btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-download me-2"></i>Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?= base_url('students/export/excel') ?>"><i class="fas fa-file-excel me-2"></i>Excel</a></li>
                    <li><a class="dropdown-item" href="<?= base_url('students/export/pdf') ?>"><i class="fas fa-file-pdf me-2"></i>PDF</a></li>
                </ul>
            </div>
            <button class="btn btn-primary" onclick="addStudent()">
                <i class="fas fa-user-plus me-2"></i>Add New Student
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-50 small">Total Students</div>
                            <div class="h4 mb-0"><?= number_format($totalStudents ?? 0) ?></div>
                        </div>
                        <div class="text-white-50">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-50 small">Active Students</div>
                            <div class="h4 mb-0"><?= number_format($activeStudents ?? 0) ?></div>
                        </div>
                        <div class="text-white-50">
                            <i class="fas fa-user-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-50 small">Inactive Students</div>
                            <div class="h4 mb-0"><?= number_format($inactiveStudents ?? 0) ?></div>
                        </div>
                        <div class="text-white-50">
                            <i class="fas fa-user-times fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-50 small">Classes</div>
                            <div class="h4 mb-0"><?= count($classes ?? []) ?></div>
                        </div>
                        <div class="text-white-50">
                            <i class="fas fa-chalkboard fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Search Students</label>
                    <input type="text" class="form-control" id="searchInput" placeholder="Search by name, admission no, roll no...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Filter by Class</label>
                    <select class="form-select" id="classFilter">
                        <option value="">All Classes</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?= $class['id'] ?>"><?= esc($class['class']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Filter by Status</label>
                    <select class="form-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="yes">Active</option>
                        <option value="no">Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary" onclick="clearFilters()">
                            <i class="fas fa-times me-1"></i>Clear
                        </button>
                        <button class="btn btn-primary" onclick="applyFilters()">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-user-graduate me-2"></i>Students List</h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary btn-sm" onclick="refreshTable()">
                        <i class="fas fa-sync-alt me-1"></i>Refresh
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="studentsTable">
                    <thead class="table-light">
                        <tr>
                            <th width="60">Photo</th>
                            <th width="120">Admission No</th>
                            <th width="200">Student Name</th>
                            <th width="150">Class/Section</th>
                            <th width="100">Roll No</th>
                            <th width="120">Contact</th>
                            <th width="80">Status</th>
                            <th width="120">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($students)): ?>
                            <?php foreach ($students as $student): ?>
                                <tr data-student-id="<?= $student['id'] ?>">
                                    <td>
                                        <?php if (!empty($student['image'])): ?>
                                            <img src="<?= base_url('uploads/student_images/' . $student['image']) ?>"
                                                 alt="<?= esc($student['firstname']) ?>"
                                                 class="rounded-circle" width="40" height="40">
                                        <?php else: ?>
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 40px; height: 40px;">
                                                <?= strtoupper(substr($student['firstname'], 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="fw-semibold"><?= esc($student['admission_no']) ?></span>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-semibold"><?= esc($student['firstname'] . ' ' . $student['lastname']) ?></div>
                                            <small class="text-muted"><?= esc($student['email']) ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            <?= esc($student['class_name'] ?? 'N/A') ?> - <?= esc($student['section_name'] ?? 'N/A') ?>
                                        </span>
                                    </td>
                                    <td><?= esc($student['roll_no']) ?></td>
                                    <td>
                                        <div><?= esc($student['mobileno']) ?></div>
                                        <small class="text-muted"><?= esc($student['father_phone']) ?></small>
                                    </td>
                                    <td>
                                        <?php if ($student['is_active'] == 'yes'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm" 
                                                    onclick="viewStudent(<?= $student['id'] ?>)" 
                                                    title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-success btn-sm" 
                                                    onclick="editStudent(<?= $student['id'] ?>)" 
                                                    title="Edit Student">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-warning btn-sm" 
                                                    onclick="toggleStatus(<?= $student['id'] ?>)" 
                                                    title="Toggle Status">
                                                <i class="fas fa-toggle-on"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-info btn-sm" 
                                                    onclick="generateIdCard(<?= $student['id'] ?>)" 
                                                    title="Generate ID Card">
                                                <i class="fas fa-id-card"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                                    onclick="deleteStudent(<?= $student['id'] ?>)" 
                                                    title="Delete Student">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-user-graduate fa-3x mb-3"></i>
                                        <p class="mb-0">No students found</p>
                                        <small>Click "Add New Student" to get started</small>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Student Details Modal -->
<div class="modal fade" id="studentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Student Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="studentModalBody">
                <!-- Student details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="printStudentCard()">Print ID Card</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#studentsTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[1, 'asc']], // Sort by admission number
        columnDefs: [
            { orderable: false, targets: [0, 7] }, // Disable sorting for photo and actions columns
            { className: "text-center", targets: [0, 6, 7] }, // Center align photo, status, and actions
            { width: "60px", targets: 0 }, // Photo column width
            { width: "120px", targets: 1 }, // Admission No column width
            { width: "200px", targets: 2 }, // Student Name column width
            { width: "150px", targets: 3 }, // Class/Section column width
            { width: "100px", targets: 4 }, // Roll No column width
            { width: "120px", targets: 5 }, // Contact column width
            { width: "80px", targets: 6 }, // Status column width
            { width: "120px", targets: 7 } // Actions column width
        ],
        language: {
            search: "Search Students:",
            lengthMenu: "Show _MENU_ students per page",
            info: "Showing _START_ to _END_ of _TOTAL_ students",
            emptyTable: "No students found",
            zeroRecords: "No matching students found"
        },
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success btn-sm'
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm'
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-info btn-sm'
            }
        ]
    });

    // Real-time search
    $('#searchInput').on('keyup', function() {
        $('#studentsTable').DataTable().search(this.value).draw();
    });
});

function addStudent() {
    window.location.href = '<?= base_url("students/create") ?>';
}

function viewStudent(studentId) {
    // Show loading
    $('#studentModalBody').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Loading student details...</p></div>');
    $('#studentModal').modal('show');

    // Load student details via AJAX
    $.get(`<?= base_url('students/show/') ?>${studentId}`, function(data) {
        $('#studentModalBody').html(data);
    }).fail(function() {
        $('#studentModalBody').html('<div class="alert alert-danger">Failed to load student details</div>');
    });
}

function editStudent(studentId) {
    window.location.href = `<?= base_url("students/edit/") ?>${studentId}`;
}

function deleteStudent(studentId) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This action will permanently delete the student record!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `<?= base_url('students/delete/') ?>${studentId}`,
                type: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Deleted!', response.message, 'success');
                        location.reload();
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'Failed to delete student', 'error');
                }
            });
        }
    });
}

function toggleStatus(studentId) {
    $.ajax({
        url: `<?= base_url('students/toggleStatus/') ?>${studentId}`,
        type: 'POST',
        success: function(response) {
            if (response.success) {
                toastr.success(response.message);
                location.reload();
            } else {
                toastr.error(response.message);
            }
        },
        error: function() {
            toastr.error('Failed to update student status');
        }
    });
}

function generateIdCard(studentId) {
    window.open(`<?= base_url('students/idCard/') ?>${studentId}`, '_blank');
}

function importStudents() {
    window.location.href = '<?= base_url("students/import") ?>';
}

function applyFilters() {
    const searchTerm = $('#searchInput').val();
    const classId = $('#classFilter').val();
    const status = $('#statusFilter').val();
    
    let url = '<?= base_url("students/search") ?>?';
    const params = [];
    
    if (searchTerm) params.push(`term=${encodeURIComponent(searchTerm)}`);
    if (classId) params.push(`class_id=${classId}`);
    if (status) params.push(`status=${status}`);
    
    if (params.length > 0) {
        url += params.join('&');
        
        $.get(url, function(response) {
            if (response.success) {
                updateTable(response.data);
            }
        });
    }
}

function clearFilters() {
    $('#searchInput').val('');
    $('#classFilter').val('');
    $('#statusFilter').val('');
    $('#studentsTable').DataTable().search('').draw();
}

function refreshTable() {
    location.reload();
}

function updateTable(students) {
    const table = $('#studentsTable').DataTable();
    table.clear();
    
    students.forEach(function(student) {
        const photoHtml = student.image ? 
            `<img src="<?= base_url('uploads/student_images/') ?>${student.image}" class="rounded-circle" width="40" height="40">` :
            `<div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 40px; height: 40px;">${student.firstname.charAt(0).toUpperCase()}</div>`;
        
        const statusBadge = student.is_active === 'yes' ? 
            '<span class="badge bg-success">Active</span>' : 
            '<span class="badge bg-danger">Inactive</span>';
        
        table.row.add([
            photoHtml,
            student.admission_no,
            `<div><div class="fw-semibold">${student.firstname} ${student.lastname}</div><small class="text-muted">${student.email || ''}</small></div>`,
            `<span class="badge bg-primary">${student.class_name || 'N/A'} - ${student.section_name || 'N/A'}</span>`,
            student.roll_no,
            student.father_name,
            `<div>${student.mobileno}</div><small class="text-muted">${student.father_phone || ''}</small>`,
            statusBadge,
            `<div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="viewStudent(${student.id})" title="View Details"><i class="fas fa-eye"></i></button>
                <button type="button" class="btn btn-outline-success btn-sm" onclick="editStudent(${student.id})" title="Edit Student"><i class="fas fa-edit"></i></button>
                <button type="button" class="btn btn-outline-info btn-sm" onclick="generateIdCard(${student.id})" title="ID Card"><i class="fas fa-id-card"></i></button>
                <button type="button" class="btn btn-outline-warning btn-sm" onclick="toggleStatus(${student.id})" title="Toggle Status"><i class="fas fa-toggle-on"></i></button>
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteStudent(${student.id})" title="Delete Student"><i class="fas fa-trash"></i></button>
            </div>`
        ]);
    });
    
    table.draw();
}
</script>
<?= $this->endSection() ?>