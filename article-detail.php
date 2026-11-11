<?php
/**
 * NOVA Creative Digital Agency
 * Article Detail Page (article-detail.php)
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/helpers.php';

$slug = trim($_GET['slug'] ?? '');
$id = (int)($_GET['id'] ?? 0);

$db = getDB();

$statusCondition = is_admin() ? "" : " AND a.status = 'published'";

if (!empty($slug)) {
    $stmt = $db->prepare("SELECT a.*, c.name as category_name FROM articles a LEFT JOIN categories c ON a.category_id = c.id WHERE a.slug = ?" . $statusCondition . " LIMIT 1");
    $stmt->execute([$slug]);
} else if ($id > 0) {
    $stmt = $db->prepare("SELECT a.*, c.name as category_name FROM articles a LEFT JOIN categories c ON a.category_id = c.id WHERE a.id = ?" . $statusCondition . " LIMIT 1");
    $stmt->execute([$id]);
} else {
    header('Location: ' . url('insights.php'));
    exit;
}

$article = $stmt->fetch();
if (!$article) {
    set_flash('error', 'Article could not be found.');
    header('Location: ' . url('insights.php'));
    exit;
}

// Fetch related articles
$relStmt = $db->prepare("SELECT a.*, c.name as category_name FROM articles a LEFT JOIN categories c ON a.category_id = c.id WHERE a.id != ? AND a.status = 'published' ORDER BY a.publish_date DESC LIMIT 2");
$relStmt->execute([$article['id']]);
$relatedArticles = $relStmt->fetchAll();

$pageTitle = $article['title'];
$pageDesc = $article['excerpt'];

require_once __DIR__ . '/includes/header.php';
?>

<!-- Article Header Section -->
<article class="py-20 bg-primary-dark border-b border-[#222222]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <a href="<?= url('insights.php') ?>" class="inline-flex items-center gap-2 font-mono text-xs text-accent hover:text-white transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>BACK TO INSIGHTS</span>
            </a>
        </div>

        <div class="flex items-center gap-3 text-xs font-mono text-muted-gray mb-6">
            <span class="text-accent uppercase font-bold tracking-wider"><?= e($article['category_name'] ?? 'Insight') ?></span>
            <span>&bull;</span>
            <span><?= date('F d, Y', strtotime($article['publish_date'] ?? 'now')) ?></span>
            <span>&bull;</span>
            <span><?= e($article['read_time'] ?? '5 min read') ?></span>
        </div>

        <h1 class="text-3xl sm:text-5xl md:text-6xl font-heading font-black text-white tracking-tight leading-[1.1] mb-8">
            <?= e($article['title']) ?>
        </h1>

        <p class="text-xl sm:text-2xl text-offwhite/80 font-light leading-relaxed mb-10">
            <?= e($article['excerpt']) ?>
        </p>

        <div class="flex items-center justify-between pt-6 border-t border-[#262626]">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-accent/20 border border-accent/40 flex items-center justify-center font-heading font-bold text-accent text-sm">
                    <?= substr($article['author'] ?? 'Alex', 0, 1) ?>
                </div>
                <div>
                    <span class="text-sm font-heading font-bold text-white block"><?= e($article['author'] ?? 'Alex Nova') ?></span>
                    <span class="text-xs font-mono text-muted-gray">Author &bull; NOVA Agency</span>
                </div>
            </div>

            <!-- Social Share Links -->
            <div class="flex items-center space-x-3 text-offwhite/60">
                <span class="text-xs font-mono hidden sm:inline">SHARE:</span>
                <a href="https://twitter.com/intent/tweet?text=<?= urlencode($article['title']) ?>&url=<?= urlencode(url('article-detail.php?slug=' . $article['slug'])) ?>" target="_blank" rel="noopener" class="p-2 rounded-full hover:bg-charcoal hover:text-white transition-colors" aria-label="Share on X">
                    <i data-lucide="twitter" class="w-4 h-4"></i>
                </a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode(url('article-detail.php?slug=' . $article['slug'])) ?>" target="_blank" rel="noopener" class="p-2 rounded-full hover:bg-charcoal hover:text-white transition-colors" aria-label="Share on LinkedIn">
                    <i data-lucide="linkedin" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
    </div>
</article>

<!-- Article Cover Hero Image -->
<section class="py-12 bg-black border-b border-[#222222]">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="aspect-[16/9] w-full rounded-3xl overflow-hidden border border-[#2B2B2B] bg-[#111111]">
            <img src="<?= upload_url('articles', $article['cover_image']) ?>" 
                 alt="<?= e($article['title']) ?>" 
                 class="w-full h-full object-cover">
        </div>
    </div>
</section>

<!-- Article Body Content -->
<section class="py-24 bg-[#141414]">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="prose prose-invert prose-lg max-w-none text-offwhite/80 font-light leading-relaxed">
            <?= $article['body'] ?>
        </div>

        <div class="mt-16 pt-10 border-t border-[#262626] flex flex-col sm:flex-row items-center justify-between gap-6">
            <span class="text-sm font-mono text-muted-gray">CATEGORIZED UNDER: <strong class="text-white"><?= e($article['category_name']) ?></strong></span>
            <a href="<?= url('insights.php') ?>" class="btn-secondary text-xs">
                VIEW MORE INSIGHTS
            </a>
        </div>
    </div>
</section>

<!-- Related Articles Section -->
<?php if (!empty($relatedArticles)): ?>
<section class="py-24 bg-primary-dark border-t border-[#222222]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-12">
            <div>
                <span class="font-mono text-xs text-accent uppercase tracking-widest block mb-2">CONTINUE READING</span>
                <h2 class="text-3xl font-heading font-extrabold text-white">Related Essays</h2>
            </div>
            <a href="<?= url('insights.php') ?>" class="font-mono text-xs text-accent hover:text-white transition-colors">
                VIEW ALL ARTICLES &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <?php foreach ($relatedArticles as $rel): ?>
            <a href="<?= url('article-detail.php?slug=' . urlencode($rel['slug'])) ?>" class="group p-6 rounded-2xl bg-[#171717] border border-[#2B2B2B] hover:border-accent transition-all card-hover flex gap-6 items-center">
                <div class="w-32 h-24 rounded-xl overflow-hidden bg-black flex-shrink-0">
                    <img src="<?= upload_url('articles', $rel['cover_image']) ?>" alt="<?= e($rel['title']) ?>" class="w-full h-full object-cover">
                </div>
                <div>
                    <span class="font-mono text-[11px] text-accent block mb-1"><?= e($rel['category_name']) ?> &bull; <?= e($rel['read_time']) ?></span>
                    <h3 class="font-heading font-bold text-white text-lg group-hover:text-accent transition-colors leading-snug">
                        <?= e($rel['title']) ?>
                    </h3>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Call to action block -->
<?php require_once __DIR__ . '/includes/cta-banner.php'; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

