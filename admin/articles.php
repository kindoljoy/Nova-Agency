<?php
/**
 * Admin Articles Management (admin/articles.php)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helpers.php';

$pageTitle = 'Manage Articles & Insights';
$db = getDB();

// Handle Delete Article
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (verify_csrf()) {
        $delId = (int)($_POST['id'] ?? 0);
        if ($delId > 0) {
            $stmt = $db->prepare("DELETE FROM articles WHERE id = ?");
            $stmt->execute([$delId]);
            set_flash('success', 'Article has been deleted.');
            header('Location: ' . url('admin/articles.php'));
            exit;
        }
    } else {
        set_flash('error', 'Security token invalid.');
    }
}

// Fetch articles with categories
$artStmt = $db->query("SELECT a.*, c.name as category_name FROM articles a LEFT JOIN categories c ON a.category_id = c.id ORDER BY a.publish_date DESC, a.created_at DESC");
$articles = $artStmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="text-2xl font-heading font-extrabold text-white">Insights &amp; Articles (<?= count($articles) ?>)</h2>
        <p class="text-xs text-muted-gray mt-1">Manage thought leadership essays, design frameworks, and agency perspectives.</p>
    </div>
    <a href="<?= url('admin/article-edit.php') ?>" class="btn-primary !py-2.5 !px-5 text-xs flex items-center gap-2 self-start sm:self-auto">
        <i data-lucide="plus" class="w-4 h-4"></i>
        <span>WRITE NEW ARTICLE</span>
    </a>
</div>

<div class="rounded-2xl bg-card-bg border border-border-dark overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-[#141414] text-xs font-mono text-muted-gray uppercase border-b border-border-dark">
                <tr>
                    <th class="px-6 py-4">Cover</th>
                    <th class="px-6 py-4">Title &amp; Author</th>
                    <th class="px-6 py-4">Category</th>
                    <th class="px-6 py-4">Read Time</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-dark/60 font-light text-offwhite/80">
                <?php if (empty($articles)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-muted-gray font-mono text-xs">
                        No articles published yet.
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($articles as $art): ?>
                    <tr class="hover:bg-charcoal/40 transition-colors">
                        <td class="px-6 py-4">
                            <div class="w-16 h-10 rounded-lg bg-black overflow-hidden border border-border-dark flex-shrink-0">
                                <img src="<?= upload_url('articles', $art['cover_image']) ?>" alt="" class="w-full h-full object-cover">
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-heading font-bold text-white"><?= e($art['title']) ?></div>
                            <div class="text-xs text-muted-gray font-mono">By <?= e($art['author']) ?> &bull; slug: /<?= e($art['slug']) ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded bg-[#202020] text-xs font-mono text-offwhite">
                                <?= e($art['category_name'] ?? 'Uncategorized') ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs font-mono text-muted-gray">
                            <?= e($art['read_time']) ?>
                        </td>
                        <td class="px-6 py-4 text-xs font-mono text-muted-gray">
                            <?= date('M d, Y', strtotime($art['publish_date'] ?? 'now')) ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if ($art['status'] === 'published'): ?>
                                <span class="px-2.5 py-1 rounded-full text-xs font-mono font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Published</span>
                            <?php else: ?>
                                <span class="px-2.5 py-1 rounded-full text-xs font-mono font-bold bg-muted-gray/20 text-muted-gray border border-border-dark">Draft</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="<?= url('article-detail.php?slug=' . urlencode($art['slug'])) ?>" target="_blank" class="p-1.5 rounded bg-charcoal hover:bg-[#2A2A2A] text-offwhite inline-block transition-colors" title="View Article">
                                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                            </a>
                            <a href="<?= url('admin/article-edit.php?id=' . $art['id']) ?>" class="p-1.5 rounded bg-charcoal hover:bg-accent hover:text-black text-offwhite inline-block transition-colors" title="Edit Article">
                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                            </a>
                            <form action="<?= url('admin/articles.php') ?>" method="POST" class="inline" onsubmit="return confirm('Permanently delete this article?');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $art['id'] ?>">
                                <button type="submit" class="p-1.5 rounded bg-charcoal hover:bg-rose-950 text-muted-gray hover:text-rose-300 transition-colors" title="Delete Article">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

