<?php
/**
 * NOVA Creative Digital Agency
 * Case Study Detail Page (project-detail.php)
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/helpers.php';

$slug = trim($_GET['slug'] ?? '');
$id = (int)($_GET['id'] ?? 0);

$db = getDB();

$statusCondition = is_admin() ? "" : " AND status = 'published'";

if (!empty($slug)) {
    $stmt = $db->prepare("SELECT * FROM projects WHERE slug = ?" . $statusCondition . " LIMIT 1");
    $stmt->execute([$slug]);
} else if ($id > 0) {
    $stmt = $db->prepare("SELECT * FROM projects WHERE id = ?" . $statusCondition . " LIMIT 1");
    $stmt->execute([$id]);
} else {
    header('Location: ' . url('work.php'));
    exit;
}

$project = $stmt->fetch();
if (!$project) {
    set_flash('error', 'Project case study not found.');
    header('Location: ' . url('work.php'));
    exit;
}

// Fetch related projects
$relStmt = $db->prepare("SELECT * FROM projects WHERE id != ? AND status = 'published' ORDER BY is_featured DESC LIMIT 2");
$relStmt->execute([$project['id']]);
$relatedProjects = $relStmt->fetchAll();

$pageTitle = $project['title'] . ' — Case Study';
$pageDesc = $project['summary'];

require_once __DIR__ . '/includes/header.php';
?>

<!-- Case Study Hero Header -->
<article class="py-20 bg-primary-dark border-b border-[#222222]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if ($project['status'] !== 'published'): ?>
        <div class="mb-8 p-4 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-300 text-xs font-mono flex items-center justify-between">
            <span class="flex items-center gap-2">
                <i data-lucide="alert-triangle" class="w-4 h-4 text-amber-400"></i>
                <strong>DRAFT PREVIEW:</strong> This case study is currently unpublished and only visible to administrators.
            </span>
            <a href="<?= url('admin/project-edit.php?id=' . $project['id']) ?>" class="underline hover:text-white">Edit in Admin &rarr;</a>
        </div>
        <?php endif; ?>
        <!-- Back Navigation & Meta -->
        <div class="mb-10 flex items-center justify-between">
            <a href="<?= url('work.php') ?>" class="inline-flex items-center gap-2 font-mono text-xs text-accent hover:text-white transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>BACK TO ALL WORKS</span>
            </a>
            <span class="px-3.5 py-1 rounded-full bg-charcoal border border-[#333333] text-xs font-mono text-offwhite/80 uppercase">
                <?= e($project['category_name']) ?>
            </span>
        </div>

        <!-- Project Title -->
        <h1 class="text-3xl sm:text-5xl md:text-6xl lg:text-7xl font-heading font-black text-white tracking-tight leading-[1.08] mb-8 max-w-5xl">
            <?= e($project['title']) ?>
        </h1>

        <!-- Project Summary -->
        <p class="text-xl sm:text-2xl text-offwhite/80 font-light leading-relaxed max-w-3xl mb-12">
            <?= e($project['summary']) ?>
        </p>

        <!-- Project Metadata Ribbon -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 p-6 rounded-2xl bg-[#171717] border border-[#2B2B2B]">
            <div>
                <span class="font-mono text-xs text-muted-gray uppercase block mb-1">CLIENT</span>
                <span class="font-heading font-bold text-white text-base"><?= e($project['client']) ?></span>
            </div>
            <div>
                <span class="font-mono text-xs text-muted-gray uppercase block mb-1">CATEGORY</span>
                <span class="font-heading font-bold text-white text-base"><?= e($project['category_name']) ?></span>
            </div>
            <div>
                <span class="font-mono text-xs text-muted-gray uppercase block mb-1">YEAR</span>
                <span class="font-heading font-bold text-white text-base"><?= e($project['year']) ?></span>
            </div>
            <div>
                <span class="font-mono text-xs text-muted-gray uppercase block mb-1">SERVICES</span>
                <span class="font-heading font-bold text-accent text-base">Strategy, Design, Code</span>
            </div>
        </div>
    </div>
</article>

<!-- Hero Visual Showcase -->
<section class="py-12 bg-black border-b border-[#222222]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="aspect-[16/9] w-full rounded-3xl overflow-hidden border border-[#2B2B2B] bg-[#111111] shadow-2xl">
            <img src="<?= upload_url('projects', $project['featured_image']) ?>" 
                 alt="<?= e($project['title']) ?>" 
                 class="w-full h-full object-cover">
        </div>
    </div>
</section>

<!-- Deep Case Study Content Sections (PRD 4.4) -->
<section class="py-24 bg-[#141414]">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-20">
        <!-- 1. The Objectives -->
        <?php if (!empty($project['objectives'])): ?>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
            <div class="md:col-span-4">
                <span class="font-mono text-xs text-accent uppercase tracking-widest block mb-2">01 // OBJECTIVES</span>
                <h2 class="text-2xl sm:text-3xl font-heading font-extrabold text-white">The Goals &amp; Scope</h2>
            </div>
            <div class="md:col-span-8 text-offwhite/80 font-light text-lg leading-relaxed bg-primary-dark p-8 rounded-2xl border border-[#262626]">
                <?= nl2br(e($project['objectives'])) ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- 2. The Challenges -->
        <?php if (!empty($project['challenges'])): ?>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
            <div class="md:col-span-4">
                <span class="font-mono text-xs text-accent uppercase tracking-widest block mb-2">02 // CHALLENGES</span>
                <h2 class="text-2xl sm:text-3xl font-heading font-extrabold text-white">Complexities Faced</h2>
            </div>
            <div class="md:col-span-8 text-offwhite/80 font-light text-lg leading-relaxed bg-primary-dark p-8 rounded-2xl border border-[#262626]">
                <?= nl2br(e($project['challenges'])) ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- 3. Creative Process -->
        <?php if (!empty($project['creative_process'])): ?>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
            <div class="md:col-span-4">
                <span class="font-mono text-xs text-accent uppercase tracking-widest block mb-2">03 // PROCESS</span>
                <h2 class="text-2xl sm:text-3xl font-heading font-extrabold text-white">Strategic Execution</h2>
            </div>
            <div class="md:col-span-8 text-offwhite/80 font-light text-lg leading-relaxed bg-primary-dark p-8 rounded-2xl border border-[#262626]">
                <?= nl2br(e($project['creative_process'])) ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- 4. Final Output -->
        <?php if (!empty($project['final_output'])): ?>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
            <div class="md:col-span-4">
                <span class="font-mono text-xs text-accent uppercase tracking-widest block mb-2">04 // DELIVERABLES</span>
                <h2 class="text-2xl sm:text-3xl font-heading font-extrabold text-white">The Final Output</h2>
            </div>
            <div class="md:col-span-8 text-offwhite/80 font-light text-lg leading-relaxed bg-primary-dark p-8 rounded-2xl border border-[#262626]">
                <?= nl2br(e($project['final_output'])) ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- 5. Measurable Outcomes -->
        <?php if (!empty($project['outcomes'])): ?>
        <div class="p-8 sm:p-12 rounded-3xl bg-accent/10 border border-accent/30 relative overflow-hidden">
            <div class="relative z-10">
                <span class="font-mono text-xs text-accent uppercase tracking-widest font-bold block mb-3">05 // BUSINESS IMPACT</span>
                <h2 class="text-3xl sm:text-4xl font-heading font-extrabold text-white mb-6">Measurable Outcomes</h2>
                <div class="text-offwhite text-lg sm:text-xl font-light leading-relaxed">
                    <?= nl2br(e($project['outcomes'])) ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Related Projects Navigation -->
<?php if (!empty($relatedProjects)): ?>
<section class="py-24 bg-primary-dark border-t border-[#222222]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-12">
            <div>
                <span class="font-mono text-xs text-accent uppercase tracking-widest block mb-2">NEXT DISCOVERIES</span>
                <h2 class="text-3xl font-heading font-extrabold text-white">Related Case Studies</h2>
            </div>
            <a href="<?= url('work.php') ?>" class="font-mono text-xs text-accent hover:text-white transition-colors">
                VIEW ALL PROJECTS &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <?php foreach ($relatedProjects as $rel): ?>
            <a href="<?= url('project-detail.php?slug=' . urlencode($rel['slug'])) ?>" class="group p-6 rounded-2xl bg-[#171717] border border-[#2B2B2B] hover:border-accent transition-all card-hover flex gap-6 items-center">
                <div class="w-32 h-24 rounded-xl overflow-hidden bg-black flex-shrink-0">
                    <img src="<?= upload_url('projects', $rel['featured_image']) ?>" alt="<?= e($rel['title']) ?>" class="w-full h-full object-cover">
                </div>
                <div>
                    <span class="font-mono text-[11px] text-accent block mb-1"><?= e($rel['category_name']) ?></span>
                    <h3 class="font-heading font-bold text-white text-lg group-hover:text-accent transition-colors leading-snug">
                        <?= e($rel['title']) ?>
                    </h3>
                    <span class="text-xs text-muted-gray mt-1 block font-mono"><?= e($rel['client']) ?> &bull; <?= e($rel['year']) ?></span>
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

