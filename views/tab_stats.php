<?php
$total_visits = 0;
$today_visits = 0;
$recent_visits = [];
$top_sources = [];
$pages_db_stats = [];
$mobile_pct = 0;
$bounce_rate = 0;
$all_pages = [];

            $all_pages = $pdo->query("SELECT DISTINCT page FROM visits WHERE page != '' ORDER BY page")->fetchAll(PDO::FETCH_COLUMN);

            $where_clauses = ["1=1"];
            $params = [];

            if (!empty($_GET['start_date'])) {
                $where_clauses[] = "date(created_at) >= :start_date";
                $params[':start_date'] = $_GET['start_date'];
            }
            if (!empty($_GET['end_date'])) {
                $where_clauses[] = "date(created_at) <= :end_date";
                $params[':end_date'] = $_GET['end_date'];
            }
            if (!empty($_GET['filter_page'])) {
                $where_clauses[] = "page = :filter_page";
                $params[':filter_page'] = $_GET['filter_page'];
            }
            if (!empty($_GET['search'])) {
                $where_clauses[] = "(utm_source LIKE :search OR utm_campaign LIKE :search OR referrer LIKE :search OR ip LIKE :search)";
                $params[':search'] = '%' . $_GET['search'] . '%';
            }

            $where_sql = implode(" AND ", $where_clauses);

            $stmt = $pdo->prepare("SELECT COUNT(*) FROM visits WHERE $where_sql");
            $stmt->execute($params);
            $total_visits = $stmt->fetchColumn();

            $stmt = $pdo->prepare("SELECT COUNT(*) FROM visits WHERE $where_sql AND date(created_at) = date('now')");
            $stmt->execute($params);
            $today_visits = $stmt->fetchColumn();

            $stmt = $pdo->prepare("SELECT COUNT(*) FROM visits WHERE $where_sql AND is_bounced = 1");
            $stmt->execute($params);
            $bounced_visits = $stmt->fetchColumn();
            $bounce_rate = $total_visits > 0 ? round(($bounced_visits / $total_visits) * 100) : 0;

            $stmt = $pdo->prepare("SELECT utm_source, COUNT(*) as c FROM visits WHERE $where_sql AND utm_source != '' GROUP BY utm_source ORDER BY c DESC LIMIT 5");
            $stmt->execute($params);
            $top_sources = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $page = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
            $per_page = 50;
            $offset = ($page - 1) * $per_page;

            $stmt = $pdo->prepare("SELECT * FROM visits WHERE $where_sql ORDER BY created_at DESC LIMIT $per_page OFFSET $offset");
            $stmt->execute($params);
            $recent_visits = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $total_pages = $total_visits > 0 ? ceil($total_visits / $per_page) : 1;

            // Sessions map for heatmap
            $sessions_map = [];
            $aggregate_clicks = [];
            if (!empty($recent_visits)) {
                try {
                    $visit_ids = array_column($recent_visits, 'id');
                    $placeholders = implode(',', array_fill(0, count($visit_ids), '?'));
                    $stmt_s = $pdo->prepare("SELECT visit_id, clicks, max_scroll, duration FROM sessions WHERE visit_id IN ($placeholders)");
                    $stmt_s->execute($visit_ids);
                    while ($row = $stmt_s->fetch(PDO::FETCH_ASSOC)) {
                        $sessions_map[$row['visit_id']] = $row;
                        $c = json_decode($row['clicks'], true);
                        if (is_array($c)) $aggregate_clicks = array_merge($aggregate_clicks, $c);
                    }
                } catch (Exception $e) {}
            }

            if ($total_visits > 0) {
                $mobile_count = 0;
                $stmt_ua = $pdo->prepare("SELECT user_agent FROM visits WHERE $where_sql");
                $stmt_ua->execute($params);
                while ($row = $stmt_ua->fetch(PDO::FETCH_ASSOC)) {
                    $ua = strtolower($row['user_agent']);
                    if (preg_match('/(mobile|iphone|ipod|android|phone|iemobile|tablet|ipad)/i', $ua)) {
                        $mobile_count++;
                    }
                }
                $mobile_pct = round(($mobile_count / $total_visits) * 100);
            }
