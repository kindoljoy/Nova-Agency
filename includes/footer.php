    </main>

    <!-- Global Footer -->
    <footer class="bg-primary-dark border-t border-[#222222] pt-20 pb-12 text-offwhite/70">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12 mb-16">
                <!-- Column 1: Brand Info -->
                <div class="lg:col-span-2">
                    <a href="<?= url('index.php') ?>" class="flex items-center gap-1 mb-6">
                        <span class="font-heading font-black text-3xl tracking-tighter text-white">NOVA</span>
                        <span class="w-2.5 h-2.5 rounded-full bg-accent inline-block transform translate-y-[-4px]"></span>
                        <span class="font-mono text-xs text-muted-gray uppercase ml-0.5">®</span>
                    </a>
                    <p class="text-offwhite/80 font-heading text-lg font-semibold text-white mb-4">
                        <?= e(get_setting('agency_tagline', 'We Build Brands. We Create Experiences.')) ?>
                    </p>
                    <p class="text-sm text-offwhite/60 leading-relaxed max-w-sm mb-6">
                        NOVA is an independent creative digital agency partnering with ambitious startups, luxury brands, and forward-thinking enterprises worldwide.
                    </p>
                    <div class="flex items-center space-x-4">
                        <a href="<?= e(get_setting('social_instagram', '#')) ?>" target="_blank" rel="noopener" class="w-10 h-10 rounded-full bg-charcoal border border-[#333333] flex items-center justify-center text-offwhite/80 hover:text-accent hover:border-accent transition-all" aria-label="Instagram">
                            <i data-lucide="instagram" class="w-4 h-4"></i>
                        </a>
                        <a href="<?= e(get_setting('social_linkedin', '#')) ?>" target="_blank" rel="noopener" class="w-10 h-10 rounded-full bg-charcoal border border-[#333333] flex items-center justify-center text-offwhite/80 hover:text-accent hover:border-accent transition-all" aria-label="LinkedIn">
                            <i data-lucide="linkedin" class="w-4 h-4"></i>
                        </a>
                        <a href="<?= e(get_setting('social_dribbble', '#')) ?>" target="_blank" rel="noopener" class="w-10 h-10 rounded-full bg-charcoal border border-[#333333] flex items-center justify-center text-offwhite/80 hover:text-accent hover:border-accent transition-all" aria-label="Dribbble">
                            <i data-lucide="dribbble" class="w-4 h-4"></i>
                        </a>
                        <a href="<?= e(get_setting('social_x', '#')) ?>" target="_blank" rel="noopener" class="w-10 h-10 rounded-full bg-charcoal border border-[#333333] flex items-center justify-center text-offwhite/80 hover:text-accent hover:border-accent transition-all" aria-label="Twitter / X">
                            <i data-lucide="twitter" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>

                <!-- Column 2: Navigation -->
                <div>
                    <h3 class="font-mono text-xs uppercase tracking-wider text-white font-bold mb-6">Explore</h3>
                    <ul class="space-y-3 text-sm">
                        <li><a href="<?= url('index.php') ?>" class="hover:text-accent transition-colors">Home</a></li>
                        <li><a href="<?= url('about.php') ?>" class="hover:text-accent transition-colors">About Agency</a></li>
                        <li><a href="<?= url('services.php') ?>" class="hover:text-accent transition-colors">Core Capabilities</a></li>
                        <li><a href="<?= url('work.php') ?>" class="hover:text-accent transition-colors">Selected Work</a></li>
                        <li><a href="<?= url('insights.php') ?>" class="hover:text-accent transition-colors">Insights & Articles</a></li>
                        <li><a href="<?= url('careers.php') ?>" class="hover:text-accent transition-colors flex items-center gap-2">
                            <span>Careers</span>
                            <span class="text-[10px] font-mono px-1.5 py-0.5 rounded bg-accent/20 text-accent border border-accent/30">WE'RE HIRING</span>
                        </a></li>
                        <li><a href="<?= url('contact.php') ?>" class="hover:text-accent transition-colors">Contact</a></li>
                    </ul>
                </div>

                <!-- Column 3: Services -->
                <div>
                    <h3 class="font-mono text-xs uppercase tracking-wider text-white font-bold mb-6">Services</h3>
                    <ul class="space-y-3 text-sm">
                        <li><a href="<?= url('services.php#brand-identity') ?>" class="hover:text-accent transition-colors">Brand Identity</a></li>
                        <li><a href="<?= url('services.php#ui-ux-design') ?>" class="hover:text-accent transition-colors">UI/UX Design</a></li>
                        <li><a href="<?= url('services.php#website-development') ?>" class="hover:text-accent transition-colors">Website Development</a></li>
                        <li><a href="<?= url('services.php#graphic-design') ?>" class="hover:text-accent transition-colors">Graphic Design</a></li>
                        <li><a href="<?= url('services.php#digital-marketing') ?>" class="hover:text-accent transition-colors">Digital Marketing</a></li>
                    </ul>
                </div>

                <!-- Column 4: Contact & Office -->
                <div>
                    <h3 class="font-mono text-xs uppercase tracking-wider text-white font-bold mb-6">Inquiries</h3>
                    <div class="space-y-4 text-sm">
                        <div>
                            <span class="text-xs font-mono text-muted-gray block">NEW BUSINESS</span>
                            <a href="mailto:<?= e(get_setting('contact_email', 'hello@nova.agency')) ?>" class="text-white hover:text-accent transition-colors font-medium">
                                <?= e(get_setting('contact_email', 'hello@nova.agency')) ?>
                            </a>
                        </div>
                        <div>
                            <span class="text-xs font-mono text-muted-gray block">WHATSAPP / PHONE</span>
                            <a href="tel:<?= e(get_setting('contact_whatsapp', '+6281289006682')) ?>" class="text-white hover:text-accent transition-colors font-medium">
                                <?= e(get_setting('contact_phone', '+62 21 555-8900')) ?>
                            </a>
                        </div>
                        <div>
                            <span class="text-xs font-mono text-muted-gray block">HEADQUARTERS</span>
                            <p class="text-offwhite/80 leading-snug">
                                <?= e(get_setting('office_address', 'SCBD District 8, Level 24, Jakarta, Indonesia')) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Sub-footer -->
            <div class="pt-8 border-t border-[#222222] flex flex-col sm:flex-row items-center justify-between text-xs text-offwhite/50 space-y-4 sm:space-y-0">
                <div>
                    &copy; <?= date('Y') ?> NOVA Creative Digital Agency. All rights reserved.
                </div>
                <div class="flex items-center space-x-6">
                    <span class="font-mono text-[11px] text-muted-gray">Operating hours: <?= e(get_setting('working_hours', 'Mon - Fri, 09:00 - 18:00 WIB')) ?></span>
                    <a href="<?= url('admin/login.php') ?>" class="hover:text-accent font-mono transition-colors flex items-center gap-1 text-[11px]">
                        <i data-lucide="lock" class="w-3 h-3"></i>
                        <span>Admin Access</span>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Main JavaScript Assets -->
    <script src="<?= asset('js/main.js') ?>"></script>
    <script>
        // Trigger lucide icon creation
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>
</body>
</html>

