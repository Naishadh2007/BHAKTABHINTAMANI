<?php
/**
 * Smart Gateway:
 * - When vendor/autoload.php exists (Local development / Laravel server): Runs full Laravel application
 * - When vendor/autoload.php does NOT exist (InfinityFree shared hosting): Routes to standalone PHP scripts & React SPA
 */

$vendorPath = __DIR__ . '/vendor/autoload.php';
if (!file_exists($vendorPath) && file_exists(__DIR__ . '/../vendor/autoload.php')) {
    $vendorPath = __DIR__ . '/../vendor/autoload.php';
}

if (file_exists($vendorPath)) {
    // Run full Laravel
    require_once __DIR__ . '/public/index.php';
} else {
    $uri = strtok(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '?');
    $dir = __DIR__;

    // Admin Auth: login, me, logout
    if (preg_match('#^/(public/)?api/admin/(login|me|logout)#i', $uri)) {
        require $dir . '/api_admin.php';
        exit;
    }

    // Admin Chapters CRUD
    if (preg_match('#^/(public/)?api/admin/chapters#i', $uri)) {
        require $dir . '/api_admin_chapters.php';
        exit;
    }

    // Admin Users CRUD
    if (preg_match('#^/(public/)?api/admin/users#i', $uri)) {
        require $dir . '/api_admin_users.php';
        exit;
    }

    // Public Chapters
    if (preg_match('#^/(public/)?api/chapters#i', $uri)) {
        require $dir . '/api_chapters.php';
        exit;
    }

    // Serve React SPA for all other routes
    $indexHtml = $dir . '/index.html';
    if (file_exists($indexHtml)) {
        header('Content-Type: text/html; charset=utf-8');
        readfile($indexHtml);
    } else {
        http_response_code(404);
        echo 'Not found';
    }
    exit;
}
