<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url() ?>">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-school"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Smart School</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?= uri_string() == '' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url() ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Hostel Management
    </div>

    <!-- Nav Item - Hostels -->
    <li class="nav-item <?= strpos(uri_string(), 'hostel') === 0 ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('hostel') ?>">
            <i class="fas fa-fw fa-building"></i>
            <span>Hostels</span>
        </a>
    </li>

    <!-- Nav Item - Rooms -->
    <li class="nav-item <?= strpos(uri_string(), 'room') === 0 && strpos(uri_string(), 'roomtype') !== 0 ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('room') ?>">
            <i class="fas fa-fw fa-door-open"></i>
            <span>Rooms</span>
        </a>
    </li>

    <!-- Nav Item - Room Types -->
    <li class="nav-item <?= strpos(uri_string(), 'roomtype') === 0 ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('roomtype') ?>">
            <i class="fas fa-fw fa-th-large"></i>
            <span>Room Types</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        School Management
    </div>

    <!-- Nav Item - Students Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseStudents"
            aria-expanded="true" aria-controls="collapseStudents">
            <i class="fas fa-fw fa-user-graduate"></i>
            <span>Students</span>
        </a>
        <div id="collapseStudents" class="collapse" aria-labelledby="headingStudents" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Student Management:</h6>
                <a class="collapse-item" href="#">Student List</a>
                <a class="collapse-item" href="#">Add Student</a>
                <a class="collapse-item" href="#">Student Categories</a>
                <a class="collapse-item" href="#">Student Houses</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Classes Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseClasses"
            aria-expanded="true" aria-controls="collapseClasses">
            <i class="fas fa-fw fa-chalkboard"></i>
            <span>Classes</span>
        </a>
        <div id="collapseClasses" class="collapse" aria-labelledby="headingClasses" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Class Management:</h6>
                <a class="collapse-item" href="#">Class List</a>
                <a class="collapse-item" href="#">Add Class</a>
                <a class="collapse-item" href="#">Sections</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Staff Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseStaff"
            aria-expanded="true" aria-controls="collapseStaff">
            <i class="fas fa-fw fa-user-tie"></i>
            <span>Staff</span>
        </a>
        <div id="collapseStaff" class="collapse" aria-labelledby="headingStaff" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Staff Management:</h6>
                <a class="collapse-item" href="#">Staff List</a>
                <a class="collapse-item" href="#">Add Staff</a>
                <a class="collapse-item" href="#">Staff Attendance</a>
                <a class="collapse-item" href="#">Staff Roles</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->