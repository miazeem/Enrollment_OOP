<?php
// Include header and required files
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../models/Teacher.php';

// Create teacher object
$teacher = new Teacher();

// Get teacher ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get teacher data
$teacherData = $teacher->getById($id);

// If teacher not found, redirect
if (!$teacherData) {
    header('Location: teachers.php?error=Teacher not found');
    exit;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $data = [
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'],
        'specialization' => $_POST['specialization'],
        'hire_date' => $_POST['hire_date'],
        'status' => $_POST['status']
    ];

    // Try to update teacher
    if ($teacher->update($id, $data)) {
        // Success - redirect to teachers page
        header('Location: teachers.php?success=updated');
        exit;
    } else {
        $error = "Failed to update teacher.";
    }
}
?>

<div class="max-w-2xl mx-auto mt-8 p-8">
    <div class="bg-white rounded-lg shadow-lg">
        <div class="bg-yellow-500 text-white px-6 py-4 rounded-t-lg">
            <h4 class="text-2xl"><i class="bi bi-pencil"></i> Edit Teacher</h4>
        </div>
        <div class="p-6">
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <!-- Teacher Edit Form -->
            <form method="POST" action="">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">First Name *</label>
                        <input type="text" name="first_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                               value="<?php echo htmlspecialchars($teacherData['first_name']); ?>" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Last Name *</label>
                        <input type="text" name="last_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                               value="<?php echo htmlspecialchars($teacherData['last_name']); ?>" required>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Email *</label>
                    <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                           value="<?php echo htmlspecialchars($teacherData['email']); ?>" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Phone</label>
                    <input type="text" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                           value="<?php echo htmlspecialchars($teacherData['phone']); ?>">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Specialization</label>
                    <input type="text" name="specialization" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                           value="<?php echo htmlspecialchars($teacherData['specialization']); ?>">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Hire Date</label>
                    <input type="date" name="hire_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                           value="<?php echo $teacherData['hire_date']; ?>">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Status *</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                        <option value="active" <?php echo $teacherData['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo $teacherData['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>

                <div class="flex justify-between">
                    <a href="teachers.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition">
                        <i class="bi bi-save"></i> Update Teacher
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
