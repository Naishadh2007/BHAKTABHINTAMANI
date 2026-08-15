<?php
header('Content-Type: application/json; charset=utf-8');

$host = 'sql107.infinityfree.com';
$user = 'if0_42640441';
$pass = 'Naishadhbv2007';
$db   = 'if0_42640441_bhaktchintamani';
$port = 3306;

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // 1. Create admins table if not exists
    $pdo->exec("CREATE TABLE IF NOT EXISTS `admins` (
      `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
      `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
      `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
      `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
      `is_super_admin` tinyint(1) NOT NULL DEFAULT '0',
      `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
      `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `admins_email_unique` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // 2. Create personal_access_tokens table if not exists
    $pdo->exec("CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
      `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
      `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
      `tokenable_id` bigint(20) UNSIGNED NOT NULL,
      `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
      `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
      `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      `last_used_at` timestamp NULL DEFAULT NULL,
      `expires_at` timestamp NULL DEFAULT NULL,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
      KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // 3. Upsert Super Admin credentials
    $adminEmail = 'naishad@ssgd.com';
    $adminPlainPassword = 'naishad@123';
    $hashedPassword = password_hash($adminPlainPassword, PASSWORD_BCRYPT);

    $stmtCheck = $pdo->prepare("SELECT id FROM admins WHERE email = ?");
    $stmtCheck->execute([$adminEmail]);
    $existing = $stmtCheck->fetch();

    if ($existing) {
        $stmtUpdate = $pdo->prepare("UPDATE admins SET password = ?, is_super_admin = 1 WHERE email = ?");
        $stmtUpdate->execute([$hashedPassword, $adminEmail]);
    } else {
        $stmtInsert = $pdo->prepare("INSERT INTO admins (name, email, password, is_super_admin, created_at, updated_at) VALUES (?, ?, ?, 1, NOW(), NOW())");
        $stmtInsert->execute(['Main Admin', $adminEmail, $hashedPassword]);
    }

    // 4. Fetch all admins in DB
    $allAdmins = $pdo->query("SELECT id, name, email, is_super_admin, created_at FROM admins")->fetchAll();

    echo json_encode([
        'status' => 'success',
        'message' => 'Admin tables verified and Super Admin credentials ready!',
        'default_credentials' => [
            'login_url' => '/admin/login',
            'email'     => 'naishad@ssgd.com',
            'password'  => 'naishad@123',
        ],
        'registered_admins' => $allAdmins,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ], JSON_PRETTY_PRINT);
}
