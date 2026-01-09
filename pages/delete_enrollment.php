<?php
// Include required files
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Enrollment.php';

// Create enrollment object
$enrollment = new Enrollment();

// Get enrollment ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Try to delete enrollment
if ($id > 0) {
    if ($enrollment->delete($id)) {
        // Success
        header('Location: enrollments.php?success=deleted');
    } else {
        // Failed
        header('Location: enrollments.php?error=Failed to delete enrollment');
    }
} else {
    header('Location: enrollments.php?error=Invalid enrollment ID');
}
exit;
?>
