<?php
// Include header and required files
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../models/Course.php';

// Create course object
$course = new Course();

// Get all courses from database
$courses = $course->getAll();
?>

<div class="flex">
    <!-- Sidebar Navigation -->
    <div class="w-64 bg-gray-100 min-h-screen">
        <div class="pt-6 px-4">
            <ul class="space-y-2">
                <li>
                    <a href="students.php" class="flex items-center space-x-2 px-4 py-2 text-gray-700 hover:bg-blue-100 rounded-lg transition">
                        <i class="bi bi-people"></i> <span>Students</span>
                    </a>
                </li>
                <li>
                    <a href="teachers.php" class="flex items-center space-x-2 px-4 py-2 text-gray-700 hover:bg-blue-100 rounded-lg transition">
                        <i class="bi bi-person-badge"></i> <span>Teachers</span>
                    </a>
                </li>
                <li>
                    <a href="courses.php" class="flex items-center space-x-2 px-4 py-2 text-gray-700 bg-blue-100 rounded-lg transition">
                        <i class="bi bi-book"></i> <span>Courses</span>
                    </a>
                </li>
                <li>
                    <a href="enrollments.php" class="flex items-center space-x-2 px-4 py-2 text-gray-700 hover:bg-blue-100 rounded-lg transition">
                        <i class="bi bi-clipboard-check"></i> <span>Enrollments</span>
                    </a>
                </li>
                <li>
                    <a href="reports.php" class="flex items-center space-x-2 px-4 py-2 text-gray-700 hover:bg-blue-100 rounded-lg transition">
                        <i class="bi bi-file-earmark-text"></i> <span>Reports</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex-1 p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800"><i class="bi bi-book"></i> Course Management</h2>
            <a href="add_course.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                <i class="bi bi-plus-circle"></i> Add New Course
            </a>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                <?php 
                if ($_GET['success'] == 'added') echo 'Course added successfully!';
                if ($_GET['success'] == 'updated') echo 'Course updated successfully!';
                if ($_GET['success'] == 'deleted') echo 'Course deleted successfully!';
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                Error: <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Courses Table -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse">
                    <thead class="bg-blue-600 text-white">
                        <tr>
                            <th class="border px-6 py-3 text-left">ID</th>
                            <th class="border px-6 py-3 text-left">Code</th>
                            <th class="border px-6 py-3 text-left">Course Name</th>
                            <th class="border px-6 py-3 text-left">Credits</th>
                            <th class="border px-6 py-3 text-left">Semester</th>
                            <th class="border px-6 py-3 text-left">Teachers</th>
                            <th class="border px-6 py-3 text-left">Status</th>
                            <th class="border px-6 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($courses)): ?>
                            <tr>
                                <td colspan="8" class="border px-6 py-3 text-center text-gray-500">No courses found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($courses as $c): ?>
                                <?php
                                // Get teachers for this course
                                $teachers = $course->getTeachers($c['id']);
                                $teacherNames = array_map(function($t) {
                                    return $t['first_name'] . ' ' . $t['last_name'];
                                }, $teachers);
                                ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="border px-6 py-3"><?php echo $c['id']; ?></td>
                                    <td class="border px-6 py-3"><strong><?php echo htmlspecialchars($c['course_code']); ?></strong></td>
                                    <td class="border px-6 py-3"><?php echo htmlspecialchars($c['course_name']); ?></td>
                                    <td class="border px-6 py-3"><?php echo $c['credits']; ?></td>
                                    <td class="border px-6 py-3"><?php echo htmlspecialchars($c['semester']); ?></td>
                                    <td class="border px-6 py-3">
                                        <?php if (empty($teacherNames)): ?>
                                            <span class="inline-block bg-gray-500 text-white px-3 py-1 rounded-full text-sm">No teacher</span>
                                        <?php else: ?>
                                            <?php echo htmlspecialchars(implode(', ', $teacherNames)); ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="border px-6 py-3">
                                        <?php if ($c['status'] == 'active'): ?>
                                            <span class="inline-block bg-green-500 text-white px-3 py-1 rounded-full text-sm">Active</span>
                                        <?php else: ?>
                                            <span class="inline-block bg-gray-500 text-white px-3 py-1 rounded-full text-sm">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="border px-6 py-3">
                                        <a href="edit_course.php?id=<?php echo $c['id']; ?>" 
                                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded transition inline-block" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="delete_course.php?id=<?php echo $c['id']; ?>" 
                                           class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded transition inline-block ml-2" 
                                           onclick="return confirm('Are you sure you want to delete this course?')"
                                           title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
