<?php
// Include header
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/models/Student.php';
require_once __DIR__ . '/models/Teacher.php';
require_once __DIR__ . '/models/Course.php';
require_once __DIR__ . '/models/Enrollment.php';

// Get statistics for dashboard
$student = new Student();
$teacher = new Teacher();
$course = new Course();
$enrollment = new Enrollment();

$totalStudents = count($student->getAll());
$totalTeachers = count($teacher->getAll());
$totalCourses = count($course->getAll());
$enrollmentStats = $enrollment->getStatistics();
?>

<div class="flex">
    <!-- Sidebar Navigation -->
    <div class="w-64 bg-gray-100 min-h-screen">
        <div class="pt-6 px-4">
            <ul class="space-y-2">
                <li>
                    <a href="pages/students.php" class="flex items-center space-x-2 px-4 py-2 text-gray-700 hover:bg-blue-100 rounded-lg transition">
                        <i class="bi bi-people"></i> <span>Students</span>
                    </a>
                </li>
                <li>
                    <a href="pages/teachers.php" class="flex items-center space-x-2 px-4 py-2 text-gray-700 hover:bg-blue-100 rounded-lg transition">
                        <i class="bi bi-person-badge"></i> <span>Teachers</span>
                    </a>
                </li>
                <li>
                    <a href="pages/courses.php" class="flex items-center space-x-2 px-4 py-2 text-gray-700 hover:bg-blue-100 rounded-lg transition">
                        <i class="bi bi-book"></i> <span>Courses</span>
                    </a>
                </li>
                <li>
                    <a href="pages/enrollments.php" class="flex items-center space-x-2 px-4 py-2 text-gray-700 hover:bg-blue-100 rounded-lg transition">
                        <i class="bi bi-clipboard-check"></i> <span>Enrollments</span>
                    </a>
                </li>
                <li>
                    <a href="pages/reports.php" class="flex items-center space-x-2 px-4 py-2 text-gray-700 hover:bg-blue-100 rounded-lg transition">
                        <i class="bi bi-file-earmark-text"></i> <span>Reports</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800">
                <i class="bi bi-mortarboard"></i> Welcome to Student Enrollment System
            </h1>
            <p class="text-lg text-gray-600 mt-2">Manage students, teachers, courses, and enrollments efficiently</p>
        </div>

        <!-- Dashboard Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-blue-500 text-white p-6 rounded-lg shadow-lg">
                <div class="text-center">
                    <i class="bi bi-people text-4xl mb-4 block"></i>
                    <h2 class="text-3xl font-bold"><?php echo $totalStudents; ?></h2>
                    <p class="mt-2">Total Students</p>
                </div>
                <div class="mt-4 text-center border-t border-blue-400 pt-3">
                    <a href="pages/students.php" class="text-white hover:underline">
                        View All <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="bg-green-500 text-white p-6 rounded-lg shadow-lg">
                <div class="text-center">
                    <i class="bi bi-person-badge text-4xl mb-4 block"></i>
                    <h2 class="text-3xl font-bold"><?php echo $totalTeachers; ?></h2>
                    <p class="mt-2">Total Teachers</p>
                </div>
                <div class="mt-4 text-center border-t border-green-400 pt-3">
                    <a href="pages/teachers.php" class="text-white hover:underline">
                        View All <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="bg-cyan-500 text-white p-6 rounded-lg shadow-lg">
                <div class="text-center">
                    <i class="bi bi-book text-4xl mb-4 block"></i>
                    <h2 class="text-3xl font-bold"><?php echo $totalCourses; ?></h2>
                    <p class="mt-2">Total Courses</p>
                </div>
                <div class="mt-4 text-center border-t border-cyan-400 pt-3">
                    <a href="pages/courses.php" class="text-white hover:underline">
                        View All <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="bg-amber-500 text-white p-6 rounded-lg shadow-lg">
                <div class="text-center">
                    <i class="bi bi-clipboard-check text-4xl mb-4 block"></i>
                    <h2 class="text-3xl font-bold"><?php echo $enrollmentStats['total_enrollments']; ?></h2>
                    <p class="mt-2">Total Enrollments</p>
                </div>
                <div class="mt-4 text-center border-t border-amber-400 pt-3">
                    <a href="pages/enrollments.php" class="text-white hover:underline">
                        View All <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-6"><i class="bi bi-lightning"></i> Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="pages/add_student.php" class="border-2 border-blue-500 text-blue-500 px-6 py-8 rounded-lg hover:bg-blue-50 transition text-center">
                    <i class="bi bi-person-plus text-4xl block mb-2"></i>
                    <span class="font-semibold">Add Student</span>
                </a>
                <a href="pages/add_teacher.php" class="border-2 border-green-500 text-green-500 px-6 py-8 rounded-lg hover:bg-green-50 transition text-center">
                    <i class="bi bi-person-plus text-4xl block mb-2"></i>
                    <span class="font-semibold">Add Teacher</span>
                </a>
                <a href="pages/add_course.php" class="border-2 border-cyan-500 text-cyan-500 px-6 py-8 rounded-lg hover:bg-cyan-50 transition text-center">
                    <i class="bi bi-plus-circle text-4xl block mb-2"></i>
                    <span class="font-semibold">Add Course</span>
                </a>
                <a href="pages/add_enrollment.php" class="border-2 border-amber-500 text-amber-500 px-6 py-8 rounded-lg hover:bg-amber-50 transition text-center">
                    <i class="bi bi-clipboard-plus text-4xl block mb-2"></i>
                    <span class="font-semibold">Add Enrollment</span>
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
