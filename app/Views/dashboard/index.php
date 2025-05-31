<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-tachometer-alt text-primary"></i> 
            <?= $pageTitle ?>
            <?php if ($isDemo): ?>
                <span class="badge badge-warning ml-2">Demo Mode</span>
            <?php endif; ?>
        </h1>
        <div class="d-none d-lg-inline-block">
            <span class="text-muted">Welcome back! Here's what's happening at your school today.</span>
        </div>
    </div>

    <!-- Statistics Cards Row -->
    <div class="row">
        <!-- Students Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Students
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($totalStudents) ?></div>
                            <div class="mt-2">
                                <small class="text-success">
                                    <i class="fas fa-check-circle"></i> <?= number_format($activeStudents ?? 0) ?> Active
                                </small>
                                <small class="text-muted ml-2">
                                    <i class="fas fa-pause-circle"></i> <?= number_format($inactiveStudents ?? 0) ?> Inactive
                                </small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teachers Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Teachers
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($totalTeachers) ?></div>
                            <div class="mt-2">
                                <small class="text-success">
                                    <i class="fas fa-check-circle"></i> <?= number_format($activeTeachers ?? 0) ?> Active
                                </small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Classes Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Classes & Sections
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($totalClasses) ?></div>
                            <div class="mt-2">
                                <small class="text-info">
                                    <i class="fas fa-layer-group"></i> <?= number_format($totalSections ?? 0) ?> Sections
                                </small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-school fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subjects Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Subjects
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($totalSubjects ?? 0) ?></div>
                            <div class="mt-2">
                                <small class="text-warning">
                                    <i class="fas fa-book"></i> Academic Curriculum
                                </small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-books fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Statistics Row -->
    <?php if (isset($monthlyStats)): ?>
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                New Admissions (This Month)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($monthlyStats['newAdmissions'] ?? 0) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-plus fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Fee Collection (This Month)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₹<?= number_format($monthlyStats['feeCollection'] ?? 0) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Exams Conducted (This Month)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($monthlyStats['examsConducted'] ?? 0) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Content Row -->
    <div class="row">
        <!-- Recent Students -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users"></i> Recent Students
                    </h6>
                    <a href="<?= base_url('students') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-eye"></i> View All
                    </a>
                </div>
                <div class="card-body">
                    <?php if (!empty($recentStudents)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Student</th>
                                        <th>Class</th>
                                        <th>Admission Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentStudents as $student): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm mr-3">
                                                        <div class="avatar-title bg-primary text-white rounded-circle">
                                                            <?= strtoupper(substr($student['first_name'] ?? $student['firstname'] ?? 'S', 0, 1)) ?>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="font-weight-bold">
                                                            <?= esc($student['first_name'] ?? $student['firstname'] ?? '') ?> 
                                                            <?= esc($student['last_name'] ?? $student['lastname'] ?? '') ?>
                                                        </div>
                                                        <small class="text-muted"><?= esc($student['email'] ?? '') ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">
                                                    <?= esc($student['class_name'] ?? 'N/A') ?>
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= date('M d, Y', strtotime($student['admission_date'] ?? $student['created_at'] ?? 'now')) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge badge-success">Active</span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">No recent students found</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bell"></i> Recent Activities
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($recentActivities)): ?>
                        <?php foreach ($recentActivities as $activity): ?>
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-circle bg-<?= $activity['color'] ?> text-white mr-3">
                                    <i class="<?= $activity['icon'] ?>"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="small text-gray-500"><?= $activity['time'] ?></div>
                                    <div class="font-weight-bold"><?= esc($activity['message']) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-bell fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">No recent activities</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Events -->
    <?php if (!empty($upcomingEvents)): ?>
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-alt"></i> Upcoming Events
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($upcomingEvents as $event): ?>
                            <div class="col-md-4 mb-3">
                                <div class="card border-left-info h-100">
                                    <div class="card-body">
                                        <h6 class="card-title text-info">
                                            <i class="fas fa-calendar"></i> <?= esc($event['title']) ?>
                                        </h6>
                                        <p class="card-text">
                                            <small class="text-muted">
                                                <i class="fas fa-clock"></i> 
                                                <?= date('M d, Y', strtotime($event['start_date'])) ?> 
                                                at <?= $event['time'] ?>
                                            </small>
                                        </p>
                                        <p class="card-text"><?= esc($event['note']) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Fee Statistics -->
    <?php if (isset($feeStats)): ?>
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Fee Collection
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₹<?= number_format($feeStats['totalCollection'] ?? 0) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-rupee-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Monthly Collection
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₹<?= number_format($feeStats['monthlyCollection'] ?? 0) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Amount
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₹<?= number_format($feeStats['pendingAmount'] ?? 0) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Current Session Info -->
    <?php if (isset($currentSession) && $currentSession): ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle"></i>
                <strong>Current Academic Session:</strong> <?= esc($currentSession->name ?? 'Not Set') ?>
                <?php if (isset($currentSession->is_active) && $currentSession->is_active): ?>
                    <span class="badge badge-success ml-2">Active</span>
                <?php endif; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
.icon-circle {
    height: 2.5rem;
    width: 2.5rem;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-sm {
    height: 2.5rem;
    width: 2.5rem;
}

.avatar-title {
    align-items: center;
    display: flex;
    font-size: 1rem;
    font-weight: 600;
    height: 100%;
    justify-content: center;
    width: 100%;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.card {
    transition: all 0.3s;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.1);
}
</style>
<?= $this->endSection() ?>