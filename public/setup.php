<?php

// Database setup script to create users table
// Access this file via: http://localhost:8080/setup.php

header('Content-Type: text/html; charset=utf-8');

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'simschool1';
$port = 3306;

echo "<h2>Database Setup Script</h2>";
echo "<pre>";

try {
    // Connect to MySQL
    $pdo = new PDO("mysql:host=$host;port=$port", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ Database '$database' created or already exists.\n";
    
    // Select the database
    $pdo->exec("USE `$database`");
    
    // Disable foreign key checks temporarily
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    echo "✓ Foreign key checks disabled.\n";
    
    // Drop existing users table if it exists
    $pdo->exec("DROP TABLE IF EXISTS `users`");
    echo "✓ Existing users table dropped.\n";
    
    // Re-enable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    echo "✓ Foreign key checks re-enabled.\n";
    
    // Create users table
    $createTableSQL = "
    CREATE TABLE `users` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `username` varchar(50) NOT NULL,
        `email` varchar(100) NOT NULL,
        `password` varchar(255) NOT NULL,
        `first_name` varchar(50) NOT NULL,
        `last_name` varchar(50) NOT NULL,
        `role` enum('admin','teacher','student','parent','staff','principal') NOT NULL DEFAULT 'student',
        `is_active` enum('yes','no') NOT NULL DEFAULT 'no',
        `last_login` datetime DEFAULT NULL,
        `login_ip` varchar(45) DEFAULT NULL,
        `remember_token` varchar(255) DEFAULT NULL,
        `reset_token` varchar(255) DEFAULT NULL,
        `reset_token_expiry` datetime DEFAULT NULL,
        `email_verification_token` varchar(255) DEFAULT NULL,
        `email_verified_at` datetime DEFAULT NULL,
        `two_factor_secret` varchar(255) DEFAULT NULL,
        `two_factor_enabled` enum('yes','no') NOT NULL DEFAULT 'no',
        `google_id` varchar(255) DEFAULT NULL,
        `avatar` varchar(255) DEFAULT NULL,
        `phone` varchar(20) DEFAULT NULL,
        `address` text DEFAULT NULL,
        `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `username` (`username`),
        UNIQUE KEY `email` (`email`),
        KEY `role` (`role`),
        KEY `is_active` (`is_active`),
        KEY `google_id` (`google_id`),
        KEY `remember_token` (`remember_token`),
        KEY `reset_token` (`reset_token`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $pdo->exec($createTableSQL);
    echo "✓ Users table created successfully.\n";
    
    // Insert default users
    $insertSQL = "
    INSERT INTO `users` (`username`, `email`, `password`, `first_name`, `last_name`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
    ('admin', 'admin@school.com', :admin_password, 'System', 'Administrator', 'admin', 'yes', NOW(), NOW()),
    ('teacher1', 'teacher@school.com', :teacher_password, 'John', 'Teacher', 'teacher', 'yes', NOW(), NOW()),
    ('student1', 'student@school.com', :student_password, 'Jane', 'Student', 'student', 'yes', NOW(), NOW())
    ";
    
    $stmt = $pdo->prepare($insertSQL);
    $hashedPassword = password_hash('password', PASSWORD_DEFAULT);
    $stmt->bindParam(':admin_password', $hashedPassword);
    $stmt->bindParam(':teacher_password', $hashedPassword);
    $stmt->bindParam(':student_password', $hashedPassword);
    $stmt->execute();
    
    echo "✓ Default users inserted successfully.\n\n";
    echo "<strong>Default login credentials:</strong>\n";
    echo "- Admin: admin@school.com / password\n";
    echo "- Teacher: teacher@school.com / password\n";
    echo "- Student: student@school.com / password\n\n";
    
    echo "<span style='color: green; font-weight: bold;'>✓ Database setup completed successfully!</span>\n";
    echo "\n<a href='/'>← Go to Login Page</a>\n";
    
} catch (PDOException $e) {
    echo "<span style='color: red; font-weight: bold;'>✗ Error: " . $e->getMessage() . "</span>\n";
    exit(1);
}

echo "</pre>";
?>