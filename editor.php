<?php

session_start();

$lang = $_SESSION['lang'] ?? 'en';
$dir = $lang === 'fa' ? 'rtl' : 'ltr';

$i18n = [
    'en' => [
        'invalid_file' => 'File is invalid or not found.',
        'editor_title' => 'Landing Editor',
        'back_to_landings' => 'Back to Landings',
        'saved_msg' => 'Saved successfully',
        'tab_edit' => 'Edit Code',
        'tab_preview' => 'Live Preview',
        'save_changes' => 'Save Changes',
        'view_label' => 'View:',
        'device_desktop' => 'Desktop',
        'device_tablet' => 'Tablet (iPad)',
        'device_mobile' => 'Mobile (iPhone)',
    ],
    'fa' => [
        'invalid_file' => 'فایل معتبر نیست یا یافت نشد.',
        'editor_title' => 'ویرایشگر لندینگ',
        'back_to_landings' => '<?= __("back_to_landings") ?>',
        'saved_msg' => '<?= __("saved_msg") ?>',
        'tab_edit' => '<?= __("tab_edit") ?>',
        'tab_preview' => '<?= __("tab_preview") ?>',
        'save_changes' => '<?= __("save_changes") ?>',
        'view_label' => '<?= __("view_label") ?>',
        'device_desktop' => '<?= __("device_desktop") ?>',
        'device_tablet' => '<?= __("device_tablet") ?>',
        'device_mobile' => '<?= __("device_mobile") ?>',
    ]
];

function __($key) {
    global $i18n, $lang;
    return $i18n[$lang][$key] ?? $key;
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: stats.php");
    exit;
}

$filename = isset($_GET['file']) ? basename($_GET['file']) : '';
if (!$filename || substr($filename, -5) !== '.html' || !file_exists(__DIR__ . '/' . $filename)) {
    die(__("invalid_file"));
}
$filepath = __DIR__ . '/' . $filename;

$saved = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    file_put_contents($filepath, $_POST['content']);
    $saved = true;
}

