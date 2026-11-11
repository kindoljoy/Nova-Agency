            </main>
        </div>
    </div>

    <!-- Admin Mobile Menu Modal / Drawer -->
    <div id="admin-mobile-drawer" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden md:hidden">
        <div class="w-64 bg-sidebar-dark h-full p-4 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-border-dark mb-4">
                    <span class="font-heading font-black text-xl text-white">NOVA ADMIN</span>
                    <button id="admin-close-drawer" class="text-offwhite/60 p-1"><i data-lucide="x" class="w-5 h-5"></i></button>
                </div>
                <nav class="space-y-1 text-sm font-medium">
                    <a href="<?= url('admin/index.php') ?>" class="block px-3 py-2 rounded text-offwhite hover:bg-charcoal">Dashboard</a>
                    <a href="<?= url('admin/inquiries.php') ?>" class="block px-3 py-2 rounded text-offwhite hover:bg-charcoal">Inquiries</a>
                    <a href="<?= url('admin/projects.php') ?>" class="block px-3 py-2 rounded text-offwhite hover:bg-charcoal">Portfolio / Work</a>
                    <a href="<?= url('admin/services.php') ?>" class="block px-3 py-2 rounded text-offwhite hover:bg-charcoal">Services</a>
                    <a href="<?= url('admin/articles.php') ?>" class="block px-3 py-2 rounded text-offwhite hover:bg-charcoal">Insights / Articles</a>
                    <a href="<?= url('admin/testimonials.php') ?>" class="block px-3 py-2 rounded text-offwhite hover:bg-charcoal">Testimonials</a>
                    <a href="<?= url('admin/jobs.php') ?>" class="block px-3 py-2 rounded text-offwhite hover:bg-charcoal">Job Positions</a>
                    <a href="<?= url('admin/applications.php') ?>" class="block px-3 py-2 rounded text-offwhite hover:bg-charcoal">Applications</a>
                    <a href="<?= url('admin/settings.php') ?>" class="block px-3 py-2 rounded text-offwhite hover:bg-charcoal">Settings</a>
                </nav>
            </div>
            <div class="pt-4 border-t border-border-dark">
                <a href="<?= url('admin/logout.php') ?>" class="block text-center py-2 bg-rose-950/60 text-rose-300 rounded text-xs font-mono">Logout</a>
            </div>
        </div>
    </div>

    <script src="<?= asset('js/main.js') ?>"></script>
    <script>
        lucide.createIcons();
        const toggleBtn = document.getElementById('admin-mobile-toggle');
        const drawer = document.getElementById('admin-mobile-drawer');
        const closeBtn = document.getElementById('admin-close-drawer');
        if (toggleBtn && drawer) {
            toggleBtn.addEventListener('click', () => drawer.classList.remove('hidden'));
        }
        if (closeBtn && drawer) {
            closeBtn.addEventListener('click', () => drawer.classList.add('hidden'));
        }
    </script>
</body>
</html>

