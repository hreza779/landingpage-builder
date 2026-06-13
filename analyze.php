<?php
$db_file = __DIR__ . '/data/tracking.sqlite';
$pdo = new PDO('sqlite:' . $db_file);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Query total visits and bounced visits per page + utm_source + utm_campaign
$visitsQuery = "
    SELECT 
        page, 
        CASE WHEN utm_source IS NULL OR utm_source = '' THEN 'Direct' ELSE utm_source END as utm_source, 
        CASE WHEN utm_campaign IS NULL OR utm_campaign = '' THEN 'None' ELSE utm_campaign END as utm_campaign,
        COUNT(*) as total_visits,
        SUM(is_bounced) as total_bounced
    FROM visits 
    GROUP BY page, utm_source, utm_campaign
";
$stmt = $pdo->query($visitsQuery);
$visits = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Query leads per page + utm_source + utm_campaign
$leadsQuery = "
    SELECT 
        page, 
        CASE WHEN utm_source IS NULL OR utm_source = '' THEN 'Direct' ELSE utm_source END as utm_source, 
        CASE WHEN utm_campaign IS NULL OR utm_campaign = '' THEN 'None' ELSE utm_campaign END as utm_campaign,
        COUNT(*) as total_leads
    FROM leads 
    GROUP BY page, utm_source, utm_campaign
";
$stmt = $pdo->query($leadsQuery);
$leads = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Combine data
$data = [];
foreach ($visits as $v) {
    $key = $v['page'] . '|' . $v['utm_source'] . '|' . $v['utm_campaign'];
    $data[$key] = [
        'page' => $v['page'],
        'utm_source' => $v['utm_source'],
        'utm_campaign' => $v['utm_campaign'],
        'visits' => $v['total_visits'],
        'bounces' => $v['total_bounced'],
        'leads' => 0
    ];
}

foreach ($leads as $l) {
    $key = $l['page'] . '|' . $l['utm_source'] . '|' . $l['utm_campaign'];
    if (!isset($data[$key])) {
        $data[$key] = [
            'page' => $l['page'],
            'utm_source' => $l['utm_source'],
            'utm_campaign' => $l['utm_campaign'],
            'visits' => 0,
            'bounces' => 0,
            'leads' => 0
        ];
    }
    $data[$key]['leads'] = $l['total_leads'];
}

echo json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
