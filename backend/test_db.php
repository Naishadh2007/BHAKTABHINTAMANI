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

    // Check if table exists
    $tables = $pdo->query("SHOW TABLES LIKE 'chapters'")->fetchAll();
    
    if (empty($tables)) {
        echo json_encode([
            'status' => 'connected_but_no_table',
            'message' => "Connected to database '$db', but 'chapters' table does not exist yet!",
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    $stmt = $pdo->query("SELECT id, `order`, title, title_gu FROM chapters ORDER BY `order`");
    $chapters = $stmt->fetchAll();

    echo json_encode([
        'status' => 'success',
        'message' => 'Connected to MySQL successfully!',
        'count' => count($chapters),
        'chapters' => $chapters,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'code' => $e->getCode(),
    ], JSON_PRETTY_PRINT);
}
