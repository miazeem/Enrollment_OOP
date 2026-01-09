<?php
// Include header and required files
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Teacher.php';

// Create objects
$course = new Course();
$teacher = new Teacher();

// Get all active teachers for selection
$teachers = $teacher->getActive();

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

    // Try to create course
    $courseId = $course->create($data);
    
    if ($courseId) {
        // Assign teachers if selected
        $selectedTeachers = isset($_POST['teachers']) ? $_POST['teachers'] : [];
        $course->assignTeachers($courseId, $selectedTeachers);
        
        // Success - redirect to courses page
        header('Location: courses.php?success=added');
        exit;
    } else {
        $error = "Failed to add course. Course code might already exist.";
    }
}
?>

<div class="max-w-2xl mx-auto mt-8 p-8">
    <div class="bg-white rounded-lg shadow-lg">
        <div class="bg-blue-600 text-white px-6 py-4 rounded-t-lg">
            <h4 class="text-2xl"><i class="bi bi-book"></i> Add New Course</h4>
        </div>
        <div class="p-6">
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <!-- Course Form -->
            <form method="POST" action="">
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Course Code *</label>
                    <input type="text" name="course_code" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                           placeholder="e.g., CS101" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Course Name *</label>
                    <input type="text" name="course_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                           placeholder="e.g., Introduction to Programming" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Description</label>
                    <textarea name="description" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" rows="3" 
                              placeholder="Course description"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Credits *</label>
                        <input type="number" name="credits" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                               value="3" min="1" max="6" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Semester</label>
                        <select name="semester" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                            <option value="Fall">Fall</option>
                            <option value="Spring">Spring</option>
                            <option value="Summer">Summer</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Academic Year</label>
                    <input type="text" name="academic_year" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                           placeholder="e.g., 2025-2026" value="2025-2026">
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
                                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
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
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="flex justify-between">
                    <a href="courses.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                        <i class="bi bi-save"></i> Save Course
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
