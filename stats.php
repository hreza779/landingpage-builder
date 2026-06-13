<?php

session_start();

if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'fa'])) {
    $_SESSION['lang'] = $_GET['lang'];
    $current_url = preg_replace('/([&?])lang=[^&]*&?/', '', $_SERVER['REQUEST_URI']);
    $current_url = rtrim($current_url, '?&');
    header("Location: " . ($current_url ?: '?tab=stats'));
    exit;
}
$lang = $_SESSION['lang'] ?? 'en';
$dir = $lang === 'fa' ? 'rtl' : 'ltr';

$i18n = [
    'en' => [
        'login_title' => 'Admin Panel Login',
        'password_placeholder' => 'Enter password...',
        'login_btn' => 'Login to System',
        'wrong_password' => 'Incorrect password!',
        'app_title' => 'Homplus Landing',
        'app_desc' => 'Centralized Landing Pages',
        'portable_version' => 'Portable Version',
        'tab_stats' => 'Overview',
        'tab_landings' => 'My Landings',
        'tab_leads' => 'Forms & Leads',
        'tab_media' => 'File Manager',
        'logout' => 'Logout',
        'search_placeholder' => 'Search UTM, IP, Referrer...',
        'search_stats' => 'Search Data',
        'filter_page' => 'Landing (Page)',
        'all_pages' => 'All Landings',
        'from_date' => 'From Date',
        'to_date' => 'To Date',
        'apply' => 'Apply',
        'clear_filters' => 'Clear Filters',
        'visits_period' => 'Visits (Period)',
        'visits_today' => 'Visits Today',
        'total_landings_count' => 'Landings Count',
        'mobile_users' => 'Mobile Users',
        'touch_devices' => 'Touch devices share',
        'top_sources' => 'Top Sources',
        'no_data' => 'No data found.',
        'recent_visits' => 'Recent Visits',
        'records' => 'Records',
        'no_visits' => 'No visits found.',
        'col_page_time' => 'Page / Time',
        'col_traffic' => 'Traffic (UTM)',
        'col_referrer' => 'Referrer',
        'col_device' => 'Device & OS',
        'col_ip' => 'IP Address',
        'direct' => 'Direct',
        'landing_files_list' => 'Landing Files List',
        'current_folder' => 'Current folder on host:',
        'new_landing' => 'New Landing',
        'col_filename' => 'File Name',
        'col_total_visits' => 'Total Visits',
        'col_today_visits' => 'Today Visits',
        'col_actions' => 'Actions',
        'edit' => 'Edit',
        'copy_link' => 'Copy Link',
        'copy_source_link' => 'Copy Link (Source)',
        'view' => 'View',
        'no_html_files' => 'No HTML files found!',
        'leads_list' => 'Leads & Forms List',
        'leads_desc' => 'View submitted forms from all landing pages',
        'export_csv' => 'Export CSV',
        'col_reg_date' => 'Date',
        'col_name_mobile' => 'Name & Mobile',
        'col_utm' => 'UTM Source',
        'col_details' => 'Other Details',
        'unknown' => 'Unknown',
        'no_leads' => 'No forms have been submitted yet.',
        'upload_new' => 'Upload New File',
        'allowed_formats' => 'Allowed formats:',
        'upload_btn' => 'Upload',
        'existing_files' => 'Existing Files',
        'confirm_del' => 'Are you sure you want to delete this file permanently?',
        'no_files' => 'No files uploaded yet.',
        'create_new_landing' => 'Create New Landing',
        'filename_en' => 'File Name (English)',
        'filename_hint' => 'Only English letters, numbers, hyphens, and underscores are allowed.',
        'continue_editor' => 'Continue to Editor',
        'copied' => 'Copied',
        'file_exists' => 'A file with this name already exists!',
        'invalid_name' => 'Invalid file name.',
        'upload_success' => 'File uploaded successfully.',
        'upload_err' => 'Error uploading file.',
        'invalid_format' => 'Invalid file format!',
        'no_file_selected' => 'No file selected.',
        'delete_success' => 'File deleted successfully.',
        'db_error' => 'Database Error:',
        'msg' => 'Message:',
        'tab_stats_title' => 'Dashboard Overview',
        'tab_landings_title' => 'Landing Pages',
        'tab_media_title' => 'File Manager',
        'tab_leads_title' => 'Leads & Forms',
        'login_page_title' => 'Dashboard Login',
        'dashboard_page_title' => 'Landing Management Dashboard',
        'lang_fa' => 'فارسی',
        'lang_en' => 'English'
    ],
    'fa' => [
        'login_title' => 'ورود به پنل مدیریت',
        'password_placeholder' => 'رمز عبور خود را وارد کنید...',
        'login_btn' => 'ورود به سیستم',
        'wrong_password' => 'رمز عبور اشتباه است!',
        'app_title' => 'هوم پلاس لندینگ',
        'app_desc' => 'مدیریت متمرکز صفحات فرود',
        'portable_version' => 'نسخه پرتابل',
        'tab_stats' => 'آمار کلی',
        'tab_landings' => 'لندینگ‌های من',
        'tab_leads' => 'فرم‌ها و درخواست‌ها',
        'tab_media' => 'مدیریت فایل‌ها',
        'logout' => 'خروج از سیستم',
        'search_placeholder' => 'جستجوی UTM, آی‌پی, ارجاع‌دهنده...',
        'search_stats' => 'جستجو در آمار',
        'filter_page' => 'لندینگ (صفحه)',
        'all_pages' => 'همه لندینگ‌ها',
        'from_date' => 'از تاریخ',
        'to_date' => 'تا تاریخ',
        'apply' => 'اعمال',
        'clear_filters' => 'حذف فیلترها',
        'visits_period' => 'بازدید در این بازه',
        'visits_today' => 'بازدید امروز (بازه)',
        'total_landings_count' => 'تعداد لندینگ‌ها',
        'mobile_users' => 'کاربران موبایل',
        'touch_devices' => 'سهم دستگاه‌های لمسی',
        'top_sources' => 'برترین منابع',
        'no_data' => 'دیتا یافت نشد.',
        'recent_visits' => 'لیست بازدیدها',
        'records' => 'رکورد',
        'no_visits' => 'هیچ بازدیدی یافت نشد.',
        'col_page_time' => 'صفحه / زمان',
        'col_traffic' => 'ترافیک (UTM)',
        'col_referrer' => 'ارجاع‌دهنده',
        'col_device' => 'دستگاه و OS',
        'col_ip' => 'آی‌پی',
        'direct' => 'مستقیم (Direct)',
        'landing_files_list' => 'لیست فایل‌های لندینگ',
        'current_folder' => 'آدرس پوشه فعلی روی هاست:',
        'new_landing' => 'لندینگ جدید',
        'col_filename' => 'نام فایل (لندینگ)',
        'col_total_visits' => 'بازدید کل',
        'col_today_visits' => 'بازدید امروز',
        'col_actions' => 'عملیات',
        'edit' => 'ویرایش',
        'copy_link' => 'کپی لینک',
        'copy_source_link' => 'کپی لینک (لینک برای سورس کپی می‌شود)',
        'view' => 'مشاهده',
        'no_html_files' => 'هیچ فایل HTML در این پوشه یافت نشد!',
        'leads_list' => 'لیست درخواست‌ها و سرنخ‌ها (Leads)',
        'leads_desc' => 'مشاهده اطلاعات فرم‌های پر شده در تمامی لندینگ‌ها',
        'export_csv' => 'خروجی اکسل (CSV)',
        'col_reg_date' => 'تاریخ ثبت',
        'col_name_mobile' => 'نام و موبایل',
        'col_utm' => 'منبع (UTM)',
        'col_details' => 'سایر اطلاعات (جزئیات فرم)',
        'unknown' => 'نامشخص',
        'no_leads' => 'تا کنون هیچ فرمی ثبت نشده است.',
        'upload_new' => 'آپلود فایل جدید',
        'allowed_formats' => 'فرمت‌های مجاز:',
        'upload_btn' => 'آپلود',
        'existing_files' => 'فایل‌های موجود',
        'confirm_del' => 'آیا از حذف دائم این فایل مطمئن هستید؟',
        'no_files' => 'هیچ فایلی هنوز آپلود نشده است.',
        'create_new_landing' => 'ساخت لندینگ جدید',
        'filename_en' => 'نام فایل (انگلیسی)',
        'filename_hint' => 'توجه: فقط حروف انگلیسی، عدد، خط تیره (-) و آندرلاین مجاز است.',
        'continue_editor' => 'ادامه و ورود به ویرایشگر',
        'copied' => 'کپی شد',
        'file_exists' => 'فایلی با این نام از قبل وجود دارد!',
        'invalid_name' => 'نام فایل نامعتبر است.',
        'upload_success' => 'فایل با موفقیت آپلود شد.',
        'upload_err' => 'خطا در آپلود فایل (مشکل پرمیشن پوشه).',
        'invalid_format' => 'فرمت فایل مجاز نیست!',
        'no_file_selected' => 'فایلی انتخاب نشده یا حجم آن بیش از حد مجاز سرور است.',
        'delete_success' => 'فایل با موفقیت حذف شد.',
        'db_error' => 'خطای دیتابیس:',
        'msg' => 'پیام:',
        'tab_stats_title' => 'داشبورد آمار کلی',
        'tab_landings_title' => 'مدیریت صفحات فرود',
        'tab_media_title' => 'فایل منیجر اختصاصی',
        'tab_leads_title' => 'درخواست‌ها و فرم‌ها',
        'login_page_title' => 'ورود به داشبورد',
        'dashboard_page_title' => 'داشبورد مدیریت لندینگ‌ها',
        'lang_fa' => 'فارسی',
        'lang_en' => 'English'
    ]
];

