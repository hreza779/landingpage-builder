<?php
$db_file = __DIR__ . '/../data/tracking.sqlite';
if (!file_exists($db_file)) {
    $db_file = __DIR__ . '/data/tracking.sqlite';
}

$pdo = null;

if (file_exists($db_file)) {
    try {
        $pdo = new PDO('sqlite:' . $db_file);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try { $pdo->exec("ALTER TABLE visits ADD COLUMN is_bounced INTEGER DEFAULT 1"); } catch (Exception $e) {}
    } catch (PDOException $e) {
        $db_error = $e->getMessage();
    }
}
