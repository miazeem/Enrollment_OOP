<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/Database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-gray-50">
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="<?php echo APP_URL; ?>" class="flex items-center space-x-2 font-bold text-lg">
                    <i class="bi bi-mortarboard-fill text-2xl"></i>
                    <span><?php echo APP_NAME; ?></span>
                </a>
                <div class="hidden md:flex space-x-4">
                    <a href="<?php echo APP_URL; ?>" class="hover:bg-blue-700 px-3 py-2 rounded-md transition">
                        <i class="bi bi-house-door"></i> Home
                    </a>
                </div>
            </div>
        </div>
    </nav>
