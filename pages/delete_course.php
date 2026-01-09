<?php
// Include required files
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Course.php';

// Create course object
$course = new Course();

// Get course ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Try to delete course
if ($id > 0) {
    if ($course->delete($id)) {
        // Success
        header('Location: courses.php?success=deleted');
    } else {
        // Failed
        header('Location: courses.php?error=Failed to delete course');
    }
} else {
    header('Location: courses.php?error=Invalid course ID');
}
exit;
?>
