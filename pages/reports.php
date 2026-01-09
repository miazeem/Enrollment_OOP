<?php
// Include header and required files
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/Database.php';

// Get database connection
$db = Database::getInstance()->getConnection();

// Query to get comprehensive student enrollment report with teachers
$query = "SELECT 
    s.id,
    s.first_name as student_first,
    s.last_name as student_last,
    s.email as student_email,
    c.course_code,
    c.course_name,
    c.credits,
    e.enrollment_date,
    e.grade,
    e.status as enrollment_status,
    GROUP_CONCAT(CONCAT(t.first_name, ' ', t.last_name) SEPARATOR ', ') as teachers
FROM students s
INNER JOIN enrollments e ON s.id = e.student_id
INNER JOIN courses c ON e.course_id = c.id
LEFT JOIN course_teachers ct ON c.id = ct.course_id
LEFT JOIN teachers t ON ct.teacher_id = t.id
GROUP BY s.id, c.id, e.id
ORDER BY s.last_name, s.first_name, c.course_code";

$stmt = $db->prepare($query);
$stmt->execute();
$reportData = $stmt->fetchAll();
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
                    <a href="courses.php" class="flex items-center space-x-2 px-4 py-2 text-gray-700 hover:bg-blue-100 rounded-lg transition">
                        <i class="bi bi-book"></i> <span>Courses</span>
                    </a>
                </li>
                <li>
                    <a href="enrollments.php" class="flex items-center space-x-2 px-4 py-2 text-gray-700 hover:bg-blue-100 rounded-lg transition">
                        <i class="bi bi-clipboard-check"></i> <span>Enrollments</span>
                    </a>
                </li>
                <li>
                    <a href="reports.php" class="flex items-center space-x-2 px-4 py-2 text-gray-700 bg-blue-100 rounded-lg transition">
                        <i class="bi bi-file-earmark-text"></i> <span>Reports</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex-1 p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800"><i class="bi bi-file-earmark-text"></i> Student Enrollment Report</h2>
        
        </div>

        <div class="bg-cyan-100 border-l-4 border-cyan-500 text-cyan-700 p-4 mb-6 rounded">
            <i class="bi bi-info-circle"></i> 
            This report shows all students with their enrolled courses and assigned teachers.
        </div>

        <!-- Report Table -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse">
                    <thead class="bg-blue-600 text-white">
                        <tr>
                            <th class="border px-6 py-3 text-left">Student ID</th>
                            <th class="border px-6 py-3 text-left">Student Name</th>
                            <th class="border px-6 py-3 text-left">Email</th>
                            <th class="border px-6 py-3 text-left">Course Code</th>
                            <th class="border px-6 py-3 text-left">Course Name</th>
                            <th class="border px-6 py-3 text-left">Credits</th>
                            <th class="border px-6 py-3 text-left">Teachers</th>
                            <th class="border px-6 py-3 text-left">Grade</th>
                            <th class="border px-6 py-3 text-left">Status</th>
                            <th class="border px-6 py-3 text-left">Enrollment Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reportData)): ?>
                            <tr>
                                <td colspan="10" class="border px-6 py-3 text-center text-gray-500">No enrollment data found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($reportData as $row): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="border px-6 py-3"><?php echo $row['id']; ?></td>
                                    <td class="border px-6 py-3"><strong><?php echo htmlspecialchars($row['student_first'] . ' ' . $row['student_last']); ?></strong></td>
                                    <td class="border px-6 py-3"><?php echo htmlspecialchars($row['student_email']); ?></td>
                                    <td class="border px-6 py-3"><span class="inline-block bg-gray-500 text-white px-2 py-1 rounded text-sm"><?php echo htmlspecialchars($row['course_code']); ?></span></td>
                                    <td class="border px-6 py-3"><?php echo htmlspecialchars($row['course_name']); ?></td>
                                    <td class="border px-6 py-3"><?php echo $row['credits']; ?></td>
                                    <td class="border px-6 py-3">
                                        <?php if ($row['teachers']): ?>
                                            <small><?php echo htmlspecialchars($row['teachers']); ?></small>
                                        <?php else: ?>
                                            <span class="text-gray-500">No teacher</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="border px-6 py-3">
                                        <?php if ($row['grade']): ?>
                                            <span class="inline-block bg-cyan-500 text-white px-2 py-1 rounded text-sm"><?php echo htmlspecialchars($row['grade']); ?></span>
                                        <?php else: ?>
                                            <span class="text-gray-500">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="border px-6 py-3">
                                        <?php if ($row['enrollment_status'] == 'enrolled'): ?>
                                            <span class="inline-block bg-green-500 text-white px-2 py-1 rounded-full text-sm">Enrolled</span>
                                        <?php elseif ($row['enrollment_status'] == 'completed'): ?>
                                            <span class="inline-block bg-blue-500 text-white px-2 py-1 rounded-full text-sm">Completed</span>
                                        <?php else: ?>
                                            <span class="inline-block bg-gray-500 text-white px-2 py-1 rounded-full text-sm">Dropped</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="border px-6 py-3"><?php echo date('M d, Y', strtotime($row['enrollment_date'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary Statistics -->
        <?php
        // Calculate summary statistics
        $totalEnrollments = count($reportData);
        $uniqueStudents = count(array_unique(array_column($reportData, 'id')));
        $uniqueCourses = count(array_unique(array_column($reportData, 'course_code')));
        ?>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-blue-500 text-white p-6 rounded-lg shadow-lg">
                <div class="text-center">
                    <h3 class="text-3xl font-bold"><?php echo $totalEnrollments; ?></h3>
                    <p class="mt-2">Total Enrollments</p>
                </div>
            </div>
            <div class="bg-green-500 text-white p-6 rounded-lg shadow-lg">
                <div class="text-center">
                    <h3 class="text-3xl font-bold"><?php echo $uniqueStudents; ?></h3>
                    <p class="mt-2">Enrolled Students</p>
                </div>
            </div>
            <div class="bg-cyan-500 text-white p-6 rounded-lg shadow-lg">
                <div class="text-center">
                    <h3 class="text-3xl font-bold"><?php echo $uniqueCourses; ?></h3>
                    <p class="mt-2">Active Courses</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