?>
                <!-- Filters Area -->
                <form method="GET" class="bg-white rounded-2xl p-4 md:p-5 shadow-sm border border-gray-100 mb-6 flex flex-col lg:flex-row gap-4 lg:items-end">
                    <input type="hidden" name="tab" value="stats">
                    
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-gray-500 mb-1.5"><?= __('search_stats') ?></label>
                        <div class="relative">
                            <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="<?= __('search_placeholder') ?>" class="w-full text-sm border border-gray-200 rounded-xl pl-3 pr-10 py-2.5 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                            <i class="fa-solid fa-search absolute right-3.5 top-3.5 text-gray-400"></i>
                        </div>
                    </div>

                    <div class="w-full lg:w-48">
                        <label class="block text-xs font-bold text-gray-500 mb-1.5"><?= __('filter_page') ?></label>
                        <select name="filter_page" class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none bg-white cursor-pointer">
                            <option value=""><?= __('all_pages') ?></option>
                            <?php foreach($all_pages as $p): ?>
                                <option value="<?= htmlspecialchars($p) ?>" <?= (isset($_GET['filter_page']) && $_GET['filter_page'] === $p) ? 'selected' : '' ?>><?= htmlspecialchars($p) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="w-full sm:w-1/2 lg:w-36">
                        <label class="block text-xs font-bold text-gray-500 mb-1.5"><?= __('from_date') ?></label>
                        <input type="date" name="start_date" value="<?= htmlspecialchars($_GET['start_date'] ?? '') ?>" class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none" dir="ltr">
                    </div>

                    <div class="w-full sm:w-1/2 lg:w-36">
                        <label class="block text-xs font-bold text-gray-500 mb-1.5"><?= __('to_date') ?></label>
                        <input type="date" name="end_date" value="<?= htmlspecialchars($_GET['end_date'] ?? '') ?>" class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none" dir="ltr">
                    </div>

                    <div class="flex gap-2 w-full lg:w-auto">
                        <button type="submit" class="flex-1 lg:flex-none bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-colors shadow-md shadow-blue-600/20 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-filter"></i> <?= __('apply') ?>
                        </button>
                        <?php if(!empty($_GET['search']) || !empty($_GET['filter_page']) || !empty($_GET['start_date']) || !empty($_GET['end_date'])): ?>
                            <a href="?tab=stats" class="flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2.5 rounded-xl text-sm font-bold transition-colors" title="<?= __('clear_filters') ?>">
                                <i class="fa-solid fa-times"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </form>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-1"><?= __('visits_period') ?></p>
                            <h3 class="text-3xl font-black text-gray-800"><?= number_format($total_visits) ?></h3>
                        </div>
                        <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center text-xl">
                            <i class="fa-solid fa-eye"></i>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-l from-green-500 to-emerald-400 rounded-2xl p-6 shadow-md text-white flex items-center justify-between">
                        <div>
                            <p class="text-green-50 text-sm font-medium mb-1"><?= __('visits_today') ?></p>
                            <h3 class="text-3xl font-black"><?= number_format($today_visits) ?></h3>
                        </div>
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center text-xl">
                            <i class="fa-solid fa-calendar-day"></i>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-1"><?= __('total_landings_count') ?></p>
                            <h3 class="text-3xl font-black text-gray-800"><?= count(glob("*.html")) ?></h3>
                        </div>
                        <div class="w-12 h-12 bg-purple-50 text-purple-500 rounded-full flex items-center justify-center text-xl">
                            <i class="fa-regular fa-file-code"></i>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-center">
                        <div class="flex justify-between items-center mb-2">
                            <p class="text-gray-500 text-sm font-medium"><?= __('mobile_users') ?></p>
                            <h3 class="text-2xl font-black text-gray-800" dir="ltr">%<?= $mobile_pct ?></h3>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 mb-2">
                            <div class="bg-indigo-500 h-2 rounded-full" style="width: <?= $mobile_pct ?>%"></div>
                        </div>
                        <p class="text-[10px] text-gray-400"><?= __('touch_devices') ?></p>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-center">
                        <div class="flex justify-between items-center mb-2">
                            <p class="text-gray-500 text-sm font-medium"><?= $lang === 'fa' ? 'نرخ پرش' : 'Bounce Rate' ?></p>
                            <h3 class="text-2xl font-black text-gray-800" dir="ltr">%<?= $bounce_rate ?></h3>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 mb-2">
                            <div class="<?= $bounce_rate > 70 ? 'bg-red-500' : ($bounce_rate > 40 ? 'bg-yellow-500' : 'bg-green-500') ?> h-2 rounded-full" style="width: <?= $bounce_rate ?>%"></div>
                        </div>
                        <p class="text-[10px] text-gray-400"><?= $lang === 'fa' ? 'خروج زیر ۴ ثانیه' : 'Left under 4s' ?></p>
                    </div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
                    <!-- Top UTM Sources -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 xl:col-span-1 h-fit">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-chart-simple text-green-500"></i> <?= __('top_sources') ?>
                        </h3>
                        <div class="space-y-4">
                            <?php foreach($top_sources as $src): ?>
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="font-medium text-gray-700"><?= htmlspecialchars($src['utm_source']) ?></span>
                                        <span class="font-bold text-gray-900"><?= number_format($src['c']) ?></span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-2">
                                        <?php $pct = $total_visits > 0 ? ($src['c'] / $total_visits) * 100 : 0; ?>
                                        <div class="bg-green-500 h-2 rounded-full" style="width: <?= $pct ?>%"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <?php if(empty($top_sources)): ?>
                                <p class="text-sm text-gray-400 text-center py-4"><?= __('no_data') ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Recent Visits Table -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-0 xl:col-span-3 flex flex-col h-[600px]">
                        <div class="p-6 border-b border-gray-100 shrink-0 flex justify-between items-center">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                <i class="fa-solid fa-list text-blue-500"></i> <?= __('recent_visits') ?>
                            </h3>
                            <span class="bg-gray-100 text-gray-500 text-xs px-3 py-1 rounded-full font-bold">
                                <?= count($recent_visits) ?> <?= __('records') ?>
                            </span>
                        </div>
                        <div class="flex-1 overflow-auto custom-scrollbar p-0">
                            <table class="w-full text-right text-sm">
                                <thead class="bg-gray-50 sticky top-0 shadow-sm z-10">
                                    <tr>
                                        <th class="py-3 px-4 text-gray-500 font-medium whitespace-nowrap"><?= __('col_page_time') ?></th>
                                        <th class="py-3 px-4 text-gray-500 font-medium whitespace-nowrap"><?= __('col_traffic') ?></th>
                                        <th class="py-3 px-4 text-gray-500 font-medium whitespace-nowrap"><?= __('col_referrer') ?></th>
                                        <th class="py-3 px-4 text-gray-500 font-medium whitespace-nowrap"><?= __('col_device') ?></th>
                                        <th class="py-3 px-4 text-gray-500 font-medium whitespace-nowrap"><?= __('col_ip') ?></th>
                                        <th class="py-3 px-4 text-gray-500 font-medium whitespace-nowrap text-center">🎬</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <?php foreach($recent_visits as $v): ?>
                                        <?php $dev = getDeviceInfo($v['user_agent']); ?>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="py-3 px-4">
                                                <div class="font-bold text-gray-800"><?= htmlspecialchars($v['page']) ?></div>
                                                <div class="text-[10px] text-gray-400" dir="ltr"><?= date('y/m/d H:i', strtotime($v['created_at'])) ?></div>
                                            </td>
                                            <td class="py-3 px-4">
                                                <div class="flex flex-wrap gap-1">
                                                    <?php if($v['utm_source']): ?><span class="bg-green-50 text-green-700 px-2 py-0.5 rounded text-[10px] border border-green-100" title="Source">S: <?= htmlspecialchars($v['utm_source']) ?></span><?php endif; ?>
                                                    <?php if($v['utm_campaign']): ?><span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded text-[10px] border border-blue-100" title="Campaign">C: <?= htmlspecialchars($v['utm_campaign']) ?></span><?php endif; ?>
                                                    <?php if(!$v['utm_source'] && !$v['utm_campaign']): ?><span class="text-gray-300 text-xs">-</span><?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4">
                                                <?php if($v['referrer']): ?>
                                                    <?php $ref_host = parse_url($v['referrer'], PHP_URL_HOST) ?? $v['referrer']; ?>
                                                    <span class="text-xs text-blue-500 truncate max-w-[120px] inline-block" title="<?= htmlspecialchars($v['referrer']) ?>">
                                                        <?= htmlspecialchars($ref_host) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-gray-400 text-[10px] bg-gray-100 px-2 py-1 rounded"><?= __('direct') ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="py-3 px-4">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-lg"><?= $dev['icon'] ?></span>
                                                    <div class="flex flex-col">
                                                        <span class="text-gray-700 font-bold text-xs"><?= $dev['device'] ?></span>
                                                        <span class="text-gray-400 text-[10px]"><?= $dev['os'] ?></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4 text-gray-400 font-mono text-xs" dir="ltr"><?= htmlspecialchars($v['ip']) ?></td>
                                            <td class="py-3 px-4 text-center">
                                                <?php if (isset($sessions_map[$v['id']])): ?>
                                                    <a
                                                        href="?heatmap_view=<?= $v['id'] ?>"
                                                        target="_blank"
                                                        title="<?= $lang === 'fa' ? 'نمایش نقشه کلیک' : 'View Click Heatmap' ?>"
                                                        class="w-7 h-7 rounded-lg bg-purple-50 hover:bg-purple-100 text-purple-600 flex items-center justify-center mx-auto transition-colors text-sm">
                                                        <i class="fa-solid fa-crosshairs"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-gray-200 text-xs">—</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if(empty($recent_visits)): ?>
                                        <tr><td colspan="6" class="py-8 text-center text-gray-400"><?= __('no_visits') ?></td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if (isset($total_pages) && $total_pages > 1): ?>
                        <div class="p-4 border-t border-gray-100 flex items-center justify-between shrink-0 bg-gray-50/50 rounded-b-2xl">
                            <span class="text-sm text-gray-500 font-medium">
                                <?= $lang === 'fa' ? 'صفحه' : 'Page' ?> <b class="text-gray-800"><?= $page ?></b> <?= $lang === 'fa' ? 'از' : 'of' ?> <b class="text-gray-800"><?= $total_pages ?></b>
                            </span>
                            <div class="flex gap-1" dir="ltr">
                                <?php 
                                    $query_params = $_GET;
                                    unset($query_params['p']);
                                    $qs = http_build_query($query_params);
                                    $qs = $qs ? '?' . $qs . '&' : '?';
                                ?>
                                <?php if ($page > 1): ?>
                                    <a href="<?= $qs ?>p=<?= $page - 1 ?>" class="px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-white bg-gray-50 text-sm transition-colors text-gray-600">&laquo;</a>
                                <?php endif; ?>
                                
                                <?php
                                $start_p = max(1, $page - 2);
                                $end_p = min($total_pages, $page + 2);
                                for ($i = $start_p; $i <= $end_p; $i++):
                                ?>
                                    <a href="<?= $qs ?>p=<?= $i ?>" class="px-3 py-1.5 border <?= $i === $page ? 'bg-blue-50 text-blue-600 font-bold border-blue-200 shadow-sm' : 'border-gray-200 hover:bg-white bg-gray-50 text-gray-600 transition-colors' ?> text-sm rounded-lg"><?= $i ?></a>
                                <?php endfor; ?>

                                <?php if ($page < $total_pages): ?>
                                    <a href="<?= $qs ?>p=<?= $page + 1 ?>" class="px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-white bg-gray-50 text-sm transition-colors text-gray-600">&raquo;</a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