function __($key) {
    global $i18n, $lang;
    return $i18n[$lang][$key] ?? $key;
}

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

$upload_dir = __DIR__ . '/assets/uploads';
if (!is_dir($upload_dir)) {
    @mkdir($upload_dir, 0755, true);
    @file_put_contents($upload_dir . '/.htaccess', "<FilesMatch \"\\.(php|phtml|php3|php4|php5|pl|py|cgi|sh|exe|jsp|asp|aspx)$\">\nOrder allow,deny\nDeny from all\n</FilesMatch>");
}

$media_msg = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    if ($_POST['action'] === 'create_landing') {
        $new_file = trim($_POST['filename']);
        $new_file = preg_replace('/[^a-zA-Z0-9_-]/', '', $new_file);
        if (!empty($new_file)) {
            $new_file .= '.html';
            $filepath = __DIR__ . '/' . $new_file;
            if (!file_exists($filepath)) {
                                $b_title = __('new_landing');
                $b_welcome = $lang === 'fa' ? 'به لندینگ جدید خوش آمدید' : 'Welcome to your New Landing';
                $b_desc = $lang === 'fa' ? 'این یک صفحه خام است. با کلیک روی تب (کد) از طریق ویرایشگر، کدهای خود را جایگزین کنید.' : 'This is a blank page. Switch to the Edit tab in the editor to replace this with your custom code.';
                $b_sending = $lang === 'fa' ? 'در حال ارسال...' : 'Sending...';
                $b_success = $lang === 'fa' ? 'اطلاعات با موفقیت ثبت شد.' : 'Data submitted successfully.';
                $b_error = $lang === 'fa' ? 'خطا در ارتباط با سرور.' : 'Server communication error.';
                $b_lang = $lang;
                $b_dir = $dir;

                $boilerplate = "<!DOCTYPE html>\n<html lang=\"{$b_lang}\" dir=\"{$b_dir}\">\n<head>\n    <meta charset=\"UTF-8\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n    <title>{$b_title}</title>\n    <link rel=\"stylesheet\" href=\"assets/css/font-awesome.min.css\">\n    <link rel=\"stylesheet\" href=\"assets/css/style.min.css\">\n</head>\n<body class=\"antialiased text-gray-800 bg-gray-50\">\n\n    <div class=\"container mx-auto px-4 py-20 text-center\">\n        <h1 class=\"text-4xl font-black text-primary mb-4\">{$b_welcome}</h1>\n        <p class=\"text-gray-600\">{$b_desc}</p>\n    </div>\n\n    <script>\n        document.addEventListener(\"DOMContentLoaded\", function() {\n            try {\n                const urlParams = new URLSearchParams(window.location.search);\n                let pageName = window.location.pathname.split('/').pop() || 'index';\n                if (pageName.endsWith('.html')) pageName = pageName.slice(0, -5);\n                if (pageName === '') pageName = 'index';\n\n                const trackingData = { action: 'visit', page: pageName, utm_source: urlParams.get('utm_source') || '', utm_medium: urlParams.get('utm_medium') || '', utm_campaign: urlParams.get('utm_campaign') || '', utm_term: urlParams.get('utm_term') || '', utm_content: urlParams.get('utm_content') || '', referrer: document.referrer || '' };\n                fetch('tracker.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(trackingData) }).then(r=>r.json()).then(r => { if(r.visit_id) { setTimeout(() => { fetch('tracker.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({action: 'ping', visit_id: r.visit_id}) }); }, 4000); } }).catch(e=>console.log(e));\n\n                document.querySelectorAll('form').forEach(form => {\n                    form.addEventListener('submit', function(e) {\n                        e.preventDefault();\n                        const fd = new FormData(form); const d = {}; fd.forEach((v, k) => d[k] = v);\n                        const name = d.name || d.fullname || d.first_name || '';\n                        const phone = d.phone || d.mobile || d.tel || '';\n                        delete d.name; delete d.fullname; delete d.first_name; delete d.phone; delete d.mobile; delete d.tel;\n                        const btn = form.querySelector('button[type=\"submit\"]'); const ob = btn ? btn.innerHTML : '';\n                        if(btn) { btn.innerHTML = '{$b_sending}'; btn.disabled = true; }\n                        fetch('tracker.php', { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify({action: 'lead', page: pageName, name: name, phone: phone, details: JSON.stringify(d), utm_source: urlParams.get('utm_source')||'', utm_medium: urlParams.get('utm_medium')||'', utm_campaign: urlParams.get('utm_campaign')||'', utm_term: urlParams.get('utm_term')||'', utm_content: urlParams.get('utm_content')||''}) })\n                        .then(r => r.json()).then(r => { form.innerHTML = '<div style=\"padding:20px;background:#dcfce7;color:#166534;border-radius:8px;text-align:center;font-weight:bold;\">{$b_success}</div>'; })\n                        .catch(e => { if(btn) { btn.innerHTML = ob; btn.disabled = false; } alert('{$b_error}'); });\n                    });\n                });\n            } catch (error) {}\n        });\n    </script>\n</body>\n</html>";
                file_put_contents($filepath, $boilerplate);
                header("Location: editor.php?file=" . urlencode($new_file));
                exit;
            } else {
                $create_error = __('file_exists');
            }
        } else {
            $create_error = __('invalid_name');
        }
    }

    elseif ($_POST['action'] === 'upload_media') {
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'mp4', 'webm', 'mp3', 'pdf', 'zip', 'rar'];
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $file_name = basename($_FILES['file']['name']);
            $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed_exts)) {
                $clean_name = preg_replace('/[^a-zA-Z0-9.\-_]/', '', pathinfo($file_name, PATHINFO_FILENAME));
                
                if (empty(trim($clean_name))) $clean_name = 'media';
                $final_name = $clean_name . '_' . time() . '.' . $ext;
                $target = $upload_dir . '/' . $final_name;
                
                if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
                    $media_msg = ['type' => 'success', 'text' => __('upload_success')];
                } else {
                    $media_msg = ['type' => 'error', 'text' => __('upload_err')];
                }
            } else {
                $media_msg = ['type' => 'error', 'text' => __('invalid_format')];
            }
        } else {
            $media_msg = ['type' => 'error', 'text' => __('no_file_selected')];
        }
    }

    elseif ($_POST['action'] === 'delete_media') {
        $file_to_delete = basename($_POST['filename']);
        $target = $upload_dir . '/' . $file_to_delete;
        if (file_exists($target) && !is_dir($target)) {
            unlink($target);
            $media_msg = ['type' => 'success', 'text' => __('delete_success')];
        }
    }
}

