<?php
        if ($tab === 'leads' || (isset($_GET['export']) && $_GET['export'] === 'leads')) {
            $leads = $pdo->query("SELECT * FROM leads ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

            if (isset($_GET['export']) && $_GET['export'] === 'leads') {
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=leads_' . date('Y-m-d') . '.csv');
                $output = fopen('php://output', 'w');
                
                fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
                fputcsv($output, ['ID', __('col_landing_name'), 'نام', __('col_mobile_number'), 'Source', 'Campaign', 'Medium', __('col_other_info'), __('col_ip'), __('col_reg_date')]);
                foreach ($leads as $lead) {
                    $details = json_decode($lead['details'], true);
                    $details_str = '';
                    if (is_array($details)) {
                        foreach ($details as $k => $v) $details_str .= "$k: $v | ";
                    }
                    fputcsv($output, [
                        $lead['id'],
                        $lead['page'],
                        $lead['name'],
                        $lead['phone'],
                        $lead['utm_source'] ?? '',
                        $lead['utm_campaign'] ?? '',
                        $lead['utm_medium'] ?? '',
                        trim($details_str, ' | '),
                        $lead['ip'],
                        $lead['created_at']
                    ]);
                }
                fclose($output);
                exit;
            }
        }
?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-[700px]">
                    <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 shrink-0">
                        <div class="flex flex-col gap-1">
                            <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2">
                                <i class="fa-solid fa-address-book text-blue-500"></i> <?= __('leads_list') ?>
                            </h3>
                            <p class="text-xs text-gray-500"><?= __('leads_desc') ?></p>
                        </div>
                        
                        <a href="?tab=leads&export=leads" class="bg-emerald-600 hover:bg-emerald-500 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-all shadow-md flex items-center gap-2 shrink-0">
                            <i class="fa-solid fa-file-csv"></i> <?= __('export_csv') ?>
                        </a>
                    </div>
                    
                    <div class="flex-1 overflow-auto custom-scrollbar p-0">
                        <table class="w-full text-right text-sm">
                            <thead class="bg-gray-50 sticky top-0 shadow-sm z-10">
                                <tr>
                                    <th class="py-3 px-4 text-gray-500 font-medium whitespace-nowrap"><?= __('col_reg_date') ?></th>
                                    <th class="py-3 px-4 text-gray-500 font-medium whitespace-nowrap"><?= __('col_landing_name') ?></th>
                                    <th class="py-3 px-4 text-gray-500 font-medium whitespace-nowrap"><?= __('col_name_mobile') ?></th>
                                    <th class="py-3 px-4 text-gray-500 font-medium whitespace-nowrap"><?= __('col_utm') ?></th>
                                    <th class="py-3 px-4 text-gray-500 font-medium"><?= __('col_details') ?></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php if(isset($leads)): foreach($leads as $l): ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="py-3 px-4 text-xs font-mono text-gray-500" dir="ltr"><?= $l['created_at'] ?></td>
                                        <td class="py-3 px-4 font-bold text-gray-800"><?= htmlspecialchars($l['page']) ?></td>
                                        <td class="py-3 px-4">
                                            <div class="font-bold text-gray-900"><?= $l['name'] ?: '<span class="text-gray-400 font-normal">' . __('unknown') . '</span>' ?></div>
                                            <div class="text-blue-600 font-mono text-xs mt-1" dir="ltr"><?= htmlspecialchars($l['phone']) ?></div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex flex-wrap gap-1">
                                                <?php if(!empty($l['utm_source'])): ?><span class="bg-green-50 text-green-700 px-2 py-0.5 rounded text-[10px] border border-green-100" title="Source">S: <?= htmlspecialchars($l['utm_source']) ?></span><?php endif; ?>
                                                <?php if(!empty($l['utm_campaign'])): ?><span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded text-[10px] border border-blue-100" title="Campaign">C: <?= htmlspecialchars($l['utm_campaign']) ?></span><?php endif; ?>
                                                <?php if(empty($l['utm_source']) && empty($l['utm_campaign'])): ?><span class="text-gray-300 text-xs">-</span><?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <?php 
                                                $details = json_decode($l['details'], true);
                                                if(is_array($details) && !empty($details)): 
                                            ?>
                                                <div class="flex flex-wrap gap-2 text-xs">
                                                    <?php foreach($details as $k => $v): ?>
                                                        <span class="bg-gray-100 border border-gray-200 px-2 py-1 rounded text-gray-600">
                                                            <strong class="text-gray-500"><?= htmlspecialchars($k) ?>:</strong> <?= htmlspecialchars($v) ?>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-gray-300 text-xs">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; endif; ?>
                                <?php if(empty($leads)): ?>
                                    <tr><td colspan="4" class="py-12 text-center text-gray-400"><i class="fa-regular fa-folder-open text-4xl mb-3 block"></i><?= __('no_leads') ?></td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

