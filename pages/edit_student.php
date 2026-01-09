<?php
// Include header and required files
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../models/Student.php';

// Create student object
$student = new Student();

// Get student ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get student data
$studentData = $student->getById($id);

// If student not found, redirect
if (!$studentData) {
    header('Location: students.php?error=Student not found');
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
        'date_of_birth' => $_POST['date_of_birth'],
        'status' => $_POST['status']
    ];

    // Try to update student
    if ($student->update($id, $data)) {
        // Success - redirect to students page
        header('Location: students.php?success=updated');
        exit;
    } else {
        $error = "Failed to update student.";
    }
}
?>

<div class="max-w-2xl mx-auto mt-8 p-8">
    <div class="bg-white rounded-lg shadow-lg">
        <div class="bg-yellow-500 text-white px-6 py-4 rounded-t-lg">
            <h4 class="text-2xl"><i class="bi bi-pencil"></i> Edit Student</h4>
        </div>
        <div class="p-6">
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <!-- Student Edit Form -->
            <form method="POST" action="">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">First Name *</label>
                        <input type="text" name="first_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                               value="<?php echo htmlspecialchars($studentData['first_name']); ?>" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Last Name *</label>
                        <input type="text" name="last_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                               value="<?php echo htmlspecialchars($studentData['last_name']); ?>" required>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Email *</label>
                    <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                           value="<?php echo htmlspecialchars($studentData['email']); ?>" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Phone</label>
                    <input type="text" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                           value="<?php echo htmlspecialchars($studentData['phone']); ?>">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Date of Birth</label>
                    <input type="date" name="date_of_birth" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                           value="<?php echo $studentData['date_of_birth']; ?>">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Status *</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                        <option value="active" <?php echo $studentData['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo $studentData['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>

                <div class="flex justify-between">
                    <a href="students.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition">
                        <i class="bi bi-save"></i> Update Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
