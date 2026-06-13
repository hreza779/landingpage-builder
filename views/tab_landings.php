<?php
            $stmt = $pdo->query("SELECT page, COUNT(*) as total, SUM(CASE WHEN date(created_at) = date('now') THEN 1 ELSE 0 END) as today FROM visits GROUP BY page");
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $pages_db_stats[$row['page']] = $row;
            }
$html_files = [];
if ($tab === 'landings') {
    $files = glob("*.html");
    foreach ($files as $file) {
        $page_key = str_replace('.html', '', $file);
        if ($page_key === 'index' || $page_key === '') $page_key = 'index';
        $html_files[] = [
            'filename' => $file,
            'key' => $page_key,
            'stats' => $pages_db_stats[$page_key] ?? ['total' => 0, 'today' => 0]
        ];
    }
}
?>
                
                <?php if(isset($create_error)): ?>
                    <div class="bg-red-100 border-r-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 shadow-sm">
                        <strong><?= __('msg') ?></strong> <?= htmlspecialchars($create_error) ?>
                    </div>
                <?php endif; ?>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="flex flex-col gap-1">
                            <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2">
                                <i class="fa-regular fa-folder-open text-yellow-500"></i> <?= __('landing_files_list') ?>
                            </h3>
                            <p class="text-xs text-gray-500"><?= __('current_folder') ?> <span class="font-mono bg-gray-50 px-2 rounded border border-gray-200">/landings/</span></p>
                        </div>
                        
                        <button onclick="document.getElementById('create-modal').classList.remove('hidden')" class="bg-green-600 hover:bg-green-500 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-all shadow-md flex items-center gap-2 shrink-0">
                            <i class="fa-solid fa-plus"></i> <?= __('new_landing') ?>
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-right">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="py-4 px-6 text-gray-500 font-medium w-1/3"><?= __('col_filename') ?></th>
                                    <th class="py-4 px-6 text-gray-500 font-medium text-center"><?= __('col_total_visits') ?></th>
                                    <th class="py-4 px-6 text-gray-500 font-medium text-center"><?= __('col_today_visits') ?></th>
                                    <th class="py-4 px-6 text-gray-500 font-medium text-left"><?= __('col_actions') ?></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach($html_files as $file): ?>
                                    <tr class="hover:bg-green-50/50 transition-colors group">
                                        <td class="py-4 px-6">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-orange-100 text-orange-500 rounded-lg flex items-center justify-center shrink-0">
                                                    <i class="fa-brands fa-html5 text-xl"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-gray-800 text-base" dir="ltr"><?= htmlspecialchars($file['filename']) ?></h4>
                                                    <p class="text-xs text-gray-400 mt-1">Key: <?= htmlspecialchars($file['key']) ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <span class="inline-block bg-gray-100 text-gray-700 font-bold px-3 py-1 rounded-lg min-w-[3rem]">
                                                <?= number_format($file['stats']['total'] ?? 0) ?>
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <span class="inline-block <?= ($file['stats']['today'] ?? 0) > 0 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' ?> font-bold px-3 py-1 rounded-lg min-w-[3rem]">
                                                <?= number_format($file['stats']['today'] ?? 0) ?>
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-left">
                                            <div class="flex items-center justify-end gap-2">
                                                <?php 
                                                    $clean_name = str_replace('.html', '', $file['filename']);
                                                    $target_url = $base_url . '/' . $clean_name;
                                                ?>
                                                <a href="editor.php?file=<?= urlencode($file['filename']) ?>" class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                                                    <i class="fa-solid fa-pen"></i>
                                                    <span class="hidden xl:inline"><?= __('edit') ?></span>
                                                </a>
                                                <button onclick="copyToClipboard('<?= $target_url ?>', this)" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                                                    <i class="fa-regular fa-copy"></i>
                                                    <span class="hidden xl:inline"><?= __('copy_link') ?></span>
                                                </button>
                                                <a href="<?= $target_url ?>" target="_blank" class="bg-green-100 hover:bg-green-200 text-green-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                    <span class="hidden xl:inline"><?= __('view') ?></span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                
                                <?php if(empty($html_files)): ?>
                                    <tr>
                                        <td colspan="4" class="py-12 text-center text-gray-400">
                                            <i class="fa-regular fa-folder-open text-4xl mb-3 block"></i>
                                            <?= __('no_html_files') ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

