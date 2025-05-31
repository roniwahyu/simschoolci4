<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students Management - Smart School</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Students Management</h1>
        <a href="/students/create" class="btn btn-primary">Add New Student</a>
        <div class="filters">
            <form action="/students/search" method="get">
                <input type="text" name="term" placeholder="Search by name or admission number" value="<?= esc($searchTerm ?? '') ?>">
                <select name="class_id" onchange="this.form.submit()">
                    <option value="">Select Class</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?= esc($class['id']) ?>" <?= isset($classId) && $classId == $class['id'] ? 'selected' : '' ?>>
                            <?= esc($class['class']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <select name="section_id" onchange="this.form.submit()">
                    <option value="">Select Section</option>
                    <?php foreach ($sections as $section): ?>
                        <option value="<?= esc($section['id']) ?>" <?= isset($sectionId) && $sectionId == $section['id'] ? 'selected' : '' ?>>
                            <?= esc($section['section']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Admission No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= esc($student['admission_no']) ?></td>
                        <td><?= esc($student['firstname'] . ' ' . $student['lastname']) ?></td>
                        <td><?= esc($student['email']) ?></td>
                        <td><?= esc($student['mobileno']) ?></td>
                        <td><?= esc($student['class_name']) ?></td>
                        <td><?= esc($student['section_name']) ?></td>
                        <td><?= esc($student['is_active'] === 'yes' ? 'Active' : 'Inactive') ?></td>
                        <td>
                            <a href="/students/show/<?= esc($student['id']) ?>" class="btn btn-info">View</a>
                            <a href="/students/edit/<?= esc($student['id']) ?>" class="btn btn-warning">Edit</a>
                            <a href="#" onclick="toggleStatus(<?= esc($student['id']) ?>)" class="btn btn-secondary">
                                <?= esc($student['is_active'] === 'yes' ? 'Deactivate' : 'Activate') ?>
                            </a>
                            <a href="#" onclick="deleteStudent(<?= esc($student['id']) ?>)" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="stats">
            <p>Total Students: <?= esc($totalStudents) ?></p>
            <p>Active Students: <?= esc($activeStudents) ?></p>
            <p>Inactive Students: <?= esc($inactiveStudents) ?></p>
        </div>
        <div class="actions">
            <a href="/students/export/excel" class="btn btn-success">Export to Excel</a>
            <a href="/students/export/pdf" class="btn btn-success">Export to PDF</a>
            <a href="/students/import" class="btn btn-primary">Import Students</a>
        </div>
    </div>

    <script>
        function toggleStatus(id) {
            if (confirm('Are you sure you want to toggle the status of this student?')) {
                fetch(`/students/toggleStatus/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while toggling the status.');
                });
            }
        }

        function deleteStudent(id) {
            if (confirm('Are you sure you want to delete this student?')) {
                fetch(`/students/delete/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the student.');
                });
            }
        }
    </script>
</body>
</html>
