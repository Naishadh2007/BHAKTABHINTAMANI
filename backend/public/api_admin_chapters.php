<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

$host   = 'sql107.infinityfree.com';
$dbuser = 'if0_42640441';
$pass   = 'Naishadhbv2007';
$db     = 'if0_42640441_bhaktchintamani';

function getPdo($host, $dbuser, $pass, $db) {
    return new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $dbuser, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}

function respond($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $pdo    = getPdo($host, $dbuser, $pass, $db);
    $method = $_SERVER['REQUEST_METHOD'];
    $id     = isset($_GET['id'])   ? (int)$_GET['id']   : null;
    $bulk   = isset($_GET['bulk']);
    $input  = json_decode(file_get_contents('php://input'), true) ?: [];

    // ── GET all ──────────────────────────────────────────────────────────────
    if ($method === 'GET' && !$id) {
        $page   = max(1, (int)($_GET['page']  ?? 1));
        $limit  = max(1, (int)($_GET['limit'] ?? 30));
        $offset = ($page - 1) * $limit;

        // Try query with status first; fallback without if column missing
        $search = !empty($_GET['search']) ? '%' . trim($_GET['search']) . '%' : null;
        $status = !empty($_GET['status']) ? trim($_GET['status']) : null;

        // Build WHERE
        $where  = [];
        $params = [];
        if ($search) {
            $where[]  = '(title LIKE ? OR title_gu LIKE ? OR title_en LIKE ?)';
            $params   = [$search, $search, $search];
        }

        $whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        // Count total
        $cnt = $pdo->prepare("SELECT COUNT(*) FROM chapters $whereSQL");
        $cnt->execute($params);
        $total = (int)$cnt->fetchColumn();

        // Try fetching with status column
        $rows = null;
        try {
            $filterParams = $params;
            $statusWhere  = $whereSQL;
            if ($status) {
                $statusWhere = $statusWhere ? "$statusWhere AND `status` = ?" : "WHERE `status` = ?";
                $filterParams[] = $status;
            }
            $s = $pdo->prepare("SELECT id, `order`, title, title_gu, title_en, `status`, description_gu FROM chapters $statusWhere ORDER BY `order` ASC LIMIT ? OFFSET ?");
            $s->execute(array_merge($filterParams, [$limit, $offset]));
            $rows = $s->fetchAll();
        } catch (PDOException $noStatus) {
            // status column doesn't exist — fetch without it
            $s = $pdo->prepare("SELECT id, `order`, title, title_gu, title_en, description_gu FROM chapters $whereSQL ORDER BY `order` ASC LIMIT ? OFFSET ?");
            $s->execute(array_merge($params, [$limit, $offset]));
            $rows = $s->fetchAll();
            foreach ($rows as &$r) { $r['status'] = 'published'; }
            unset($r);
        }

        respond([
            'data'     => $rows,
            'total'    => $total,
            'page'     => $page,
            'limit'    => $limit,
            'has_more' => ($offset + $limit) < $total,
        ]);
    }

    // ── GET single ───────────────────────────────────────────────────────────
    if ($method === 'GET' && $id) {
        $s = $pdo->prepare("SELECT * FROM chapters WHERE id = ?");
        $s->execute([$id]);
        $ch = $s->fetch();
        if (!$ch) respond(['message' => 'Not found'], 404);
        if (!isset($ch['status'])) $ch['status'] = 'published';
        respond($ch);
    }

    // ── POST bulk ─────────────────────────────────────────────────────────────
    if ($method === 'POST' && $bulk) {
        $ids    = array_map('intval', (array)($input['ids'] ?? []));
        $action = trim($input['action'] ?? '');
        if (!$ids) respond(['message' => 'No IDs']);
        $ph = implode(',', array_fill(0, count($ids), '?'));
        if ($action === 'delete') {
            $pdo->prepare("DELETE FROM chapters WHERE id IN ($ph)")->execute($ids);
        } elseif (in_array($action, ['publish','draft'])) {
            $st = $action === 'publish' ? 'published' : 'draft';
            try { $pdo->prepare("UPDATE chapters SET `status`='$st' WHERE id IN ($ph)")->execute($ids); }
            catch (PDOException $e) { /* status column missing, ignore */ }
        }
        respond(['message' => 'Done']);
    }

    // ── POST create ───────────────────────────────────────────────────────────
    if ($method === 'POST') {
        $maxOrder = (int)$pdo->query("SELECT COALESCE(MAX(`order`),0) FROM chapters")->fetchColumn() + 1;
        try {
            $pdo->prepare("INSERT INTO chapters (`order`,title,title_gu,title_en,description,description_gu,description_en,content,content_gu,content_en,`status`,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,NOW(),NOW())")
                ->execute([
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
        } catch (PDOException $noStatus) {
            // Retry without status column
            $pdo->prepare("INSERT INTO chapters (`order`,title,title_gu,title_en,description,description_gu,description_en,content,content_gu,content_en,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,NOW(),NOW())")
                ->execute([
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
                ]);
        }
        $newId = $pdo->lastInsertId();
        $s = $pdo->prepare("SELECT * FROM chapters WHERE id=?"); $s->execute([$newId]);
        respond($s->fetch());
    }

    // ── PUT update ────────────────────────────────────────────────────────────
    if ($method === 'PUT' && $id) {
        $allowed = ['order','title','title_gu','title_en','description','description_gu','description_en','content','content_gu','content_en','status'];
        $fields = []; $vals = [];
        foreach ($allowed as $f) {
            if (array_key_exists($f, $input)) { $fields[] = "`$f`=?"; $vals[] = $input[$f]; }
        }
        if (!$fields) respond(['message' => 'Nothing to update']);
        $vals[] = $id;
        try {
            $pdo->prepare("UPDATE chapters SET " . implode(',', $fields) . ",updated_at=NOW() WHERE id=?")->execute($vals);
        } catch (PDOException $noStatus) {
            // Remove status and retry
            $fields2 = []; $vals2 = [];
            foreach (array_diff($allowed, ['status']) as $f) {
                if (array_key_exists($f, $input)) { $fields2[] = "`$f`=?"; $vals2[] = $input[$f]; }
            }
            if ($fields2) { $vals2[] = $id; $pdo->prepare("UPDATE chapters SET " . implode(',', $fields2) . ",updated_at=NOW() WHERE id=?")->execute($vals2); }
        }
        $s = $pdo->prepare("SELECT * FROM chapters WHERE id=?"); $s->execute([$id]);
        respond($s->fetch());
    }

    // ── DELETE ────────────────────────────────────────────────────────────────
    if ($method === 'DELETE' && $id) {
        $pdo->prepare("DELETE FROM chapters WHERE id=?")->execute([$id]);
        respond(['message' => 'Deleted']);
    }

    respond(['message' => 'Unknown action'], 400);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => true, 'message' => $e->getMessage(), 'line' => $e->getLine()]);
}
