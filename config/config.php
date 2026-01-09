<?php
/**
 * Database Configuration File
 * Student Course Enrollment System
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'enrollment_system');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Application Configuration
define('APP_NAME', 'Student Enrollment System');
define('APP_URL', 'http://localhost/Projects/Enrollment_OOP/');

// Timezone
date_default_timezone_set('UTC');

// Error Reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
