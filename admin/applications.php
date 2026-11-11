<?php
/**
 * Admin Job Applications Management (admin/applications.php)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helpers.php';

$pageTitle = 'Candidate Applications';
$db = getDB();

// Handle Status Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    if (verify_csrf()) {
        $appId = (int)($_POST['id'] ?? 0);
        $newStatus = trim($_POST['status'] ?? 'Pending');
        if ($appId > 0) {
            $stmt = $db->prepare("UPDATE applications SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$newStatus, $appId]);
            set_flash('success', 'Application #' . $appId . ' status set to ' . $newStatus);
            header('Location: ' . url('admin/applications.php'));
            exit;
        }
    }
}

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (verify_csrf()) {
        $delId = (int)($_POST['id'] ?? 0);
        if ($delId > 0) {
            // Fetch CV file to delete if exists
            $stmt = $db->prepare("SELECT cv_path FROM applications WHERE id = ?");
            $stmt->execute([$delId]);
            $row = $stmt->fetch();
            if ($row && !empty($row['cv_path'])) {
                $cvFile = CV_UPLOADS_PATH . DIRECTORY_SEPARATOR . basename($row['cv_path']);
                if (file_exists($cvFile)) {
                    @unlink($cvFile);
                }
            }

            $delStmt = $db->prepare("DELETE FROM applications WHERE id = ?");
            $delStmt->execute([$delId]);
            set_flash('success', 'Candidate application removed.');
            header('Location: ' . url('admin/applications.php'));
            exit;
        }
    }
}

$statusFilter = trim($_GET['status'] ?? '');
$sql = "SELECT a.*, j.title as job_title, j.department FROM applications a LEFT JOIN job_positions j ON a.job_id = j.id WHERE 1=1";
$params = [];

if (!empty($statusFilter) && $statusFilter !== 'all') {
    $sql .= " AND a.status = ?";
    $params[] = $statusFilter;
}

$sql .= " ORDER BY a.created_at DESC";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$applications = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="text-2xl font-heading font-extrabold text-white">Job Applications (<?= count($applications) ?>)</h2>
        <p class="text-xs text-muted-gray mt-1">Review candidates, access CV files securely, and manage hiring pipeline statuses.</p>
    </div>

    <!-- Status Filters -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 sm:pb-0">
        <a href="<?= url('admin/applications.php') ?>" class="px-3.5 py-1.5 rounded-lg text-xs font-mono uppercase tracking-wider border <?= (empty($statusFilter) || $statusFilter === 'all') ? 'bg-accent text-black font-bold border-accent' : 'bg-card-bg text-offwhite/70 border-border-dark hover:border-offwhite' ?>">
            All
        </a>
        <a href="<?= url('admin/applications.php?status=Pending') ?>" class="px-3.5 py-1.5 rounded-lg text-xs font-mono uppercase tracking-wider border <?= ($statusFilter === 'Pending') ? 'bg-accent text-black font-bold border-accent' : 'bg-card-bg text-offwhite/70 border-border-dark hover:border-offwhite' ?>">
            Pending
        </a>
        <a href="<?= url('admin/applications.php?status=Reviewed') ?>" class="px-3.5 py-1.5 rounded-lg text-xs font-mono uppercase tracking-wider border <?= ($statusFilter === 'Reviewed') ? 'bg-accent text-black font-bold border-accent' : 'bg-card-bg text-offwhite/70 border-border-dark hover:border-offwhite' ?>">
            Reviewed
        </a>
        <a href="<?= url('admin/applications.php?status=Shortlisted') ?>" class="px-3.5 py-1.5 rounded-lg text-xs font-mono uppercase tracking-wider border <?= ($statusFilter === 'Shortlisted') ? 'bg-accent text-black font-bold border-accent' : 'bg-card-bg text-offwhite/70 border-border-dark hover:border-offwhite' ?>">
            Shortlisted
        </a>
        <a href="<?= url('admin/applications.php?status=Rejected') ?>" class="px-3.5 py-1.5 rounded-lg text-xs font-mono uppercase tracking-wider border <?= ($statusFilter === 'Rejected') ? 'bg-accent text-black font-bold border-accent' : 'bg-card-bg text-offwhite/70 border-border-dark hover:border-offwhite' ?>">
            Rejected
        </a>
    </div>
</div>

<div class="rounded-2xl bg-card-bg border border-border-dark overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-[#141414] text-xs font-mono text-muted-gray uppercase border-b border-border-dark">
                <tr>
                    <th class="px-6 py-4">Applicant</th>
                    <th class="px-6 py-4">Applied Role</th>
                    <th class="px-6 py-4">Portfolio Link</th>
                    <th class="px-6 py-4">CV / Resume</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-dark/60 font-light text-offwhite/80">
                <?php if (empty($applications)): ?>
                <tr><td colspan="7" class="px-6 py-12 text-center text-muted-gray font-mono text-xs">No job applications submitted yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($applications as $app): 
                        $statusClass = ($app['status'] === 'Pending') ? 'bg-purple-500/20 text-purple-300 border-purple-500/30' :
                                       (($app['status'] === 'Reviewed') ? 'bg-blue-500/20 text-blue-300 border-blue-500/30' :
                                       (($app['status'] === 'Shortlisted') ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30' :
                                       'bg-rose-500/20 text-rose-300 border-rose-500/30'));
                    ?>
                    <tr class="hover:bg-charcoal/40 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-heading font-bold text-white"><?= e($app['full_name']) ?></div>
                            <div class="text-xs text-muted-gray font-mono"><?= e($app['email']) ?></div>
                            <?php if (!empty($app['phone'])): ?>
                                <span class="text-[11px] text-offwhite/60 font-mono"><?= e($app['phone']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($app['cover_note'])): ?>
                                <p class="text-xs text-offwhite/70 italic mt-1 line-clamp-2">"<?= e($app['cover_note']) ?>"</p>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded bg-[#202020] text-xs font-mono text-offwhite">
                                <?= e($app['job_title'] ?? 'Open Application') ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs font-mono">
                            <?php if (!empty($app['portfolio_url'])): ?>
                                <a href="<?= e($app['portfolio_url']) ?>" target="_blank" rel="noopener" class="text-accent hover:underline flex items-center gap-1">
                                    <span>View Portfolio</span>
                                    <i data-lucide="external-link" class="w-3 h-3"></i>
                                </a>
                            <?php else: ?>
                                <span class="text-muted-gray">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if (!empty($app['cv_path'])): ?>
                                <a href="<?= url('admin/download-cv.php?file=' . urlencode($app['cv_path'])) ?>" class="px-3 py-1.5 rounded bg-charcoal hover:bg-accent hover:text-black text-xs font-mono transition-colors inline-flex items-center gap-1.5">
                                    <i data-lucide="download" class="w-3.5 h-3.5"></i>
                                    <span>Download CV</span>
                                </a>
                            <?php else: ?>
                                <span class="text-xs font-mono text-muted-gray">No CV</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <form action="<?= url('admin/applications.php') ?>" method="POST" class="inline">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="update_status">
                                <input type="hidden" name="id" value="<?= $app['id'] ?>">
                                <select name="status" onchange="this.form.submit()" class="text-xs font-mono rounded px-2 py-1 bg-primary-dark border <?= $statusClass ?> cursor-pointer focus:outline-none">
                                    <option value="Pending" <?= ($app['status'] === 'Pending') ? 'selected' : '' ?>>Pending</option>
                                    <option value="Reviewed" <?= ($app['status'] === 'Reviewed') ? 'selected' : '' ?>>Reviewed</option>
                                    <option value="Shortlisted" <?= ($app['status'] === 'Shortlisted') ? 'selected' : '' ?>>Shortlisted</option>
                                    <option value="Rejected" <?= ($app['status'] === 'Rejected') ? 'selected' : '' ?>>Rejected</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-xs font-mono text-muted-gray">
                            <?= date('M d, Y', strtotime($app['created_at'])) ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="<?= url('admin/applications.php') ?>" method="POST" class="inline" onsubmit="return confirm('Delete this applicant record?');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $app['id'] ?>">
                                <button type="submit" class="p-1.5 rounded bg-charcoal hover:bg-rose-950 text-muted-gray hover:text-rose-300 transition-colors" title="Delete Application">
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

