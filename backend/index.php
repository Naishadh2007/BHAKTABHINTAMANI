<?php

// Check if vendor/autoload.php exists (Laravel Environment)
$vendorPath = __DIR__ . '/vendor/autoload.php';
if (!file_exists($vendorPath) && file_exists(__DIR__ . '/../vendor/autoload.php')) {
    $vendorPath = __DIR__ . '/../vendor/autoload.php';
}

if (file_exists($vendorPath)) {
    require_once __DIR__ . '/public/index.php';
} else {
    // Standalone Environment for Free Hosting (e.g., InfinityFree without Composer vendor)
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

    // 1. Admin Auth Routes
    if (preg_match('#^/(public/)?api/admin/(login|me|logout)#i', $uri)) {
        require __DIR__ . '/api_admin.php';
        exit;
    }

    // 2. Admin Chapters Routes
    if (preg_match('#^/(public/)?api/admin/chapters#i', $uri)) {
        require __DIR__ . '/api_admin_chapters.php';
        exit;
    }

    // 3. Admin Users Routes
    if (preg_match('#^/(public/)?api/admin/users#i', $uri)) {
        require __DIR__ . '/api_admin_users.php';
        exit;
    }

    // 4. Public Chapters Routes
    if (preg_match('#^/(public/)?api/chapters#i', $uri)) {
        require __DIR__ . '/api_chapters.php';
        exit;
    }

    // 5. Default Fallback to React SPA index.html
    $indexPath = __DIR__ . '/index.html';
    if (!file_exists($indexPath) && file_exists(__DIR__ . '/public/index.html')) {
        $indexPath = __DIR__ . '/public/index.html';
    }

    if (file_exists($indexPath)) {
        header('Content-Type: text/html; charset=utf-8');
        readfile($indexPath);
        exit;
    } else {
        echo "BhaktaChintamani Platform is active.";
        exit;
    }
}
