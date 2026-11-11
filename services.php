<?php
/**
 * NOVA Creative Digital Agency
 * Services Page (services.php)
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/helpers.php';

$pageTitle = 'Services & Capabilities — NOVA®';
$pageDesc = 'Explore our core capabilities across Brand Identity, UI/UX Design, Website Development, Graphic Design, and Digital Marketing.';

$db = getDB();
$servicesStmt = $db->query("SELECT * FROM services WHERE status = 'active' ORDER BY display_order ASC");
$services = $servicesStmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<!-- Services Hero Section -->
<section class="py-24 bg-primary-dark border-b border-[#222222] relative overflow-hidden">
    <div class="absolute -left-20 top-1/4 w-96 h-96 bg-accent/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-4xl">
            <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-mono uppercase tracking-wider text-accent bg-accent/10 border border-accent/20 mb-6">
                OUR CAPABILITIES
            </span>
            <h1 class="text-4xl sm:text-6xl md:text-7xl font-heading font-black text-white tracking-tight leading-[1.08] mb-8">
                End-to-end creative solutions engineered for real impact.
            </h1>
            <p class="text-xl sm:text-2xl text-offwhite/70 font-light leading-relaxed max-w-3xl">
                We combine strategic vision with obsessive craftsmanship. From brand architecture to high-performance code, explore how we help visionary companies scale.
            </p>
        </div>
    </div>
</section>

<!-- Detailed Services Listing Section -->
<section class="py-24 bg-[#141414]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-24">
        <?php foreach ($services as $index => $service): 
            $num = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
            $benefits = array_filter(array_map('trim', explode("\n", $service['benefits'] ?? '')));
            $deliverables = array_filter(array_map('trim', explode("\n", $service['deliverables'] ?? '')));
        ?>
        <div id="<?= e($service['slug']) ?>" class="p-8 sm:p-12 lg:p-16 rounded-3xl bg-primary-dark border border-[#2B2B2B] hover:border-accent/40 transition-all duration-300 relative overflow-hidden">
            <!-- Background Service Number -->
            <div class="absolute -right-6 -bottom-10 font-heading font-black text-9xl text-white/[0.03] select-none pointer-events-none">
                <?= $num ?>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 relative z-10">
                <!-- Left: Title, Description & Action -->
                <div class="lg:col-span-5 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <span class="font-mono text-xs text-accent font-bold tracking-widest"><?= $num ?> // SERVICE</span>
                            <span class="h-px w-10 bg-accent/30"></span>
                            <div class="w-8 h-8 rounded-full bg-surface-dark border border-[#333333] flex items-center justify-center text-accent">
                                <i data-lucide="<?= e($service['icon'] ?? 'sparkles') ?>" class="w-4 h-4"></i>
                            </div>
                        </div>

                        <h2 class="text-3xl sm:text-4xl font-heading font-extrabold text-white mb-6">
                            <?= e($service['title']) ?>
                        </h2>

                        <p class="text-offwhite/80 leading-relaxed font-light text-base mb-8">
                            <?= e($service['description'] ?? $service['short_description']) ?>
                        </p>
                    </div>

                    <div class="pt-6 border-t border-[#262626]">
                        <a href="<?= url('contact.php?service=' . urlencode($service['title'])) ?>" class="btn-primary inline-flex items-center gap-2 text-xs">
                            <span>INQUIRE ABOUT THIS SERVICE</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>

                <!-- Right: Benefits & Deliverables -->
                <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-8 bg-surface-dark/70 p-8 sm:p-10 rounded-2xl border border-[#262626]">
                    <!-- Key Benefits -->
                    <div>
                        <h3 class="font-mono text-xs uppercase tracking-wider text-accent font-bold mb-6 flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-4 h-4 text-accent"></i>
                            <span>Strategic Benefits</span>
                        </h3>
                        <ul class="space-y-4">
                            <?php foreach ($benefits as $benefit): ?>
                            <li class="flex items-start gap-3 text-sm text-offwhite/80 font-light">
                                <i data-lucide="check" class="w-4 h-4 text-emerald-400 flex-shrink-0 mt-0.5"></i>
                                <span><?= e($benefit) ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Deliverables Checklist -->
                    <div>
                        <h3 class="font-mono text-xs uppercase tracking-wider text-accent font-bold mb-6 flex items-center gap-2">
                            <i data-lucide="layers" class="w-4 h-4 text-accent"></i>
                            <span>Key Deliverables</span>
                        </h3>
                        <ul class="space-y-4">
                            <?php foreach ($deliverables as $deliverable): ?>
                            <li class="flex items-start gap-3 text-sm text-offwhite/80 font-light">
                                <span class="w-1.5 h-1.5 rounded-full bg-accent flex-shrink-0 mt-2"></span>
                                <span><?= e($deliverable) ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Call to action block -->
<?php require_once __DIR__ . '/includes/cta-banner.php'; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

