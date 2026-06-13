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
