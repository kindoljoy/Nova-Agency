<?php
/**
 * NOVA Creative Digital Agency
 * Homepage (index.php)
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/helpers.php';

$pageTitle = 'NOVA® — Creative Digital Agency';
$pageDesc = 'We combine creativity, strategy, and technology to help ambitious brands stand out in the digital world.';

$db = getDB();

// Fetch active services
$servicesStmt = $db->query("SELECT * FROM services WHERE status = 'active' ORDER BY display_order ASC LIMIT 4");
$services = $servicesStmt->fetchAll();

// Fetch featured projects
$projectsStmt = $db->query("SELECT * FROM projects WHERE status = 'published' ORDER BY is_featured DESC, display_order ASC LIMIT 4");
$projects = $projectsStmt->fetchAll();

// Fetch testimonials
$testimonialsStmt = $db->query("SELECT * FROM testimonials WHERE status = 'active' ORDER BY display_order ASC LIMIT 3");
$testimonials = $testimonialsStmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="relative min-h-[85vh] flex items-center justify-center pt-16 pb-24 overflow-hidden">
    <!-- Atmospheric Ambient Background Light -->
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[450px] bg-accent/10 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute top-1/3 right-10 w-[350px] h-[350px] bg-blue-500/5 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full text-center">
        <!-- Availability Badge -->
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-mono uppercase tracking-widest text-accent bg-accent/10 border border-accent/25 mb-8">
            <span class="w-2 h-2 rounded-full bg-accent animate-ping"></span>
            Available for Q1 / Q2 Projects
        </div>

        <!-- Main Headline -->
        <h1 class="text-4xl sm:text-6xl md:text-7xl lg:text-8xl font-heading font-black tracking-tight text-white max-w-5xl mx-auto leading-[1.05] mb-8">
            We Create Digital Experiences <span class="text-accent-gradient">That Matter.</span>
        </h1>

        <!-- Supporting Paragraph -->
        <p class="text-lg sm:text-xl md:text-2xl text-offwhite/75 max-w-3xl mx-auto font-light leading-relaxed mb-12">
            We combine creativity, strategy, and technology to help ambitious brands stand out in the digital world.
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 max-w-md mx-auto mb-20">
            <a href="<?= url('contact.php') ?>" class="btn-primary w-full sm:w-auto text-sm group">
                <span>START A PROJECT</span>
                <i data-lucide="arrow-up-right" class="w-4 h-4 ml-2 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"></i>
            </a>
            <a href="<?= url('work.php') ?>" class="btn-secondary w-full sm:w-auto text-sm">
                EXPLORE OUR WORK
            </a>
        </div>

        <!-- Agency Key Metrics Banner Strip -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 pt-12 border-t border-[#262626] max-w-5xl mx-auto text-left">
            <div class="p-4 rounded-xl bg-surface-dark/40 border border-[#222222]">
                <div class="font-heading text-3xl sm:text-4xl font-extrabold text-white">8+</div>
                <div class="font-mono text-xs text-muted-gray uppercase mt-1 tracking-wider">Years Experience</div>
            </div>
            <div class="p-4 rounded-xl bg-surface-dark/40 border border-[#222222]">
                <div class="font-heading text-3xl sm:text-4xl font-extrabold text-white">120+</div>
                <div class="font-mono text-xs text-muted-gray uppercase mt-1 tracking-wider">Projects Completed</div>
            </div>
            <div class="p-4 rounded-xl bg-surface-dark/40 border border-[#222222]">
                <div class="font-heading text-3xl sm:text-4xl font-extrabold text-white">98%</div>
                <div class="font-mono text-xs text-muted-gray uppercase mt-1 tracking-wider">Client Satisfaction</div>
            </div>
            <div class="p-4 rounded-xl bg-surface-dark/40 border border-[#222222]">
                <div class="font-heading text-3xl sm:text-4xl font-extrabold text-white">15+</div>
                <div class="font-mono text-xs text-muted-gray uppercase mt-1 tracking-wider">Industry Awards</div>
            </div>
        </div>
    </div>
</section>

<!-- About Preview Section -->
<section class="py-24 bg-surface-dark/50 border-t border-[#222222] relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-5">
                <span class="font-mono text-xs text-accent uppercase tracking-widest block mb-3">WHO WE ARE</span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-heading font-extrabold text-white leading-tight">
                    An independent creative agency built for the bold.
                </h2>
            </div>
            <div class="lg:col-span-7 space-y-6 text-offwhite/80 text-lg leading-relaxed font-light">
                <p>
                    NOVA is not an ordinary agency. We are a multidisciplinary collective of brand strategists, digital designers, and creative engineers. We partner with leaders who reject the mundane and demand digital products that redefine their industries.
                </p>
                <p>
                    From high-growth startups securing Series-A funding to heritage luxury brands commanding global attention, we engineer cohesive identities and digital platforms that captivate minds and accelerate revenue.
                </p>
                <div class="pt-4">
                    <a href="<?= url('about.php') ?>" class="inline-flex items-center gap-2 font-mono text-sm font-bold text-accent hover:text-white transition-colors group">
                        <span>LEARN MORE ABOUT OUR MISSION</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 transform group-hover:translate-x-1.5 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Core Services Section -->
<section class="py-28 bg-primary-dark border-t border-[#222222] relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16">
            <div>
                <span class="font-mono text-xs text-accent uppercase tracking-widest block mb-3">CAPABILITIES</span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-heading font-extrabold text-white tracking-tight">
                    Specialized Expertise.
                </h2>
            </div>
            <p class="mt-4 md:mt-0 text-offwhite/60 max-w-md text-sm leading-relaxed">
                We bridge the gap between arresting brand design and high-performance technical engineering.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <?php foreach ($services as $index => $service): 
                $num = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
            ?>
            <div class="group p-8 sm:p-10 rounded-2xl bg-[#171717] border border-[#2B2B2B] hover:border-accent/60 transition-all duration-300 card-hover flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-8">
                        <span class="font-mono text-xs text-accent font-bold tracking-widest"><?= $num ?> // SERVICE</span>
                        <div class="w-10 h-10 rounded-full bg-[#222222] flex items-center justify-center text-offwhite group-hover:bg-accent group-hover:text-black transition-colors">
                            <i data-lucide="<?= e($service['icon'] ?? 'sparkles') ?>" class="w-5 h-5"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-heading font-bold text-white mb-4 group-hover:text-accent transition-colors">
                        <?= e($service['title']) ?>
                    </h3>
                    <p class="text-offwhite/70 text-sm leading-relaxed mb-6 font-light">
                        <?= e($service['short_description']) ?>
                    </p>
                </div>
                <div class="pt-6 border-t border-[#262626] flex items-center justify-between">
                    <span class="font-mono text-xs text-muted-gray uppercase">DELIVERABLES INCLUDED</span>
                    <a href="<?= url('services.php#' . e($service['slug'])) ?>" class="text-xs font-mono font-bold text-accent group-hover:text-white flex items-center gap-1.5 transition-colors">
                        <span>EXPLORE DETAILS</span>
                        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-12 text-center">
            <a href="<?= url('services.php') ?>" class="btn-secondary text-xs">
                VIEW ALL 5 SERVICES &amp; DELIVERABLES
            </a>
        </div>
    </div>
</section>

<!-- Selected Works / Portfolio Section -->
<section class="py-28 bg-[#141414] border-t border-[#222222] relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16">
            <div>
                <span class="font-mono text-xs text-accent uppercase tracking-widest block mb-3">PORTFOLIO</span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-heading font-extrabold text-white tracking-tight">
                    Selected Works.
                </h2>
            </div>
            <a href="<?= url('work.php') ?>" class="mt-4 md:mt-0 font-mono text-xs text-accent hover:text-white transition-colors flex items-center gap-2 group font-bold">
                <span>VIEW COMPLETE ARCHIVE (<?= count($projects) ?>+)</span>
                <i data-lucide="arrow-right" class="w-4 h-4 transform group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <?php foreach ($projects as $project): ?>
            <article class="group rounded-2xl overflow-hidden bg-[#1A1A1A] border border-[#2B2B2B] hover:border-accent/50 transition-all duration-300 card-hover flex flex-col">
                <a href="<?= url('project-detail.php?slug=' . urlencode($project['slug'])) ?>" class="block relative aspect-[16/10] overflow-hidden bg-black">
                    <img src="<?= upload_url('projects', $project['featured_image']) ?>" 
                         alt="<?= e($project['title']) ?>" 
                         class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700 ease-out"
                         loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-60 group-hover:opacity-30 transition-opacity"></div>
                    <div class="absolute top-4 left-4">
                        <span class="px-3 py-1 rounded-full bg-black/75 backdrop-blur-md text-[11px] font-mono text-accent border border-white/10 uppercase tracking-wider">
                            <?= e($project['category_name'] ?? 'Featured') ?>
                        </span>
                    </div>
                </a>
                <div class="p-8 flex-grow flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between text-xs font-mono text-muted-gray mb-3">
                            <span><?= e($project['client']) ?></span>
                            <span><?= e($project['year']) ?></span>
                        </div>
                        <h3 class="text-2xl font-heading font-bold text-white group-hover:text-accent transition-colors mb-3">
                            <a href="<?= url('project-detail.php?slug=' . urlencode($project['slug'])) ?>">
                                <?= e($project['title']) ?>
                            </a>
                        </h3>
                        <p class="text-sm text-offwhite/70 line-clamp-2 font-light leading-relaxed mb-6">
                            <?= e($project['summary']) ?>
                        </p>
                    </div>
                    <a href="<?= url('project-detail.php?slug=' . urlencode($project['slug'])) ?>" class="pt-4 border-t border-[#262626] flex items-center justify-between group/cta text-offwhite/50 hover:text-accent transition-colors">
                        <span class="text-xs font-mono font-medium group-hover/cta:text-accent transition-colors">READ FULL CASE STUDY</span>
                        <div class="w-8 h-8 rounded-full bg-[#262626] flex items-center justify-center text-white group-hover:bg-accent group-hover:text-black group-hover/cta:bg-accent group-hover/cta:text-black transition-colors">
                            <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                        </div>
                    </a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Why Choose Us / Approach Section -->
<section class="py-28 bg-primary-dark border-t border-[#222222]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-20">
            <span class="font-mono text-xs text-accent uppercase tracking-widest block mb-3">OUR APPROACH</span>
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-heading font-extrabold text-white tracking-tight">
                Why Industry Leaders Choose NOVA.
            </h2>
            <p class="mt-4 text-offwhite/70 text-base font-light">
                We eliminate the layers between visionary creative thought and flawless real-world execution.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="p-8 rounded-2xl bg-[#171717] border border-[#2B2B2B]">
                <div class="w-12 h-12 rounded-xl bg-accent/10 border border-accent/25 flex items-center justify-center text-accent mb-6">
                    <i data-lucide="compass" class="w-6 h-6"></i>
                </div>
                <h3 class="text-xl font-heading font-bold text-white mb-3">Strategic Rigor</h3>
                <p class="text-sm text-offwhite/70 font-light leading-relaxed">
                    We don't design in a vacuum. Every layout, color choice, and animation serves a documented business objective.
                </p>
            </div>

            <div class="p-8 rounded-2xl bg-[#171717] border border-[#2B2B2B]">
                <div class="w-12 h-12 rounded-xl bg-accent/10 border border-accent/25 flex items-center justify-center text-accent mb-6">
                    <i data-lucide="gem" class="w-6 h-6"></i>
                </div>
                <h3 class="text-xl font-heading font-bold text-white mb-3">Pixel-Perfect Craft</h3>
                <p class="text-sm text-offwhite/70 font-light leading-relaxed">
                    Obsessive attention to micro-details, typographic harmony, and smooth interactive transitions that delight.
                </p>
            </div>

            <div class="p-8 rounded-2xl bg-[#171717] border border-[#2B2B2B]">
                <div class="w-12 h-12 rounded-xl bg-accent/10 border border-accent/25 flex items-center justify-center text-accent mb-6">
                    <i data-lucide="zap" class="w-6 h-6"></i>
                </div>
                <h3 class="text-xl font-heading font-bold text-white mb-3">High Performance</h3>
                <p class="text-sm text-offwhite/70 font-light leading-relaxed">
                    Lightning-fast load speeds, clean semantic architecture, and responsive fluidity across all modern device viewports.
                </p>
            </div>

            <div class="p-8 rounded-2xl bg-[#171717] border border-[#2B2B2B]">
                <div class="w-12 h-12 rounded-xl bg-accent/10 border border-accent/25 flex items-center justify-center text-accent mb-6">
                    <i data-lucide="users-2" class="w-6 h-6"></i>
                </div>
                <h3 class="text-xl font-heading font-bold text-white mb-3">Direct Collaboration</h3>
                <p class="text-sm text-offwhite/70 font-light leading-relaxed">
                    No middlemen or account managers playing telephone. You partner directly with the senior designers and engineers.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-28 bg-[#161616] border-t border-[#222222]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16">
            <div>
                <span class="font-mono text-xs text-accent uppercase tracking-widest block mb-3">ENDORSEMENTS</span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-heading font-extrabold text-white tracking-tight">
                    Trusted by Innovators.
                </h2>
            </div>
            <p class="mt-4 md:mt-0 text-offwhite/60 max-w-sm text-sm">
                Hear what founders and executive leaders say about partnering with our agency.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach ($testimonials as $test): ?>
            <div class="p-8 rounded-2xl bg-primary-dark border border-[#2B2B2B] flex flex-col justify-between">
                <div>
                    <!-- Rating Stars -->
                    <div class="flex items-center space-x-1 text-accent mb-6">
                        <?php for ($i = 0; $i < (int)($test['rating'] ?? 5); $i++): ?>
                            <i data-lucide="star" class="w-4 h-4 fill-accent"></i>
                        <?php endfor; ?>
                    </div>
                    <blockquote class="text-offwhite/90 text-sm leading-relaxed italic mb-8 font-light">
                        "<?= e($test['quote']) ?>"
                    </blockquote>
                </div>
                <div class="flex items-center gap-4 pt-6 border-t border-[#222222]">
                    <div class="w-11 h-11 rounded-full overflow-hidden bg-charcoal border border-[#333333] flex-shrink-0">
                        <img src="<?= upload_url('projects', $test['avatar']) ?>" alt="<?= e($test['client_name']) ?>" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h4 class="font-heading font-bold text-white text-sm"><?= e($test['client_name']) ?></h4>
                        <p class="font-mono text-xs text-muted-gray"><?= e($test['role_company']) ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Call to action block -->
<?php require_once __DIR__ . '/includes/cta-banner.php'; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

