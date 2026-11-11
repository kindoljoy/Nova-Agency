<?php
/**
 * NOVA Creative Digital Agency
 * Insights & Articles Page (insights.php)
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/helpers.php';

$pageTitle = 'Insights & Perspectives — NOVA®';
$pageDesc = 'Essays, design frameworks, and industry analysis on branding, product design, and high-performance digital engineering.';

$db = getDB();

$search = trim($_GET['q'] ?? '');
$catFilter = trim($_GET['category'] ?? '');

// Fetch article categories
$catStmt = $db->query("SELECT * FROM categories WHERE type = 'article' ORDER BY name ASC");
$categories = $catStmt->fetchAll();

// Query articles with search and category filtering
$sql = "SELECT a.*, c.name as category_name FROM articles a LEFT JOIN categories c ON a.category_id = c.id WHERE a.status = 'published'";
$params = [];

if (!empty($search)) {
    $sql .= " AND (a.title LIKE ? OR a.excerpt LIKE ? OR a.body LIKE ?)";
    $like = "%{$search}%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

if (!empty($catFilter) && $catFilter !== 'all') {
    $sql .= " AND (c.slug = ? OR c.name = ?)";
    $params[] = $catFilter;
    $params[] = $catFilter;
}

$sql .= " ORDER BY a.is_featured DESC, a.publish_date DESC";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$articles = $stmt->fetchAll();

// Pick featured article if exists and no specific search
$featuredArticle = null;
if (empty($search) && (empty($catFilter) || $catFilter === 'all')) {
    foreach ($articles as $index => $art) {
        if (!empty($art['is_featured'])) {
            $featuredArticle = $art;
            unset($articles[$index]);
            break;
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Insights Hero Section -->
<section class="py-24 bg-primary-dark border-b border-[#222222] relative overflow-hidden">
    <div class="absolute left-1/4 top-0 w-96 h-96 bg-accent/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-4xl">
            <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-mono uppercase tracking-wider text-accent bg-accent/10 border border-accent/20 mb-6">
                AGENCY PERSPECTIVES
            </span>
            <h1 class="text-4xl sm:text-6xl md:text-7xl font-heading font-black text-white tracking-tight leading-[1.08] mb-8">
                Thinking out loud. Insights on craft and culture.
            </h1>
            <p class="text-xl sm:text-2xl text-offwhite/70 font-light leading-relaxed max-w-3xl">
                Essays, architectural breakdowns, and strategic blueprints from our creative directors and engineering leads.
            </p>
        </div>

        <!-- Search & Filter Controls -->
        <div class="mt-12 flex flex-col md:flex-row items-center justify-between gap-6 pt-10 border-t border-[#262626]">
            <!-- Search Input -->
            <form action="<?= url('insights.php') ?>" method="GET" class="w-full md:w-96 relative">
                <input type="text" 
                       name="q" 
                       value="<?= e($search) ?>" 
                       placeholder="Search insights, topics, keywords..." 
                       class="w-full bg-[#181818] border border-[#333333] focus:border-accent text-white text-sm rounded-full pl-12 pr-4 py-3 focus:outline-none transition-colors">
                <i data-lucide="search" class="w-4 h-4 text-muted-gray absolute left-4 top-1/2 -translate-y-1/2"></i>
                <?php if (!empty($search)): ?>
                    <a href="<?= url('insights.php') ?>" class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-mono text-muted-gray hover:text-white">Clear</a>
                <?php endif; ?>
            </form>

            <!-- Category Pills -->
            <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-2 md:pb-0 no-scrollbar">
                <a href="<?= url('insights.php') ?>" 
                   class="px-4 py-2 rounded-full text-xs font-mono uppercase tracking-wider transition-all border <?= (empty($catFilter) || $catFilter === 'all') ? 'bg-accent text-black font-bold border-accent' : 'bg-transparent text-offwhite/70 border-[#333333] hover:border-offwhite' ?>">
                    All Topics
                </a>
                <?php foreach ($categories as $cat): 
                    $isActive = ($catFilter === $cat['slug'] || $catFilter === $cat['name']);
                ?>
                <a href="<?= url('insights.php?category=' . urlencode($cat['slug'])) ?>" 
                   class="px-4 py-2 rounded-full text-xs font-mono uppercase tracking-wider whitespace-nowrap transition-all border <?= $isActive ? 'bg-accent text-black font-bold border-accent' : 'bg-transparent text-offwhite/70 border-[#333333] hover:border-offwhite' ?>">
                    <?= e($cat['name']) ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Insights Listing Section -->
<section class="py-24 bg-[#141414]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Featured Article Hero Card (if present) -->
        <?php if ($featuredArticle): ?>
        <div class="mb-20">
            <span class="font-mono text-xs text-accent uppercase tracking-widest block mb-4">FEATURED ESSAY</span>
            <article class="group rounded-3xl overflow-hidden bg-primary-dark border border-[#2B2B2B] hover:border-accent transition-all duration-300 grid grid-cols-1 lg:grid-cols-12 items-center">
                <a href="<?= url('article-detail.php?slug=' . urlencode($featuredArticle['slug'])) ?>" class="lg:col-span-7 aspect-[16/10] overflow-hidden bg-black block">
                    <img src="<?= upload_url('articles', $featuredArticle['cover_image']) ?>" 
                         alt="<?= e($featuredArticle['title']) ?>" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                </a>
                <div class="lg:col-span-5 p-8 sm:p-12 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-3 text-xs font-mono text-muted-gray mb-4">
                            <span class="text-accent"><?= e($featuredArticle['category_name'] ?? 'Design Trends') ?></span>
                            <span>&bull;</span>
                            <span><?= e($featuredArticle['read_time'] ?? '6 min read') ?></span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-heading font-black text-white group-hover:text-accent transition-colors mb-4 leading-tight">
                            <a href="<?= url('article-detail.php?slug=' . urlencode($featuredArticle['slug'])) ?>">
                                <?= e($featuredArticle['title']) ?>
                            </a>
                        </h2>
                        <p class="text-offwhite/70 font-light text-base leading-relaxed mb-8">
                            <?= e($featuredArticle['excerpt']) ?>
                        </p>
                    </div>
                    <a href="<?= url('article-detail.php?slug=' . urlencode($featuredArticle['slug'])) ?>" class="flex items-center justify-between pt-6 border-t border-[#262626] group/cta">
                        <div class="text-xs font-mono text-muted-gray">
                            By <span class="text-white"><?= e($featuredArticle['author'] ?? 'Alex Nova') ?></span>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-charcoal flex items-center justify-center text-white group-hover/cta:bg-accent group-hover/cta:text-black transition-colors">
                            <i data-lucide="arrow-up-right" class="w-5 h-5"></i>
                        </div>
                    </a>
                </div>
            </article>
        </div>
        <?php endif; ?>

        <!-- Standard Articles Grid -->
        <?php if (empty($articles) && !$featuredArticle): ?>
        <div class="py-24 text-center">
            <i data-lucide="book-open" class="w-12 h-12 text-muted-gray mx-auto mb-4"></i>
            <h3 class="text-xl font-heading font-bold text-white mb-2">No articles found</h3>
            <p class="text-sm text-offwhite/60">Try searching for other keywords or explore all topics.</p>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($articles as $art): ?>
            <article class="group rounded-2xl overflow-hidden bg-primary-dark border border-[#2B2B2B] hover:border-accent/60 transition-all card-hover flex flex-col justify-between">
                <div>
                    <a href="<?= url('article-detail.php?slug=' . urlencode($art['slug'])) ?>" class="aspect-[16/10] overflow-hidden bg-black block relative">
                        <img src="<?= upload_url('articles', $art['cover_image']) ?>" 
                             alt="<?= e($art['title']) ?>" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out"
                             loading="lazy">
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 rounded-full bg-black/80 backdrop-blur-md text-[10px] font-mono text-accent border border-white/10 uppercase">
                                <?= e($art['category_name'] ?? 'Insight') ?>
                            </span>
                        </div>
                    </a>
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center gap-2 text-xs font-mono text-muted-gray mb-3">
                            <span><?= date('M d, Y', strtotime($art['publish_date'] ?? 'now')) ?></span>
                            <span>&bull;</span>
                            <span><?= e($art['read_time'] ?? '5 min read') ?></span>
                        </div>
                        <h3 class="text-xl font-heading font-bold text-white group-hover:text-accent transition-colors leading-snug mb-3">
                            <a href="<?= url('article-detail.php?slug=' . urlencode($art['slug'])) ?>">
                                <?= e($art['title']) ?>
                            </a>
                        </h3>
                        <p class="text-sm text-offwhite/70 font-light line-clamp-3 leading-relaxed mb-6">
                            <?= e($art['excerpt']) ?>
                        </p>
                    </div>
                </div>
                <a href="<?= url('article-detail.php?slug=' . urlencode($art['slug'])) ?>" class="p-6 sm:p-8 pt-4 flex items-center justify-between border-t border-[#262626]/50 group/cta text-muted-gray hover:text-white transition-colors">
                    <span class="text-xs font-mono">By <?= e($art['author'] ?? 'NOVA Team') ?></span>
                    <span class="text-xs font-mono text-accent group-hover/cta:text-white flex items-center gap-1 transition-colors">
                        READ ARTICLE &rarr;
                    </span>
                </a>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Call to action block -->
<?php require_once __DIR__ . '/includes/cta-banner.php'; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

