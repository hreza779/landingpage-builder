<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$data_dir = __DIR__ . '/data';
if (!is_dir($data_dir)) {
    mkdir($data_dir, 0755, true);
    file_put_contents($data_dir . '/.htaccess', "Deny from all\n");
}

$db_file = $data_dir . '/tracking.sqlite';
$is_new_db = !file_exists($db_file);

try {
    $pdo = new PDO('sqlite:' . $db_file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($is_new_db) {
        $pdo->exec("CREATE TABLE visits (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            page TEXT,
            utm_source TEXT,
            utm_medium TEXT,
            utm_campaign TEXT,
            utm_term TEXT,
            utm_content TEXT,
            referrer TEXT,
            ip TEXT,
            user_agent TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
    }

    $pdo->exec("CREATE TABLE IF NOT EXISTS leads (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        page TEXT,
        name TEXT,
        phone TEXT,
        details TEXT,
        ip TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        utm_source TEXT,
        utm_medium TEXT,
        utm_campaign TEXT,
        utm_term TEXT,
        utm_content TEXT
    )");

    $new_cols = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];
    foreach($new_cols as $c) {
        try { $pdo->exec("ALTER TABLE leads ADD COLUMN $c TEXT"); } catch (Exception $e) {}
    }

    $pdo->exec("DELETE FROM visits WHERE page = 'srcdoc' OR page = 'about:srcdoc' OR page = ''");

    $input = json_decode(file_get_contents('php://input'), true);

    if ($input) {
        $action = $input['action'] ?? 'visit';
        $page = $input['page'] ?? 'unknown';

        if ($page === 'srcdoc' || $page === 'about:srcdoc') {
            echo json_encode(['status' => 'ignored_preview']);
            exit;
        }

        if ($action === 'lead') {
            
            $phone = $input['phone'] ?? '';
            $persian_arabic = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹','٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
            $english = ['0','1','2','3','4','5','6','7','8','9','0','1','2','3','4','5','6','7','8','9'];
            $phone = str_replace($persian_arabic, $english, $phone);
            $phone = preg_replace('/[^0-9+]/', '', $phone); 

            $stmt = $pdo->prepare("INSERT INTO leads (page, name, phone, details, ip, utm_source, utm_medium, utm_campaign, utm_term, utm_content) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $page,
                $input['name'] ?? '',
                $phone,
                $input['details'] ?? '{}',
                $_SERVER['REMOTE_ADDR'] ?? '',
                $input['utm_source'] ?? '',
                $input['utm_medium'] ?? '',
                $input['utm_campaign'] ?? '',
                $input['utm_term'] ?? '',
                $input['utm_content'] ?? ''
            ]);
            echo json_encode(['status' => 'success_lead']);
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO visits (page, utm_source, utm_medium, utm_campaign, utm_term, utm_content, referrer, ip, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $page,
            $input['utm_source'] ?? '',
            $input['utm_medium'] ?? '',
            $input['utm_campaign'] ?? '',
            $input['utm_term'] ?? '',
            $input['utm_content'] ?? '',
            $input['referrer'] ?? '',
            $_SERVER['REMOTE_ADDR'] ?? '',
            $_SERVER['HTTP_USER_AGENT'] ?? ''
        ]);
        
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No data received']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
