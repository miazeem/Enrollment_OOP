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

// Get all students and courses
$students = $student->getAll();
$courses = $course->getActive();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $data = [
        'student_id' => $_POST['student_id'],
        'course_id' => $_POST['course_id'],
        'grade' => $_POST['grade'],
        'status' => $_POST['status']
    ];

    // Try to create enrollment
    if ($enrollment->create($data)) {
        // Success - redirect to enrollments page
        header('Location: enrollments.php?success=added');
        exit;
    } else {
        $error = "Failed to add enrollment. Student may already be enrolled in this course.";
    }
}
?>

<div class="max-w-2xl mx-auto mt-8 p-8">
    <div class="bg-white rounded-lg shadow-lg">
        <div class="bg-blue-600 text-white px-6 py-4 rounded-t-lg">
            <h4 class="text-2xl"><i class="bi bi-clipboard-check"></i> Add New Enrollment</h4>
        </div>
        <div class="p-6">
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <!-- Enrollment Form -->
            <form method="POST" action="">
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Select Student *</label>
                    <select name="student_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                        <option value="">-- Choose Student --</option>
                        <?php foreach ($students as $s): ?>
                            <?php if ($s['status'] == 'active'): ?>
                                <option value="<?php echo $s['id']; ?>">
                                    <?php echo htmlspecialchars($s['first_name'] . ' ' . $s['last_name'] . ' (' . $s['email'] . ')'); ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Select Course *</label>
                    <select name="course_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                        <option value="">-- Choose Course --</option>
                        <?php foreach ($courses as $c): ?>
                            <option value="<?php echo $c['id']; ?>">
                                <?php echo htmlspecialchars($c['course_code'] . ' - ' . $c['course_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Grade (Optional)</label>
                    <input type="text" name="grade" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                           placeholder="e.g., A, B+, C">
                    <small class="text-gray-500">Leave empty if not graded yet</small>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Status *</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                        <option value="enrolled">Enrolled</option>
                        <option value="completed">Completed</option>
                        <option value="dropped">Dropped</option>
                    </select>
                </div>

                <div class="flex justify-between">
                    <a href="enrollments.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                        <i class="bi bi-save"></i> Save Enrollment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
