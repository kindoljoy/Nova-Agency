<?php
/**
 * Reusable CTA Banner Component
 */
?>
<section class="py-24 bg-surface-dark/60 border-t border-b border-[#262626] relative overflow-hidden">
    <!-- Glow background orb -->
    <div class="absolute -right-40 -top-40 w-96 h-96 bg-accent/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -left-40 -bottom-40 w-96 h-96 bg-accent/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-3xl">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-mono uppercase tracking-wider text-accent bg-accent/10 border border-accent/20 mb-6">
                <span class="w-1.5 h-1.5 rounded-full bg-accent animate-pulse"></span>
                Ready to elevate your digital presence?
            </span>
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-heading font-extrabold text-white tracking-tight leading-tight">
                Let's craft something unforgettable together.
            </h2>
            <p class="mt-6 text-lg text-offwhite/70 max-w-2xl font-light leading-relaxed">
                Whether you're establishing a revolutionary brand from scratch, engineering a scalable web application, or reimagining your digital product experience, our team is ready.
            </p>
            <div class="mt-10 flex flex-wrap items-center gap-4">
                <a href="<?= url('contact.php') ?>" class="btn-primary flex items-center gap-2 group text-sm">
                    <span>START A PROJECT</span>
                    <i data-lucide="arrow-right" class="w-4 h-4 transform group-hover:translate-x-1 transition-transform"></i>
                </a>
                <a href="<?= url('work.php') ?>" class="btn-secondary text-sm">
                    EXPLORE PORTFOLIO
                </a>
            </div>
        </div>
    </div>
</section>

