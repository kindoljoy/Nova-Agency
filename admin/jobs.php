<?php
/**
 * Admin Job Openings Management (admin/jobs.php)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helpers.php';

$pageTitle = 'Job Openings';
$db = getDB();

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (verify_csrf()) {
        $delId = (int)($_POST['id'] ?? 0);
        if ($delId > 0) {
            $stmt = $db->prepare("DELETE FROM job_positions WHERE id = ?");
            $stmt->execute([$delId]);
            set_flash('success', 'Job position deleted.');
            header('Location: ' . url('admin/jobs.php'));
            exit;
        }
    }
}

$jobs = $db->query("SELECT * FROM job_positions ORDER BY created_at DESC")->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="text-2xl font-heading font-extrabold text-white">Job Openings (<?= count($jobs) ?>)</h2>
        <p class="text-xs text-muted-gray mt-1">Manage open vacancies shown on the public Careers page.</p>
    </div>
    <a href="<?= url('admin/job-edit.php') ?>" class="btn-primary !py-2.5 !px-5 text-xs flex items-center gap-2 self-start sm:self-auto">
        <i data-lucide="plus" class="w-4 h-4"></i>
        <span>POST NEW JOB VACANCY</span>
    </a>
</div>

<div class="rounded-2xl bg-card-bg border border-border-dark overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-[#141414] text-xs font-mono text-muted-gray uppercase border-b border-border-dark">
                <tr>
                    <th class="px-6 py-4">Title</th>
                    <th class="px-6 py-4">Department</th>
                    <th class="px-6 py-4">Type</th>
                    <th class="px-6 py-4">Location</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-dark/60 font-light text-offwhite/80">
                <?php if (empty($jobs)): ?>
                <tr><td colspan="6" class="px-6 py-12 text-center text-muted-gray font-mono text-xs">No job openings posted.</td></tr>
                <?php else: ?>
                    <?php foreach ($jobs as $j): ?>
                    <tr class="hover:bg-charcoal/40 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-heading font-bold text-white"><?= e($j['title']) ?></div>
                            <div class="text-xs text-muted-gray font-mono">slug: /<?= e($j['slug']) ?></div>
                        </td>
                        <td class="px-6 py-4 text-xs font-mono">
                            <?= e($j['department']) ?>
                        </td>
                        <td class="px-6 py-4 text-xs font-mono">
                            <?= e($j['work_type']) ?>
                        </td>
                        <td class="px-6 py-4 text-xs font-mono text-offwhite/70">
                            <?= e($j['location']) ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if ($j['status'] === 'open'): ?>
                                <span class="px-2.5 py-1 rounded-full text-xs font-mono font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Open</span>
                            <?php else: ?>
                                <span class="px-2.5 py-1 rounded-full text-xs font-mono font-bold bg-muted-gray/20 text-muted-gray border border-border-dark">Closed</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="<?= url('admin/job-edit.php?id=' . $j['id']) ?>" class="p-1.5 rounded bg-charcoal hover:bg-accent hover:text-black inline-block text-xs font-mono transition-colors" title="Edit Job">
                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                            </a>
                            <form action="<?= url('admin/jobs.php') ?>" method="POST" class="inline" onsubmit="return confirm('Delete this job position?');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $j['id'] ?>">
                                <button type="submit" class="p-1.5 rounded bg-charcoal hover:bg-rose-950 text-muted-gray hover:text-rose-300 transition-colors">
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

