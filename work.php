<?php
/**
 * NOVA Creative Digital Agency
 * Portfolio / Work Page (work.php)
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/helpers.php';

$pageTitle = 'Selected Work & Case Studies — NOVA®';
$pageDesc = 'Explore our portfolio of brand identities, digital products, 3D web platforms, and high-impact digital campaigns.';

$db = getDB();

// Fetch categories for filtering
$catStmt = $db->query("SELECT * FROM categories WHERE type = 'project' ORDER BY name ASC");
$categories = $catStmt->fetchAll();

// Fetch projects
$selectedCategory = trim($_GET['category'] ?? 'all');

if ($selectedCategory !== 'all' && !empty($selectedCategory)) {
    $projStmt = $db->prepare("SELECT * FROM projects WHERE status = 'published' AND (category_name = ? OR slug = ?) ORDER BY is_featured DESC, display_order ASC");
    $projStmt->execute([$selectedCategory, $selectedCategory]);
} else {
    $projStmt = $db->query("SELECT * FROM projects WHERE status = 'published' ORDER BY is_featured DESC, display_order ASC");
}
$projects = $projStmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<!-- Work Hero Section -->
<section class="py-24 bg-primary-dark border-b border-[#222222] relative overflow-hidden">
    <div class="absolute right-0 top-1/4 w-[500px] h-[500px] bg-accent/10 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-4xl">
            <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-mono uppercase tracking-wider text-accent bg-accent/10 border border-accent/20 mb-6">
                CASE STUDIES ARCHIVE
            </span>
            <h1 class="text-4xl sm:text-6xl md:text-7xl font-heading font-black text-white tracking-tight leading-[1.08] mb-8">
                Crafted with intention. Engineered to lead.
            </h1>
            <p class="text-xl sm:text-2xl text-offwhite/70 font-light leading-relaxed max-w-3xl">
                A curated selection of our work across branding, user interface design, full-stack web platforms, and global digital campaigns.
            </p>
        </div>
    </div>
</section>

<!-- Filter Tabs & Portfolio Grid -->
<section class="py-20 bg-[#141414] min-h-[600px]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Interactive Category Filter Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-16 no-scrollbar">
            <button type="button" 
                    data-category="all" 
                    class="portfolio-filter-btn px-5 py-2.5 rounded-full text-xs font-mono uppercase tracking-wider transition-all duration-300 border <?= ($selectedCategory === 'all') ? 'bg-accent text-black border-accent font-bold' : 'bg-transparent text-offwhite/70 border-[#333333] hover:border-offwhite' ?>">
                All Works (<?= count($projects) ?>)
            </button>
            <?php foreach ($categories as $cat): 
                $isActive = ($selectedCategory === $cat['name']);
            ?>
            <button type="button" 
                    data-category="<?= e($cat['name']) ?>" 
                    class="portfolio-filter-btn px-5 py-2.5 rounded-full text-xs font-mono uppercase tracking-wider transition-all duration-300 border <?= $isActive ? 'bg-accent text-black border-accent font-bold' : 'bg-transparent text-offwhite/70 border-[#333333] hover:border-offwhite' ?>">
                <?= e($cat['name']) ?>
            </button>
            <?php endforeach; ?>
        </div>

        <!-- Portfolio Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <?php if (empty($projects)): ?>
            <div class="col-span-2 py-24 text-center">
                <i data-lucide="folder-open" class="w-12 h-12 text-muted-gray mx-auto mb-4"></i>
                <h3 class="text-xl font-heading font-bold text-white mb-2">No projects found</h3>
                <p class="text-sm text-offwhite/60">No case studies currently published under this category filter.</p>
            </div>
            <?php else: ?>
                <?php foreach ($projects as $project): ?>
                <article data-category="<?= e($project['category_name']) ?>" 
                         class="portfolio-item group rounded-3xl overflow-hidden bg-primary-dark border border-[#2B2B2B] hover:border-accent/60 transition-all duration-300 card-hover flex flex-col">
                    <a href="<?= url('project-detail.php?slug=' . urlencode($project['slug'])) ?>" class="block relative aspect-[16/10] overflow-hidden bg-black">
                        <img src="<?= upload_url('projects', $project['featured_image']) ?>" 
                             alt="<?= e($project['title']) ?>" 
                             class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700 ease-out"
                             loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-60 group-hover:opacity-30 transition-opacity"></div>
                        <div class="absolute top-5 left-5">
                            <span class="px-3.5 py-1.5 rounded-full bg-black/80 backdrop-blur-md text-[11px] font-mono text-accent border border-white/10 uppercase tracking-wider">
                                <?= e($project['category_name']) ?>
                            </span>
                        </div>
                    </a>
                    <div class="p-8 sm:p-10 flex-grow flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between text-xs font-mono text-muted-gray mb-3">
                                <span class="text-white font-medium"><?= e($project['client']) ?></span>
                                <span>YEAR <?= e($project['year']) ?></span>
                            </div>
                            <h2 class="text-2xl sm:text-3xl font-heading font-bold text-white group-hover:text-accent transition-colors mb-4">
                                <a href="<?= url('project-detail.php?slug=' . urlencode($project['slug'])) ?>">
                                    <?= e($project['title']) ?>
                                </a>
                            </h2>
                            <p class="text-sm text-offwhite/70 line-clamp-3 font-light leading-relaxed mb-6">
                                <?= e($project['summary']) ?>
                            </p>
                        </div>
                        <a href="<?= url('project-detail.php?slug=' . urlencode($project['slug'])) ?>" class="pt-6 border-t border-[#262626] flex items-center justify-between group/cta text-offwhite/60 hover:text-accent transition-colors">
                            <span class="text-xs font-mono font-medium group-hover/cta:text-accent transition-colors">EXPLORE IN-DEPTH CASE STUDY</span>
                            <div class="w-10 h-10 rounded-full bg-charcoal border border-[#333333] flex items-center justify-center text-white group-hover:bg-accent group-hover:text-black group-hover:border-accent group-hover/cta:bg-accent group-hover/cta:text-black group-hover/cta:border-accent transition-colors">
                                <i data-lucide="arrow-up-right" class="w-5 h-5"></i>
                            </div>
                        </a>
                    </div>
                </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Call to action block -->
<?php require_once __DIR__ . '/includes/cta-banner.php'; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

