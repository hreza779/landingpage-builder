<?php
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

$analysis_data = array_values($data);

// Sort by visits descending
usort($analysis_data, function($a, $b) {
    return $b['visits'] <=> $a['visits'];
});
?>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-[700px]">
    <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 shrink-0">
        <div class="flex flex-col gap-1">
            <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2">
                <i class="fa-solid fa-bullhorn text-purple-500"></i> <?= $lang === 'fa' ? 'گزارش تحلیلی کمپین‌ها' : 'Campaign Analysis Report' ?>
            </h3>
            <p class="text-xs text-gray-500"><?= $lang === 'fa' ? 'تحلیل نرخ پرش و نرخ تبدیل به تفکیک لندینگ و تبلیغات' : 'Bounce rate and conversion rate by landing page and ads' ?></p>
        </div>
        
        <button onclick="window.print()" class="bg-purple-600 hover:bg-purple-500 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-all shadow-md flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-print"></i> <?= $lang === 'fa' ? 'چاپ گزارش' : 'Print Report' ?>
        </button>
    </div>
    
    <div class="flex-1 overflow-auto custom-scrollbar p-0">
        <table class="w-full text-right text-sm">
            <thead class="bg-gray-50 sticky top-0 shadow-sm z-10">
                <tr>
                    <th class="py-3 px-4 text-gray-500 font-medium whitespace-nowrap"><?= $lang === 'fa' ? 'نام لندینگ' : 'Landing Name' ?></th>
                    <th class="py-3 px-4 text-gray-500 font-medium whitespace-nowrap"><?= $lang === 'fa' ? 'منبع (UTM Source)' : 'UTM Source' ?></th>
                    <th class="py-3 px-4 text-gray-500 font-medium whitespace-nowrap"><?= $lang === 'fa' ? 'کمپین (UTM Campaign)' : 'UTM Campaign' ?></th>
                    <th class="py-3 px-4 text-gray-500 font-medium whitespace-nowrap text-center"><?= $lang === 'fa' ? 'تعداد بازدید' : 'Visits' ?></th>
                    <th class="py-3 px-4 text-gray-500 font-medium whitespace-nowrap text-center"><?= $lang === 'fa' ? 'نرخ پرش' : 'Bounce Rate' ?></th>
                    <th class="py-3 px-4 text-gray-500 font-medium whitespace-nowrap text-center"><?= $lang === 'fa' ? 'تعداد لید' : 'Leads' ?></th>
                    <th class="py-3 px-4 text-gray-500 font-medium whitespace-nowrap text-center"><?= $lang === 'fa' ? 'نرخ تبدیل' : 'Conv. Rate' ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach($analysis_data as $row): 
                    $bounce_rate = $row['visits'] > 0 ? round(($row['bounces'] / $row['visits']) * 100, 1) : 0;
                    $conv_rate = $row['visits'] > 0 ? round(($row['leads'] / $row['visits']) * 100, 1) : ($row['leads'] > 0 ? 100 : 0);
                ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 font-bold text-gray-800"><?= htmlspecialchars($row['page']) ?></td>
                        <td class="py-3 px-4">
                            <?php if ($row['utm_source'] === 'Direct'): ?>
                                <span class="bg-gray-100 text-gray-500 px-2 py-1 rounded text-xs"><?= $row['utm_source'] ?></span>
                            <?php else: ?>
                                <span class="bg-green-50 text-green-700 px-2 py-1 border border-green-100 rounded text-xs font-medium"><?= htmlspecialchars($row['utm_source']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4">
                            <?php if ($row['utm_campaign'] === 'None'): ?>
                                <span class="text-gray-300 text-xs">-</span>
                            <?php else: ?>
                                <span class="bg-blue-50 text-blue-700 px-2 py-1 border border-blue-100 rounded text-xs font-medium"><?= htmlspecialchars($row['utm_campaign']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4 text-center font-bold text-gray-700"><?= number_format($row['visits']) ?></td>
                        <td class="py-3 px-4 text-center" dir="ltr">
                            <span class="inline-block px-2 py-1 rounded text-xs font-bold <?= $bounce_rate > 70 ? 'bg-red-50 text-red-600' : ($bounce_rate > 40 ? 'bg-yellow-50 text-yellow-600' : 'bg-green-50 text-green-600') ?>">
                                %<?= $bounce_rate ?>
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center font-bold text-gray-700"><?= number_format($row['leads']) ?></td>
                        <td class="py-3 px-4 text-center" dir="ltr">
                            <span class="inline-block px-2 py-1 rounded text-xs font-bold <?= $conv_rate > 5 ? 'bg-green-50 text-green-600 border border-green-200 shadow-sm' : ($conv_rate > 0 ? 'bg-blue-50 text-blue-600' : 'bg-gray-50 text-gray-400') ?>">
                                %<?= $conv_rate ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($analysis_data)): ?>
                    <tr><td colspan="7" class="py-12 text-center text-gray-400"><i class="fa-solid fa-chart-line text-4xl mb-3 block"></i><?= $lang === 'fa' ? 'دیتایی برای نمایش وجود ندارد.' : 'No data available.' ?></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
