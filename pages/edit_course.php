<?php
// Include header and required files
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Teacher.php';

// Create objects
$course = new Course();
$teacher = new Teacher();

// Get course ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get course data
$courseData = $course->getById($id);

// If course not found, redirect
if (!$courseData) {
    header('Location: courses.php?error=Course not found');
    exit;
}

// Get all active teachers and currently assigned teachers
$teachers = $teacher->getActive();
$assignedTeacherIds = $course->getTeacherIds($id);

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $data = [
        'course_code' => $_POST['course_code'],
        'course_name' => $_POST['course_name'],
        'description' => $_POST['description'],
        'credits' => $_POST['credits'],
        'semester' => $_POST['semester'],
        'academic_year' => $_POST['academic_year'],
        'status' => $_POST['status']
    ];

    // Try to update course
    if ($course->update($id, $data)) {
        // Update teacher assignments
        $selectedTeachers = isset($_POST['teachers']) ? $_POST['teachers'] : [];
        $course->assignTeachers($id, $selectedTeachers);
        
        // Success - redirect to courses page
        header('Location: courses.php?success=updated');
        exit;
    } else {
        $error = "Failed to update course.";
    }
}
?>

<div class="max-w-2xl mx-auto mt-8 p-8">
    <div class="bg-white rounded-lg shadow-lg">
        <div class="bg-yellow-500 text-white px-6 py-4 rounded-t-lg">
            <h4 class="text-2xl"><i class="bi bi-pencil"></i> Edit Course</h4>
        </div>
        <div class="p-6">
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <!-- Course Edit Form -->
            <form method="POST" action="">
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Course Code *</label>
                    <input type="text" name="course_code" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                           value="<?php echo htmlspecialchars($courseData['course_code']); ?>" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Course Name *</label>
                    <input type="text" name="course_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                           value="<?php echo htmlspecialchars($courseData['course_name']); ?>" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Description</label>
                    <textarea name="description" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" rows="3"><?php echo htmlspecialchars($courseData['description']); ?></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Credits *</label>
                        <input type="number" name="credits" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                               value="<?php echo $courseData['credits']; ?>" min="1" max="6" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Semester</label>
                        <select name="semester" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                            <option value="Fall" <?php echo $courseData['semester'] == 'Fall' ? 'selected' : ''; ?>>Fall</option>
                            <option value="Spring" <?php echo $courseData['semester'] == 'Spring' ? 'selected' : ''; ?>>Spring</option>
                            <option value="Summer" <?php echo $courseData['semester'] == 'Summer' ? 'selected' : ''; ?>>Summer</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Academic Year</label>
                    <input type="text" name="academic_year" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                           value="<?php echo htmlspecialchars($courseData['academic_year']); ?>">
                </div>

                <!-- Multiple Teacher Selection -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="bi bi-info-circle"></i> Assign Teachers (Optional - select one or more)
                    </label>
                    <div class="border border-gray-300 p-4 rounded-lg max-h-48 overflow-y-auto bg-gray-50">
                        <?php if (empty($teachers)): ?>
                            <p class="text-gray-500">No teachers available</p>
                        <?php else: ?>
                            <?php foreach ($teachers as $t): ?>
                                <div class="mb-3">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               name="teachers[]" value="<?php echo $t['id']; ?>"
                                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                                               <?php echo in_array($t['id'], $assignedTeacherIds) ? 'checked' : ''; ?>>
                                        <span class="ml-3 text-gray-700">
                                            <?php echo htmlspecialchars($t['first_name'] . ' ' . $t['last_name']); ?>
                                            <?php if ($t['specialization']): ?>
                                                <small class="text-gray-500">(<?php echo htmlspecialchars($t['specialization']); ?>)</small>
                                            <?php endif; ?>
                                        </span>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <small class="text-gray-500">You can select multiple teachers or leave empty</small>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Status *</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                        <option value="active" <?php echo $courseData['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo $courseData['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>

                <div class="flex justify-between">
                    <a href="courses.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition">
                        <i class="bi bi-save"></i> Update Course
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
