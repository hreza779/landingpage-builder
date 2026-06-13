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
