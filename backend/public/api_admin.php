<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

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

    // Ensure admins table exists
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

    // Ensure personal_access_tokens table exists
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

    // Ensure default super admin exists
    $adminEmail = 'naishad@ssgd.com';
    $stmtCheck = $pdo->prepare("SELECT id, password FROM admins WHERE email = ?");
    $stmtCheck->execute([$adminEmail]);
    $superAdmin = $stmtCheck->fetch();

    if (!$superAdmin) {
        $stmtInsert = $pdo->prepare("INSERT INTO admins (name, email, password, is_super_admin, created_at, updated_at) VALUES (?, ?, ?, 1, NOW(), NOW())");
        $stmtInsert->execute(['Main Admin', $adminEmail, password_hash('naishad@123', PASSWORD_BCRYPT)]);
    }

    $action = isset($_GET['action']) ? $_GET['action'] : 'login';
    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true) ?: $_POST;

    // ── ACTION: LOGIN ──
    if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';

        if (empty($email) || empty($password)) {
            http_response_code(422);
            echo json_encode(['message' => 'Email and password are required.']);
            exit;
        }

        $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        // Auto-fix password if logging in as naishad@ssgd.com with naishad@123
        if ($admin && $admin['email'] === 'naishad@ssgd.com' && $password === 'naishad@123') {
            if (!password_verify($password, $admin['password'])) {
                $newHash = password_hash($password, PASSWORD_BCRYPT);
                $pdo->prepare("UPDATE admins SET password = ? WHERE id = ?")->execute([$newHash, $admin['id']]);
                $admin['password'] = $newHash;
            }
        }

        if (!$admin || !password_verify($password, $admin['password'])) {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid email or password.']);
            exit;
        }

        // Generate token
        $plainToken = bin2hex(random_bytes(32));
        $hashedToken = hash('sha256', $plainToken);

        // Delete old tokens and insert new
        $pdo->prepare("DELETE FROM personal_access_tokens WHERE tokenable_id = ? AND tokenable_type = 'App\\\\Models\\\\Admin'")->execute([$admin['id']]);
        $stmtToken = $pdo->prepare("INSERT INTO personal_access_tokens (tokenable_type, tokenable_id, name, token, abilities, created_at, updated_at) VALUES ('App\\\\Models\\\\Admin', ?, 'admin-panel', ?, '[\"*\"]', NOW(), NOW())");
        $stmtToken->execute([$admin['id'], $hashedToken]);

        $permissions = json_decode($admin['permissions'] ?? '[]', true) ?: [];

        echo json_encode([
            'token' => $plainToken,
            'admin' => [
                'id'             => (int)$admin['id'],
                'name'           => $admin['name'],
                'email'          => $admin['email'],
                'is_super_admin' => (bool)$admin['is_super_admin'],
                'permissions'    => $permissions,
            ],
        ]);
        exit;
    }

    // ── ACTION: ME ──
    if ($action === 'me') {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        $token = str_replace('Bearer ', '', $authHeader);

        if (!$token) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthenticated']);
            exit;
        }

        $hashedToken = hash('sha256', $token);
        $stmt = $pdo->prepare("SELECT a.* FROM admins a JOIN personal_access_tokens t ON a.id = t.tokenable_id WHERE t.token = ? LIMIT 1");
        $stmt->execute([$hashedToken]);
        $admin = $stmt->fetch();

        if (!$admin) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthenticated']);
            exit;
        }

        $permissions = json_decode($admin['permissions'] ?? '[]', true) ?: [];

        echo json_encode([
            'id'             => (int)$admin['id'],
            'name'           => $admin['name'],
            'email'          => $admin['email'],
            'is_super_admin' => (bool)$admin['is_super_admin'],
            'permissions'    => $permissions,
        ]);
        exit;
    }

    // ── ACTION: LOGOUT ──
    if ($action === 'logout') {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        $token = str_replace('Bearer ', '', $authHeader);
        if ($token) {
            $hashedToken = hash('sha256', $token);
            $pdo->prepare("DELETE FROM personal_access_tokens WHERE token = ?")->execute([$hashedToken]);
        }
        echo json_encode(['message' => 'Logged out successfully.']);
        exit;
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => true, 'message' => $e->getMessage()]);
}
