<?php
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
