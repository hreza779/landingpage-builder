<?php
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
$base_url = $protocol . "://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['REQUEST_URI']), '/');
?>
<!DOCTYPE html>
<html lang="fa" dir="<?= $dir ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('dashboard_page_title') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;700;900&display=swap');
        body { font-family: <?= $lang === 'fa' ? "'Vazirmatn', sans-serif" : "'Inter', sans-serif" ?>; background-color: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
        .file-upload-wrapper:hover { border-color: #16A34A; background-color: #f0fdf4; }
    </style>
</head>
<body class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-lg flex flex-col z-20 hidden md:flex shrink-0">
        <div class="p-6 border-b border-gray-100 text-center">
            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                <i class="fa-solid fa-rocket text-xl"></i>
            </div>
            <h1 class="font-black text-xl text-gray-800"><?= __('app_title') ?></h1>
            <p class="text-xs text-gray-400 mt-1"><?= __('app_desc') ?></p>
        </div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="?tab=stats" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-colors <?= $tab === 'stats' ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50' ?>">
                <i class="fa-solid fa-chart-pie w-5"></i>
                <?= __('tab_stats') ?>
            </a>
            <a href="?tab=landings" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-colors <?= $tab === 'landings' ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50' ?>">
                <i class="fa-solid fa-layer-group w-5"></i>
                <?= __('tab_landings') ?>
            </a>
            <a href="?tab=campaigns" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-colors <?= $tab === 'campaigns' ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50' ?>">
                <i class="fa-solid fa-bullhorn w-5"></i>
                <?= __('tab_campaigns') ?>
            </a>
            <a href="?tab=leads" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-colors <?= $tab === 'leads' ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50' ?>">
                <i class="fa-solid fa-address-book w-5"></i>
                <?= __('tab_leads') ?>
            </a>
            <a href="?tab=media" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-colors <?= $tab === 'media' ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50' ?>">
                <i class="fa-solid fa-images w-5"></i>
                <?= __('tab_media') ?>
            </a>
        </nav>
                <div class="p-4 border-t border-gray-100 flex justify-center gap-4">
            <a href="?lang=en" class="<?= $lang === 'en' ? 'text-green-600 font-bold' : 'text-gray-400 hover:text-gray-600' ?> text-sm transition-colors">EN</a>
            <span class="text-gray-300">|</span>
            <a href="?lang=fa" class="<?= $lang === 'fa' ? 'text-green-600 font-bold' : 'text-gray-400 hover:text-gray-600' ?> text-sm transition-colors">FA</a>
        </div>
        <div class="p-4 border-t border-gray-100">
            <a href="?logout=1" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-red-500 hover:bg-red-50 transition-colors">
                <i class="fa-solid fa-arrow-right-from-bracket w-5"></i>
                <?= __('logout') ?>
            </a>
        </div>
    </aside>

    <!-- Mobile Menu -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] z-50 flex justify-around p-3 border-t border-gray-100">
        <a href="?tab=stats" class="flex flex-col items-center gap-1 <?= $tab === 'stats' ? 'text-green-600' : 'text-gray-400' ?>">
            <i class="fa-solid fa-chart-pie text-xl"></i>
            <span class="text-[10px] font-bold"><?= __('tab_stats') ?></span>
        </a>
        <a href="?tab=landings" class="flex flex-col items-center gap-1 <?= $tab === 'landings' ? 'text-green-600' : 'text-gray-400' ?>">
            <i class="fa-solid fa-layer-group text-xl"></i>
            <span class="text-[10px] font-bold"><?= __('tab_landings') ?></span>
        </a>
        <a href="?tab=campaigns" class="flex flex-col items-center gap-1 <?= $tab === 'campaigns' ? 'text-green-600' : 'text-gray-400' ?>">
            <i class="fa-solid fa-bullhorn text-xl"></i>
            <span class="text-[10px] font-bold"><?= __('tab_campaigns') ?></span>
        </a>
        <a href="?tab=leads" class="flex flex-col items-center gap-1 <?= $tab === 'leads' ? 'text-green-600' : 'text-gray-400' ?>">
            <i class="fa-solid fa-address-book text-xl"></i>
            <span class="text-[10px] font-bold"><?= __('tab_leads') ?></span>
        </a>
        <a href="?tab=media" class="flex flex-col items-center gap-1 <?= $tab === 'media' ? 'text-green-600' : 'text-gray-400' ?>">
            <i class="fa-solid fa-images text-xl"></i>
            <span class="text-[10px] font-bold"><?= __('tab_media') ?></span>
        </a>
        <a href="?logout=1" class="flex flex-col items-center gap-1 text-red-400">
            <i class="fa-solid fa-power-off text-xl"></i>
            <span class="text-[10px] font-bold"><?= __('logout') ?></span>
        </a>
    </div>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden relative">
        <header class="bg-white/80 backdrop-blur-md border-b border-gray-100 p-6 flex justify-between items-center z-10 shrink-0">
            <h2 class="text-2xl font-bold text-gray-800">
                <?php
                    if ($tab === 'stats') echo __('tab_stats_title');
                    elseif ($tab === 'landings') echo __('tab_landings_title');
                    elseif ($tab === 'campaigns') echo __('tab_campaigns_title');
                    elseif ($tab === 'leads') echo __('tab_leads_title');
                    else echo __('tab_media_title');
                ?>
            </h2>
            <div class="text-sm font-medium text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                <?= __('portable_version') ?>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-4 md:p-8 custom-scrollbar pb-24 md:pb-8">
            
            <?php if(isset($db_error)): ?>
                <div class="bg-red-100 border-r-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 shadow-sm">
                    <strong><?= __('db_error') ?></strong> <?= htmlspecialchars($db_error) ?>
                </div>
            <?php endif; ?>

