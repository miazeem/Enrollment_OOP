<?php
// Include required files
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Teacher.php';

// Create teacher object
$teacher = new Teacher();

// Get teacher ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Try to delete teacher
if ($id > 0) {
    if ($teacher->delete($id)) {
        // Success
        header('Location: teachers.php?success=deleted');
    } else {
        // Failed
        header('Location: teachers.php?error=Failed to delete teacher');
    }
} else {
    header('Location: teachers.php?error=Invalid teacher ID');
}
exit;
?>
