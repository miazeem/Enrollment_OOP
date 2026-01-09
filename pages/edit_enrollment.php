<?php
// Include header and required files
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../models/Enrollment.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Course.php';

// Create objects
$enrollment = new Enrollment();
$student = new Student();
$course = new Course();

// Get enrollment ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get enrollment data
$enrollmentData = $enrollment->getById($id);

// If enrollment not found, redirect
if (!$enrollmentData) {
    header('Location: enrollments.php?error=Enrollment not found');
    exit;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $data = [
        'grade' => $_POST['grade'],
        'status' => $_POST['status']
    ];

    // Try to update enrollment
    if ($enrollment->update($id, $data)) {
        // Success - redirect to enrollments page
        header('Location: enrollments.php?success=updated');
        exit;
    } else {
        $error = "Failed to update enrollment.";
    }
}
?>

<div class="max-w-2xl mx-auto mt-8 p-8">
    <div class="bg-white rounded-lg shadow-lg">
        <div class="bg-yellow-500 text-white px-6 py-4 rounded-t-lg">
            <h4 class="text-2xl"><i class="bi bi-pencil"></i> Edit Enrollment</h4>
        </div>
        <div class="p-6">
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <!-- Enrollment Display Information -->
            <div class="bg-cyan-100 border-l-4 border-cyan-500 text-cyan-700 p-4 mb-6 rounded">
                <p><strong>Student:</strong> <?php echo htmlspecialchars($enrollmentData['student_first'] . ' ' . $enrollmentData['student_last']); ?></p>
                <p><strong>Course:</strong> <?php echo htmlspecialchars($enrollmentData['course_code'] . ' - ' . $enrollmentData['course_name']); ?></p>
                <p class="mb-0"><strong>Enrollment Date:</strong> <?php echo date('M d, Y', strtotime($enrollmentData['enrollment_date'])); ?></p>
            </div>

            <!-- Enrollment Edit Form -->
            <form method="POST" action="">
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Grade</label>
                    <input type="text" name="grade" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                           value="<?php echo htmlspecialchars($enrollmentData['grade'] ?? ''); ?>"
                           placeholder="e.g., A, B+, C">
                    <small class="text-gray-500">Leave empty if not graded yet</small>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Status *</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                        <option value="enrolled" <?php echo $enrollmentData['status'] == 'enrolled' ? 'selected' : ''; ?>>Enrolled</option>
                        <option value="completed" <?php echo $enrollmentData['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                        <option value="dropped" <?php echo $enrollmentData['status'] == 'dropped' ? 'selected' : ''; ?>>Dropped</option>
                    </select>
                </div>

                <div class="flex justify-between">
                    <a href="enrollments.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition">
                        <i class="bi bi-save"></i> Update Enrollment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
