<?php
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
