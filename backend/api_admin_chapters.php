<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

$host = 'sql107.infinityfree.com';
$dbuser = 'if0_42640441';
$pass = 'Naishadhbv2007';
$db   = 'if0_42640441_bhaktchintamani';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $dbuser, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Add status column if missing
    try {
        $pdo->exec("ALTER TABLE chapters ADD COLUMN `status` VARCHAR(20) NOT NULL DEFAULT 'published' AFTER `order`");
    } catch(PDOException $ignored) {}

    $method = $_SERVER['REQUEST_METHOD'];
    $id     = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $input  = json_decode(file_get_contents('php://input'), true) ?: [];

    // ── GET all (admin list) ──────────────────────────────────────────────────
    if ($method === 'GET' && !$id) {
        $page   = max(1, (int)($_GET['page']  ?? 1));
        $limit  = max(1, (int)($_GET['limit'] ?? 30));
        $offset = ($page - 1) * $limit;
        $sort   = in_array($_GET['sort'] ?? '', ['order','title_gu','status','id']) ? $_GET['sort'] : 'order';
        $dir    = ($_GET['dir'] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';
        $search = isset($_GET['search']) ? '%' . trim($_GET['search']) . '%' : null;
        $status = isset($_GET['status']) ? trim($_GET['status']) : null;

        $where = []; $params = [];
        if ($search) { $where[] = '(title LIKE ? OR title_gu LIKE ? OR title_en LIKE ?)'; $params = array_merge($params, [$search,$search,$search]); }
        if ($status) { $where[] = 'status = ?'; $params[] = $status; }
        $whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $total = $pdo->prepare("SELECT COUNT(*) FROM chapters $whereSQL");
        $total->execute($params);
        $totalCount = (int)$total->fetchColumn();

        $stmt = $pdo->prepare("SELECT id, `order`, title, title_gu, title_en, status, description_gu FROM chapters $whereSQL ORDER BY `$sort` $dir LIMIT ? OFFSET ?");
        $stmt->execute(array_merge($params, [$limit, $offset]));
        $rows = $stmt->fetchAll();

        echo json_encode([
            'data'     => $rows,
            'total'    => $totalCount,
            'page'     => $page,
            'limit'    => $limit,
            'has_more' => ($offset + $limit) < $totalCount,
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // ── GET single ───────────────────────────────────────────────────────────
    if ($method === 'GET' && $id) {
        $stmt = $pdo->prepare("SELECT * FROM chapters WHERE id = ?");
        $stmt->execute([$id]);
        $ch = $stmt->fetch();
        if (!$ch) { http_response_code(404); echo json_encode(['message'=>'Not found']); exit; }
        echo json_encode($ch, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // ── POST create ───────────────────────────────────────────────────────────
    if ($method === 'POST') {
        $maxOrder = $pdo->query("SELECT COALESCE(MAX(`order`),0)+1 FROM chapters")->fetchColumn();
        $stmt = $pdo->prepare("INSERT INTO chapters (`order`,title,title_gu,title_en,description,description_gu,description_en,content,content_gu,content_en,status,created_at,updated_at)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,NOW(),NOW())");
        $stmt->execute([
            $input['order']          ?? $maxOrder,
            $input['title']          ?? ($input['title_gu'] ?? ''),
            $input['title_gu']       ?? '',
            $input['title_en']       ?? '',
            $input['description']    ?? ($input['description_gu'] ?? ''),
            $input['description_gu'] ?? '',
            $input['description_en'] ?? '',
            $input['content']        ?? ($input['content_gu'] ?? ''),
            $input['content_gu']     ?? '',
            $input['content_en']     ?? '',
            $input['status']         ?? 'published',
        ]);
        $newId = $pdo->lastInsertId();
        $row = $pdo->prepare("SELECT * FROM chapters WHERE id=?"); $row->execute([$newId]);
        echo json_encode($row->fetch(), JSON_UNESCAPED_UNICODE);
        exit;
    }

    // ── PUT update ────────────────────────────────────────────────────────────
    if ($method === 'PUT' && $id) {
        $fields = []; $vals = [];
        $allowed = ['order','title','title_gu','title_en','description','description_gu','description_en','content','content_gu','content_en','status'];
        foreach ($allowed as $f) {
            if (array_key_exists($f, $input)) { $fields[] = "`$f` = ?"; $vals[] = $input[$f]; }
        }
        if (!$fields) { echo json_encode(['message'=>'Nothing to update']); exit; }
        $vals[] = $id;
        $pdo->prepare("UPDATE chapters SET " . implode(', ', $fields) . ", updated_at=NOW() WHERE id=?")->execute($vals);
        $row = $pdo->prepare("SELECT * FROM chapters WHERE id=?"); $row->execute([$id]);
        echo json_encode($row->fetch(), JSON_UNESCAPED_UNICODE);
        exit;
    }

    // ── DELETE ────────────────────────────────────────────────────────────────
    if ($method === 'DELETE' && $id) {
        $pdo->prepare("DELETE FROM chapters WHERE id=?")->execute([$id]);
        echo json_encode(['message'=>'Deleted']);
        exit;
    }

    // ── POST /bulk ────────────────────────────────────────────────────────────
    if ($method === 'POST' && isset($_GET['bulk'])) {
        $ids    = array_map('intval', (array)($input['ids'] ?? []));
        $action = $input['action'] ?? '';
        if (!$ids) { echo json_encode(['message'=>'No IDs']); exit; }
        $ph = implode(',', array_fill(0, count($ids), '?'));
        if ($action === 'delete') {
            $pdo->prepare("DELETE FROM chapters WHERE id IN ($ph)")->execute($ids);
        } elseif ($action === 'publish') {
            $pdo->prepare("UPDATE chapters SET status='published' WHERE id IN ($ph)")->execute($ids);
        } elseif ($action === 'draft') {
            $pdo->prepare("UPDATE chapters SET status='draft' WHERE id IN ($ph)")->execute($ids);
        }
        echo json_encode(['message'=>'Done']);
        exit;
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error'=>true, 'message'=>$e->getMessage()]);
}
