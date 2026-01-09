<?php
// Include required files
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Student.php';

// Create student object
$student = new Student();

// Get student ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Try to delete student
if ($id > 0) {
    if ($student->delete($id)) {
        // Success
        header('Location: students.php?success=deleted');
    } else {
        // Failed
        header('Location: students.php?error=Failed to delete student');
    }
} else {
    header('Location: students.php?error=Invalid student ID');
}
exit;
?>
