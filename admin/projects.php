<?php
/**
 * Admin Portfolio Projects Management (admin/projects.php)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helpers.php';

$pageTitle = 'Portfolio & Case Studies';
$db = getDB();

// Handle Delete Project
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (verify_csrf()) {
        $delId = (int)($_POST['id'] ?? 0);
        if ($delId > 0) {
            $stmt = $db->prepare("DELETE FROM projects WHERE id = ?");
            $stmt->execute([$delId]);
            set_flash('success', 'Project has been deleted.');
            header('Location: ' . url('admin/projects.php'));
            exit;
        }
    } else {
        set_flash('error', 'Security token invalid.');
    }
}

// Fetch all projects
$projectsStmt = $db->query("SELECT * FROM projects ORDER BY is_featured DESC, display_order ASC, created_at DESC");
$projects = $projectsStmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="text-2xl font-heading font-extrabold text-white">Portfolio Archive (<?= count($projects) ?>)</h2>
        <p class="text-xs text-muted-gray mt-1">Manage project case studies, client work showcases, and publication states.</p>
    </div>
    <a href="<?= url('admin/project-edit.php') ?>" class="btn-primary !py-2.5 !px-5 text-xs flex items-center gap-2 self-start sm:self-auto">
        <i data-lucide="plus" class="w-4 h-4"></i>
        <span>CREATE NEW PROJECT</span>
    </a>
</div>

<div class="rounded-2xl bg-card-bg border border-border-dark overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-[#141414] text-xs font-mono text-muted-gray uppercase border-b border-border-dark">
                <tr>
                    <th class="px-6 py-4">Thumbnail</th>
                    <th class="px-6 py-4">Project Title &amp; Client</th>
                    <th class="px-6 py-4">Category</th>
                    <th class="px-6 py-4">Year</th>
                    <th class="px-6 py-4">Featured</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-dark/60 font-light text-offwhite/80">
                <?php if (empty($projects)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-muted-gray font-mono text-xs">
                        No projects created yet. Click "Create New Project" to get started.
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($projects as $proj): ?>
                    <tr class="hover:bg-charcoal/40 transition-colors">
                        <td class="px-6 py-4">
                            <div class="w-16 h-12 rounded-lg bg-black overflow-hidden border border-border-dark flex-shrink-0">
                                <img src="<?= upload_url('projects', $proj['featured_image']) ?>" alt="" class="w-full h-full object-cover">
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-heading font-bold text-white"><?= e($proj['title']) ?></div>
                            <div class="text-xs text-muted-gray font-mono"><?= e($proj['client']) ?> &bull; slug: /<?= e($proj['slug']) ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded bg-[#202020] text-xs font-mono text-offwhite">
                                <?= e($proj['category_name']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs font-mono">
                            <?= e($proj['year']) ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if (!empty($proj['is_featured'])): ?>
                                <span class="px-2 py-0.5 rounded text-[11px] font-mono font-bold bg-accent/20 text-accent border border-accent/30">YES</span>
                            <?php else: ?>
                                <span class="text-xs font-mono text-muted-gray">No</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if ($proj['status'] === 'published'): ?>
                                <span class="px-2.5 py-1 rounded-full text-xs font-mono font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Published</span>
                            <?php else: ?>
                                <span class="px-2.5 py-1 rounded-full text-xs font-mono font-bold bg-muted-gray/20 text-muted-gray border border-border-dark">Draft</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="<?= url('project-detail.php?slug=' . urlencode($proj['slug'])) ?>" target="_blank" class="p-1.5 rounded bg-charcoal hover:bg-[#2A2A2A] text-offwhite inline-block transition-colors" title="View Public Page">
                                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                            </a>
                            <a href="<?= url('admin/project-edit.php?id=' . $proj['id']) ?>" class="p-1.5 rounded bg-charcoal hover:bg-accent hover:text-black text-offwhite inline-block transition-colors" title="Edit Project">
                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                            </a>
                            <form action="<?= url('admin/projects.php') ?>" method="POST" class="inline" onsubmit="return confirm('Permanently delete this project?');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $proj['id'] ?>">
                                <button type="submit" class="p-1.5 rounded bg-charcoal hover:bg-rose-950 text-muted-gray hover:text-rose-300 transition-colors" title="Delete Project">
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

