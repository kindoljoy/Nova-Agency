<?php
/**
 * Admin Inquiries Management (admin/inquiries.php)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helpers.php';

$pageTitle = 'Manage Project Inquiries';
$db = getDB();

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (verify_csrf()) {
        $delId = (int)($_POST['id'] ?? 0);
        if ($delId > 0) {
            $stmt = $db->prepare("DELETE FROM inquiries WHERE id = ?");
            $stmt->execute([$delId]);
            set_flash('success', 'Inquiry #' . $delId . ' has been removed.');
            header('Location: ' . url('admin/inquiries.php'));
            exit;
        }
    } else {
        set_flash('error', 'Invalid security token.');
    }
}

// Filter and Search
$statusFilter = trim($_GET['status'] ?? '');
$search = trim($_GET['q'] ?? '');

$sql = "SELECT * FROM inquiries WHERE 1=1";
$params = [];

if (!empty($statusFilter) && $statusFilter !== 'all') {
    $sql .= " AND status = ?";
    $params[] = $statusFilter;
}

if (!empty($search)) {
    $sql .= " AND (full_name LIKE ? OR email LIKE ? OR company_name LIKE ? OR description LIKE ?)";
    $like = "%{$search}%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

$sql .= " ORDER BY created_at DESC";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$inquiries = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<!-- Header Actions -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="text-2xl font-heading font-extrabold text-white">Project Inquiries (<?= count($inquiries) ?>)</h2>
        <p class="text-xs text-muted-gray mt-1">Review, qualify, and track prospective client project leads.</p>
    </div>

    <!-- Status Filters -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 sm:pb-0">
        <a href="<?= url('admin/inquiries.php') ?>" class="px-3.5 py-1.5 rounded-lg text-xs font-mono uppercase tracking-wider border <?= (empty($statusFilter) || $statusFilter === 'all') ? 'bg-accent text-black font-bold border-accent' : 'bg-card-bg text-offwhite/70 border-border-dark hover:border-offwhite' ?>">
            All
        </a>
        <a href="<?= url('admin/inquiries.php?status=New') ?>" class="px-3.5 py-1.5 rounded-lg text-xs font-mono uppercase tracking-wider border <?= ($statusFilter === 'New') ? 'bg-accent text-black font-bold border-accent' : 'bg-card-bg text-offwhite/70 border-border-dark hover:border-offwhite' ?>">
            New
        </a>
        <a href="<?= url('admin/inquiries.php?status=In Review') ?>" class="px-3.5 py-1.5 rounded-lg text-xs font-mono uppercase tracking-wider border <?= ($statusFilter === 'In Review') ? 'bg-accent text-black font-bold border-accent' : 'bg-card-bg text-offwhite/70 border-border-dark hover:border-offwhite' ?>">
            In Review
        </a>
        <a href="<?= url('admin/inquiries.php?status=Contacted') ?>" class="px-3.5 py-1.5 rounded-lg text-xs font-mono uppercase tracking-wider border <?= ($statusFilter === 'Contacted') ? 'bg-accent text-black font-bold border-accent' : 'bg-card-bg text-offwhite/70 border-border-dark hover:border-offwhite' ?>">
            Contacted
        </a>
        <a href="<?= url('admin/inquiries.php?status=Closed') ?>" class="px-3.5 py-1.5 rounded-lg text-xs font-mono uppercase tracking-wider border <?= ($statusFilter === 'Closed') ? 'bg-accent text-black font-bold border-accent' : 'bg-card-bg text-offwhite/70 border-border-dark hover:border-offwhite' ?>">
            Closed
        </a>
    </div>
</div>

<!-- Search Bar -->
<div class="mb-6">
    <form action="<?= url('admin/inquiries.php') ?>" method="GET" class="max-w-md relative">
        <input type="text" name="q" value="<?= e($search) ?>" placeholder="Search by name, email, company, description..." class="form-input !pl-10 !py-2.5 text-xs">
        <i data-lucide="search" class="w-4 h-4 text-muted-gray absolute left-3 top-1/2 -translate-y-1/2"></i>
    </form>
</div>

<!-- Table Card -->
<div class="rounded-2xl bg-card-bg border border-border-dark overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-[#141414] text-xs font-mono text-muted-gray uppercase border-b border-border-dark">
                <tr>
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">Prospect</th>
                    <th class="px-6 py-4">Service</th>
                    <th class="px-6 py-4">Budget / Timeline</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Submitted</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-dark/60 font-light text-offwhite/80">
                <?php if (empty($inquiries)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-muted-gray font-mono text-xs">
                        No inquiries match the current filter criteria.
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($inquiries as $inq): 
                        $statusClass = ($inq['status'] === 'New') ? 'bg-accent/20 text-accent border-accent/40' :
                                       (($inq['status'] === 'In Review') ? 'bg-amber-500/20 text-amber-300 border-amber-500/30' :
                                       (($inq['status'] === 'Contacted') ? 'bg-blue-500/20 text-blue-300 border-blue-500/30' :
                                       'bg-emerald-500/20 text-emerald-300 border-emerald-500/30'));
                    ?>
                    <tr class="hover:bg-charcoal/40 transition-colors">
                        <td class="px-6 py-4 font-mono text-xs text-muted-gray">
                            #<?= $inq['id'] ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-heading font-bold text-white"><?= e($inq['full_name']) ?></div>
                            <div class="text-xs text-muted-gray font-mono"><?= e($inq['email']) ?></div>
                            <?php if (!empty($inq['company_name'])): ?>
                                <span class="text-[11px] text-offwhite/60 font-mono"><?= e($inq['company_name']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded bg-[#202020] text-xs font-mono text-offwhite">
                                <?= e($inq['service_needed']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs font-mono">
                            <div><?= e($inq['estimated_budget'] ?: '—') ?></div>
                            <div class="text-muted-gray"><?= e($inq['project_timeline'] ?: '—') ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-mono font-bold border <?= $statusClass ?>">
                                <?= e($inq['status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs font-mono text-muted-gray">
                            <?= date('M d, Y', strtotime($inq['created_at'])) ?>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="<?= url('admin/inquiry-detail.php?id=' . $inq['id']) ?>" class="px-3 py-1.5 rounded bg-charcoal hover:bg-accent hover:text-black text-xs font-mono transition-colors inline-flex items-center gap-1">
                                <span>View</span>
                                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                            </a>
                            <form action="<?= url('admin/inquiries.php') ?>" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete this inquiry?');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $inq['id'] ?>">
                                <button type="submit" class="p-1.5 rounded bg-charcoal hover:bg-rose-950 text-muted-gray hover:text-rose-300 transition-colors" title="Delete Inquiry">
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