$db_file = __DIR__ . '/data/tracking.sqlite';
$pdo = null;
$tab = $_GET['tab'] ?? 'stats';

$total_visits = 0;
$today_visits = 0;
$recent_visits = [];
$top_sources = [];
$pages_db_stats = [];
$mobile_pct = 0;
$bounce_rate = 0;
$all_pages = [];

if (file_exists($db_file)) {
    try {
        $pdo = new PDO('sqlite:' . $db_file);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try { $pdo->exec("ALTER TABLE visits ADD COLUMN is_bounced INTEGER DEFAULT 1"); } catch (Exception $e) {}

        if (isset($_GET['heatmap_view'])) {
            $vid = intval($_GET['heatmap_view']);
            $stmt = $pdo->prepare("SELECT s.*, v.page FROM sessions s JOIN visits v ON s.visit_id = v.id WHERE s.visit_id = ?");
            $stmt->execute([$vid]);
            $session = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($session) {
                $page = $session['page'];
                $s_json = json_encode([
                    'clicks' => json_decode($session['clicks'], true) ?: [],
                    'max_scroll' => (int)$session['max_scroll'],
                    'duration' => (int)$session['duration'],
                    'screen_w' => (int)($session['screen_w'] ?? 0)
                ]);
                ?>
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <title>Heatmap Viewer — <?= htmlspecialchars($page) ?></title>
                    <script src="https://cdn.tailwindcss.com"></script>
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
                </head>
                <body class="bg-gray-900 h-screen flex flex-col overflow-hidden">
                    <div class="p-4 bg-gray-800 text-white flex justify-between items-center shadow-md z-20 shrink-0">
                        <div class="flex items-center gap-4">
                            <button onclick="window.close()" class="w-10 h-10 bg-gray-700 hover:bg-gray-600 rounded-lg flex items-center justify-center transition-colors">
                                <i class="fa fa-arrow-left"></i>
                            </button>
                            <div>
                                <h1 class="text-lg font-bold">Click Heatmap — <?= htmlspecialchars($page) ?></h1>
                                <p class="text-xs text-gray-400">
                                    <?= count(json_decode($session['clicks'], true) ?: []) ?> clicks recorded 
                                    | Max scroll: <?= $session['max_scroll'] ?>% 
                                    | Duration: <?= $session['duration'] ?>s
                                    <?php if(($session['screen_w'] ?? 0) > 0): ?>| Screen: <?= $session['screen_w'] ?>px<?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex-1 overflow-auto bg-gray-100">
                        <iframe id="heatmap-iframe" src="<?= ($page === 'index' || $page === '' ? 'index.html' : htmlspecialchars($page).'.html') ?>?preview=1" style="width:100%; height:100%; border:none; display:block;"></iframe>
                    </div>
                    <script>
                        const s = <?= $s_json ?>;
                        const iframe = document.getElementById('heatmap-iframe');
                        
                        iframe.onload = function() {
                            try {
                                var doc = iframe.contentWindow.document;
                                
                                // Inject overlay + dots directly into the iframe DOM
                                var overlay = doc.createElement('div');
                                overlay.style.cssText = 'position:absolute; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.4); z-index:99999; pointer-events:none;';
                                doc.body.style.position = 'relative';
                                doc.body.appendChild(overlay);
                                
                                // Scroll depth line
                                if (s.max_scroll > 0) {
                                    var scrollH = Math.max(doc.body.scrollHeight, doc.documentElement.scrollHeight);
                                    var sy = (s.max_scroll / 100) * scrollH;
                                    var line = doc.createElement('div');
                                    line.style.cssText = 'position:absolute; left:0; width:100%; height:0; border-top:2px dashed rgba(239,68,68,0.8); z-index:100001; pointer-events:none;';
                                    line.style.top = sy + 'px';
                                    var label = doc.createElement('span');
                                    label.style.cssText = 'position:absolute; top:-20px; left:10px; background:rgba(239,68,68,0.9); color:#fff; font-size:11px; padding:2px 8px; border-radius:4px; font-family:sans-serif;';
                                    label.textContent = 'Max Scroll: ' + s.max_scroll + '%';
                                    line.appendChild(label);
                                    doc.body.appendChild(line);
                                }
                                
                                // Inject click dots at absolute positions
                                s.clicks.forEach(function(click) {
                                    var x = click[0];
                                    var y = click[1];
                                    
                                    // Outer glow
                                    var glow = doc.createElement('div');
                                    glow.style.cssText = 'position:absolute; pointer-events:none; z-index:100000; border-radius:50%; width:60px; height:60px; background:radial-gradient(circle, rgba(239,68,68,0.7) 0%, rgba(251,146,60,0.3) 40%, transparent 70%);';
                                    glow.style.left = (x - 30) + 'px';
                                    glow.style.top = (y - 30) + 'px';
                                    doc.body.appendChild(glow);
                                    
                                    // Center dot
                                    var dot = doc.createElement('div');
                                    dot.style.cssText = 'position:absolute; pointer-events:none; z-index:100001; border-radius:50%; width:8px; height:8px; background:#fff; box-shadow:0 0 6px rgba(0,0,0,0.5);';
                                    dot.style.left = (x - 4) + 'px';
                                    dot.style.top = (y - 4) + 'px';
                                    doc.body.appendChild(dot);
                                });
                                
                            } catch(e) {
                                console.log('Heatmap render error:', e);
                            }
                        };
                    </script>
                </body>
                </html>
                <?php
                exit;
            }
        }

        if ($tab === 'stats') {

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
        }

        if ($tab === 'landings') {
            $stmt = $pdo->query("SELECT page, COUNT(*) as total, SUM(CASE WHEN date(created_at) = date('now') THEN 1 ELSE 0 END) as today FROM visits GROUP BY page");
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $pages_db_stats[$row['page']] = $row;
            }
        }

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
    } catch (PDOException $e) {
        $db_error = $e->getMessage();
    }
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

            <?php if ($tab === 'stats'): ?>
                <!-- ================= TAB: STATS ================= -->
                
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

                <?php elseif ($tab === 'landings'): ?>

                <!-- ================= TAB: LANDINGS ================= -->
                
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

            <?php elseif ($tab === 'leads'): ?>
                <!-- ================= TAB: LEADS ================= -->
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

            <?php elseif ($tab === 'media'): ?>
                <!-- ================= TAB: MEDIA (FILE MANAGER) ================= -->
                
                <?php if($media_msg): ?>
                    <div class="p-4 rounded-lg mb-6 shadow-sm font-medium flex items-center gap-2 <?= $media_msg['type'] === 'success' ? 'bg-green-100 text-green-700 border-r-4 border-green-500' : 'bg-red-100 text-red-700 border-r-4 border-red-500' ?>">
                        <i class="fa-solid <?= $media_msg['type'] === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation' ?>"></i>
                        <?= htmlspecialchars($media_msg['text']) ?>
                    </div>
                <?php endif; ?>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                    <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-cloud-arrow-up text-blue-500"></i> <?= __('upload_new') ?>
                    </h3>
                    <form method="POST" enctype="multipart/form-data" class="file-upload-wrapper border-2 border-dashed border-gray-200 rounded-2xl p-8 text-center transition-all">
                        <input type="hidden" name="action" value="upload_media">
                        <i class="fa-solid fa-cloud-arrow-up text-5xl text-gray-300 mb-4 block"></i>
                        <p class="text-sm text-gray-500 mb-4"><?= __('allowed_formats') ?> <strong>JPG, PNG, WEBP, SVG, MP4, MP3, PDF, ZIP, RAR</strong></p>
                        <div class="max-w-md mx-auto bg-gray-50 p-2 rounded-xl border border-gray-100 flex items-center gap-2">
                            <input type="file" name="file" required class="flex-1 block w-full text-sm text-gray-500 file:mr-2 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-green-100 file:text-green-700 hover:file:bg-green-200 cursor-pointer">
                            <button type="submit" class="bg-green-600 hover:bg-green-500 text-white px-6 py-2.5 rounded-lg text-sm font-bold transition-colors shadow-md">
                                <?= __('upload_btn') ?>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-800 text-lg mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-photo-film text-purple-500"></i> <?= __('existing_files') ?>
                    </h3>
                    
                    <?php 
                        $media_files = array_diff(scandir($upload_dir), array('..', '.', '.htaccess')); 
                        usort($media_files, function($a, $b) use ($upload_dir) {
                            return filemtime($upload_dir . '/' . $b) - filemtime($upload_dir . '/' . $a);
                        });
                    ?>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        <?php foreach($media_files as $f): 
                            $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
                            $is_img = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                            $is_vid = in_array($ext, ['mp4', 'webm']);
                            $file_url = 'assets/uploads/' . $f;
                            $abs_url = $base_url . '/assets/uploads/' . $f;
                            $size_kb = round(filesize($upload_dir . '/' . $f) / 1024);
                            $size_str = $size_kb > 1024 ? round($size_kb / 1024, 2) . ' MB' : $size_kb . ' KB';
                        ?>
                            <div class="border border-gray-100 rounded-xl overflow-hidden relative group shadow-sm hover:shadow-md transition-shadow">
                                <div class="h-32 bg-gray-50 flex items-center justify-center relative overflow-hidden pattern-bg">
                                    <?php if($is_img): ?>
                                        <img src="<?= $file_url ?>" class="w-full h-full object-cover">
                                    <?php elseif($is_vid): ?>
                                        <div class="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center text-blue-500">
                                            <i class="fa-solid fa-file-video text-2xl"></i>
                                        </div>
                                    <?php else: ?>
                                        <div class="bg-gray-200 w-12 h-12 rounded-full flex items-center justify-center text-gray-500">
                                            <i class="fa-solid fa-file text-2xl"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Hover Overlay -->
                                    <div class="absolute inset-0 bg-gray-900/70 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3 backdrop-blur-[2px]">
                                        <button type="button" onclick="copyToClipboard('<?= $abs_url ?>', this, true)" class="w-10 h-10 bg-white text-gray-800 rounded-full flex items-center justify-center hover:bg-green-500 hover:text-white transition-colors" title="<?= __('copy_source_link') ?>">
                                            <i class="fa-solid fa-link"></i>
                                        </button>
                                        <form method="POST" class="m-0" onsubmit="return confirm('<?= __('confirm_del') ?>')">
                                            <input type="hidden" name="action" value="delete_media">
                                            <input type="hidden" name="filename" value="<?= htmlspecialchars($f) ?>">
                                            <button type="submit" class="w-10 h-10 bg-white text-red-500 rounded-full flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors" title="<?= __('delete_file_action') ?>">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="p-3 bg-white border-t border-gray-100">
                                    <p class="text-[11px] text-gray-700 truncate font-mono font-bold mb-1" dir="ltr" title="<?= htmlspecialchars($f) ?>"><?= htmlspecialchars($f) ?></p>
                                    <p class="text-[10px] text-gray-400 font-medium"><?= $size_str ?> <span class="mx-1">•</span> <?= strtoupper($ext) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if(empty($media_files)): ?>
                        <div class="text-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                            <i class="fa-regular fa-images text-5xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500 font-medium"><?= __('no_files') ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
    </main>

    <!-- Modal: ایجاد <?= __('new_landing') ?> -->
    <div id="create-modal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform scale-95 transition-transform duration-300 mx-4" id="create-modal-content">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-file-circle-plus text-green-500"></i> <?= __('create_new_landing') ?>
                </h3>
                <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-red-100 hover:text-red-500 text-gray-500 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="p-6">
                <form method="POST">
                    <input type="hidden" name="action" value="create_landing">
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?= __('filename_en') ?></label>
                        <div class="flex items-center" dir="ltr">
                            <input type="text" name="filename" placeholder="e.g. blackfriday" required pattern="[a-zA-Z0-9_-]+" title="فقط از حروف انگلیسی، اعداد، خط تیره و آندرلاین استفاده کنید." class="flex-1 border border-gray-300 rounded-l-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-left bg-gray-50 transition-shadow">
                            <span class="bg-gray-200 border border-l-0 border-gray-300 rounded-r-lg px-4 py-3 text-gray-600 font-mono font-bold">.html</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2"><?= __('filename_hint') ?></p>
                    </div>
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl transition-all shadow-lg shadow-green-600/30 flex justify-center items-center gap-2">
                        <?= __('continue_editor') ?> <i class="fa-solid <?= $lang === 'fa' ? 'fa-arrow-left' : 'fa-arrow-right' ?>"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text, btnElement, isMedia = false) {
            let copyText = text;
            if (isMedia) {
                const urlObj = new URL(text);
                const pathParts = urlObj.pathname.split('/');
                const fileName = pathParts[pathParts.length - 1];
                copyText = 'assets/uploads/' + fileName;
            }

            navigator.clipboard.writeText(copyText).then(function() {
                const originalHtml = btnElement.innerHTML;
                btnElement.innerHTML = '<i class="fa-solid fa-check"></i>';
                btnElement.classList.add(isMedia ? 'bg-green-500' : 'bg-green-50', isMedia ? 'text-white' : 'text-green-600');
                if(!isMedia) btnElement.innerHTML += ' ' + '<?= __('copied') ?>';
                
                setTimeout(() => {
                    btnElement.innerHTML = originalHtml;
                    btnElement.classList.remove('bg-green-50', 'bg-green-500', 'text-white');
                }, 2000);
            }, function(err) {});
        }

        function closeModal() {
            const modal = document.getElementById('create-modal');
            const content = document.getElementById('create-modal-content');
            modal.classList.remove('opacity-100');
            content.classList.remove('scale-100');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        const oldRemove = DOMTokenList.prototype.remove;
        DOMTokenList.prototype.remove = function() {
            oldRemove.apply(this, arguments);
            if(arguments[0] === 'hidden' && this.contains('opacity-0')) {
                setTimeout(() => {
                    this.remove('opacity-0');
                    this.add('opacity-100');
                    document.getElementById('create-modal-content').classList.remove('scale-95');
                    document.getElementById('create-modal-content').classList.add('scale-100');
                }, 10);
            }
        };
    </script>
</body>
</html>