$content = file_get_contents($filepath);
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __("editor_title") ?> - <?= htmlspecialchars($filename) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CodeMirror (ویرایشگر کد) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/theme/dracula.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap');
        body { font-family: <?= $lang === 'fa' ? "'Vazirmatn'" : "'Inter'" ?>, sans-serif; background-color: #282a36; overflow: hidden; }
        .CodeMirror { height: 100% !important; font-family: monospace; font-size: 15px; direction: ltr; text-align: left; }
        /* Fix Tailwind issues with CodeMirror */
        .CodeMirror pre { padding: 0 4px; }
    </style>
</head>
<body class="flex flex-col h-screen">

    <!-- Topbar -->
    <header class="h-16 bg-gray-900 border-b border-gray-800 flex items-center justify-between px-4 lg:px-6 shrink-0 text-white shadow-md z-10">
        <div class="flex items-center gap-4 w-1/3">
            <a href="stats.php?tab=landings" class="text-gray-400 hover:text-white transition-colors" title="<?= __("back_to_landings") ?>">
                <i class="fa-solid fa-arrow-right text-xl"></i>
            </a>
            <div class="flex items-center gap-2 bg-gray-800 px-3 py-1.5 rounded-lg border border-gray-700">
                <i class="fa-brands fa-html5 text-orange-500"></i>
                <span class="font-mono text-sm font-bold" dir="ltr"><?= htmlspecialchars($filename) ?></span>
            </div>
            <?php if($saved): ?>
                <span class="text-green-400 text-sm bg-green-400/10 px-2 py-1 rounded flex items-center gap-1 animate-pulse">
                    <i class="fa-solid fa-check"></i> <?= __("saved_msg") ?>
                </span>
            <?php endif; ?>
        </div>

        <!-- Tabs -->
        <div class="flex bg-gray-800 rounded-lg p-1 border border-gray-700 justify-center">
            <button id="tab-edit" class="px-6 py-1.5 rounded-md bg-gray-700 text-white font-medium text-sm transition-all flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-code"></i> <?= __("tab_edit") ?>
            </button>
            <button id="tab-preview" class="px-6 py-1.5 rounded-md text-gray-400 hover:text-white font-medium text-sm transition-all flex items-center gap-2">
                <i class="fa-solid fa-eye"></i> <?= __("tab_preview") ?>
            </button>
        </div>

        <div class="w-1/3 flex justify-end">
            <button onclick="submitEditor()" class="bg-green-600 hover:bg-green-500 text-white px-5 py-2 rounded-lg text-sm font-bold transition-colors flex items-center gap-2 shadow-lg shadow-green-900/50">
                <i class="fa-solid fa-floppy-disk"></i> <?= __("save_changes") ?>
            </button>
        </div>
    </header>

    <!-- Main Workspace -->
    <main class="flex-1 relative">
        <form id="editor-form" method="POST" class="w-full h-full">
            <!-- Edit Pane -->
            <div id="edit-pane" class="w-full h-full absolute inset-0">
                <textarea id="code-editor" name="content" class="hidden"><?= htmlspecialchars($content) ?></textarea>
            </div>
        </form>

        <!-- Preview Pane -->
        <div id="preview-pane" class="w-full h-full absolute inset-0 hidden">
            <div class="w-full h-full flex flex-col bg-gray-100">
                <!-- Responsive Toolbar -->
                <div class="h-12 bg-white border-b border-gray-200 flex items-center justify-center gap-2 shrink-0 shadow-sm z-10">
                    <span class="text-xs text-gray-500 font-bold ml-4"><?= __("view_label") ?></span>
                    <button onclick="setPreviewSize('100%', this)" class="preview-btn w-10 h-8 flex items-center justify-center rounded bg-gray-100 text-gray-800 hover:bg-gray-200 transition-colors" title="<?= __("device_desktop") ?>">
                        <i class="fa-solid fa-desktop"></i>
                    </button>
                    <button onclick="setPreviewSize('768px', this)" class="preview-btn w-10 h-8 flex items-center justify-center rounded text-gray-500 hover:bg-gray-100 transition-colors" title="<?= __("device_tablet") ?>">
                        <i class="fa-solid fa-tablet-screen-button"></i>
                    </button>
                    <button onclick="setPreviewSize('375px', this)" class="preview-btn w-10 h-8 flex items-center justify-center rounded text-gray-500 hover:bg-gray-100 transition-colors" title="<?= __("device_mobile") ?>">
                        <i class="fa-solid fa-mobile-screen-button"></i>
                    </button>
                    <span class="text-xs text-gray-400 mr-4 border-r border-gray-200 pr-4 font-mono" id="preview-size-label">Desktop</span>
                </div>
                
                <!-- iframe Container -->
                <div class="flex-1 overflow-auto flex justify-center bg-gray-300/50">
                    <div id="iframe-container" class="h-full bg-white shadow-2xl transition-all duration-300 ease-in-out border-x border-gray-300" style="width: 100%;">
                        <iframe id="preview-frame" class="w-full h-full border-none bg-white"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/xml/xml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/css/css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/htmlmixed/htmlmixed.min.js"></script>

    <script>
        // مقداردهی CodeMirror
        const editor = CodeMirror.fromTextArea(document.getElementById('code-editor'), {
            mode: "htmlmixed",
            theme: "dracula",
            lineNumbers: true,
            indentUnit: 4,
            indentWithTabs: false,
            lineWrapping: true
        });

        // سوییچ بین تب‌ها
        const tabEdit = document.getElementById('tab-edit');
        const tabPreview = document.getElementById('tab-preview');
        const paneEdit = document.getElementById('edit-pane');
        const panePreview = document.getElementById('preview-pane');
        const previewFrame = document.getElementById('preview-frame');

        tabEdit.addEventListener('click', () => {
            tabEdit.classList.add('bg-gray-700', 'text-white', 'shadow-sm');
            tabEdit.classList.remove('text-gray-400', 'hover:text-white');
            tabPreview.classList.remove('bg-gray-700', 'text-white', 'shadow-sm');
            tabPreview.classList.add('text-gray-400', 'hover:text-white');
            
            paneEdit.classList.remove('hidden');
            panePreview.classList.add('hidden');
            
            // رفرش کردن ابعاد ادیتور پس از نمایش مجدد
            setTimeout(() => editor.refresh(), 10);
        });

        tabPreview.addEventListener('click', () => {
            tabPreview.classList.add('bg-gray-700', 'text-white', 'shadow-sm');
            tabPreview.classList.remove('text-gray-400', 'hover:text-white');
            tabEdit.classList.remove('bg-gray-700', 'text-white', 'shadow-sm');
            tabEdit.classList.add('text-gray-400', 'hover:text-white');
            
            panePreview.classList.remove('hidden');
            paneEdit.classList.add('hidden');
            
            // گرفتن کدها و آپدیت کردن Iframe
            const code = editor.getValue();
            previewFrame.srcdoc = code;
        });

        // تابع ذخیره کردن
        function submitEditor() {
            editor.save(); // انتقال متن از CodeMirror به textarea
            document.getElementById('editor-form').submit();
        }

        // کلید میانبر (Ctrl+S یا Cmd+S)
        document.addEventListener('keydown', function(e) {
            if ((window.navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)  && e.keyCode == 83) {
                e.preventDefault();
                submitEditor();
            }
        });

        // تغییر سایز پیش‌نمایش
        function setPreviewSize(width, btnElement) {
            const container = document.getElementById('iframe-container');
            const label = document.getElementById('preview-size-label');
            
            container.style.width = width;
            
            let labelText = 'Desktop';
            if(width === '768px') labelText = 'Tablet (768px)';
            if(width === '375px') labelText = 'Mobile (375px)';
            label.innerText = labelText;
            
            const buttons = document.querySelectorAll('.preview-btn');
            buttons.forEach(btn => {
                btn.classList.remove('bg-gray-100', 'text-gray-800');
                btn.classList.add('text-gray-500');
            });
            btnElement.classList.add('bg-gray-100', 'text-gray-800');
            btnElement.classList.remove('text-gray-500');
        }
    </script>
</body>
</html>
