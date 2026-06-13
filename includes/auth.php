<?php
$admin_password = 'admin'; 

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: stats.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    if ($_POST['password'] === $admin_password) {
        $_SESSION['logged_in'] = true;
        header("Location: stats.php");
        exit;
    } else {
        $error = __('wrong_password');
    }
}

function getDeviceInfo($ua) {
    $ua = strtolower($ua);
    $device = 'desktop';
    $icon = '💻';
    if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', $ua)) {
        $device = 'tablet';
        $icon = '📱';
    } elseif (preg_match('/(mobile|iphone|ipod|android|phone|iemobile)/i', $ua)) {
        $device = 'mobile';
        $icon = '📱';
    }
    
    $os = __('unknown_device');
    if (strpos($ua, 'windows') !== false) $os = 'Windows';
    elseif (strpos($ua, 'mac os x') !== false) $os = 'macOS';
    elseif (strpos($ua, 'android') !== false) $os = 'Android';
    elseif (strpos($ua, 'iphone os') !== false) $os = 'iOS';
    elseif (strpos($ua, 'linux') !== false) $os = 'Linux';
    
    return ['device' => $device, 'icon' => $icon, 'os' => $os];
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    ?>
    <!DOCTYPE html>
    <html lang="fa" dir="<?= $dir ?>">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= __('login_page_title') ?></title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;700;900&display=swap');
            body { font-family: <?= $lang === 'fa' ? "'Vazirmatn', sans-serif" : "'Inter', sans-serif" ?>; }
        </style>
    </head>
    <body class="bg-gray-50 flex items-center justify-center h-screen">
        <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-sm border border-gray-100 text-center">
            <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-6"><?= __('login_title') ?></h2>
            <?php if(isset($error)): ?>
                <div class="bg-red-100 text-red-600 p-3 rounded-lg mb-4 text-sm font-medium"><?= __($error) ?></div>
                
            <?php endif; ?>
            <form method="POST">
                <input type="password" name="password" placeholder="<?= __('password_placeholder') ?>" class="w-full px-4 py-3 border border-gray-200 rounded-xl mb-4 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-center" required>
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl transition-colors"><?= __('login_btn') ?></button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}
