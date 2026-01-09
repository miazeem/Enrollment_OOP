<?php
// Include header and required files
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../models/Teacher.php';

// Create teacher object
$teacher = new Teacher();

// Get all teachers from database
$teachers = $teacher->getAll();
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
                    <a href="teachers.php" class="flex items-center space-x-2 px-4 py-2 text-gray-700 bg-blue-100 rounded-lg transition">
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
            <h2 class="text-3xl font-bold text-gray-800"><i class="bi bi-person-badge"></i> Teacher Management</h2>
            <a href="add_teacher.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                <i class="bi bi-plus-circle"></i> Add New Teacher
            </a>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                <?php 
                if ($_GET['success'] == 'added') echo 'Teacher added successfully!';
                if ($_GET['success'] == 'updated') echo 'Teacher updated successfully!';
                if ($_GET['success'] == 'deleted') echo 'Teacher deleted successfully!';
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                Error: <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Teachers Table -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse">
                    <thead class="bg-blue-600 text-white">
                        <tr>
                            <th class="border px-6 py-3 text-left">ID</th>
                            <th class="border px-6 py-3 text-left">Name</th>
                            <th class="border px-6 py-3 text-left">Email</th>
                            <th class="border px-6 py-3 text-left">Phone</th>
                            <th class="border px-6 py-3 text-left">Specialization</th>
                            <th class="border px-6 py-3 text-left">Status</th>
                            <th class="border px-6 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($teachers)): ?>
                            <tr>
                                <td colspan="7" class="border px-6 py-3 text-center text-gray-500">No teachers found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($teachers as $t): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="border px-6 py-3"><?php echo $t['id']; ?></td>
                                    <td class="border px-6 py-3"><?php echo htmlspecialchars($t['first_name'] . ' ' . $t['last_name']); ?></td>
                                    <td class="border px-6 py-3"><?php echo htmlspecialchars($t['email']); ?></td>
                                    <td class="border px-6 py-3"><?php echo htmlspecialchars($t['phone']); ?></td>
                                    <td class="border px-6 py-3"><?php echo htmlspecialchars($t['specialization']); ?></td>
                                    <td class="border px-6 py-3">
                                        <?php if ($t['status'] == 'active'): ?>
                                            <span class="inline-block bg-green-500 text-white px-3 py-1 rounded-full text-sm">Active</span>
                                        <?php else: ?>
                                            <span class="inline-block bg-gray-500 text-white px-3 py-1 rounded-full text-sm">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="border px-6 py-3">
                                        <a href="edit_teacher.php?id=<?php echo $t['id']; ?>" 
                                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded transition inline-block" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="delete_teacher.php?id=<?php echo $t['id']; ?>" 
                                           class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded transition inline-block ml-2" 
                                           onclick="return confirm('Are you sure you want to delete this teacher?')"
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
