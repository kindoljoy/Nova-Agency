<?php
/**
 * Admin Inquiry Detail & Status Update (admin/inquiry-detail.php)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helpers.php';

$pageTitle = 'Inquiry Details';
$db = getDB();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: ' . url('admin/inquiries.php'));
    exit;
}

$stmt = $db->prepare("SELECT * FROM inquiries WHERE id = ? LIMIT 1");
$stmt->execute([$id]);
$inquiry = $stmt->fetch();

if (!$inquiry) {
    set_flash('error', 'Inquiry not found.');
    header('Location: ' . url('admin/inquiries.php'));
    exit;
}

// Handle Status & Notes Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    if (verify_csrf()) {
        $status = trim($_POST['status'] ?? 'New');
        $notes = trim($_POST['admin_notes'] ?? '');

        $upStmt = $db->prepare("UPDATE inquiries SET status = ?, admin_notes = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        $upStmt->execute([$status, $notes, $id]);

        set_flash('success', 'Inquiry status and notes have been updated.');
        header('Location: ' . url('admin/inquiry-detail.php?id=' . $id));
        exit;
    } else {
        set_flash('error', 'Invalid security token.');
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="mb-8">
    <a href="<?= url('admin/inquiries.php') ?>" class="inline-flex items-center gap-2 font-mono text-xs text-accent hover:text-white transition-colors mb-4">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        <span>BACK TO INQUIRIES LIST</span>
    </a>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h2 class="text-3xl font-heading font-extrabold text-white">Inquiry #<?= $inquiry['id'] ?> &bull; <?= e($inquiry['full_name']) ?></h2>
        <span class="px-3.5 py-1 rounded-full text-xs font-mono font-bold bg-accent/20 text-accent border border-accent/30 self-start sm:self-auto">
            CURRENT STATUS: <?= e($inquiry['status']) ?>
        </span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <!-- Left Column: Prospect Details & Message -->
    <div class="lg:col-span-8 space-y-6">
        <div class="p-8 rounded-2xl bg-card-bg border border-border-dark space-y-6">
            <h3 class="font-heading font-bold text-white text-lg border-b border-border-dark pb-4">Client Overview</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <span class="text-xs font-mono text-muted-gray uppercase block mb-1">PROSPECT NAME</span>
                    <span class="font-heading font-bold text-white text-base"><?= e($inquiry['full_name']) ?></span>
                </div>
                <div>
                    <span class="text-xs font-mono text-muted-gray uppercase block mb-1">EMAIL ADDRESS</span>
                    <a href="mailto:<?= e($inquiry['email']) ?>" class="font-mono text-sm text-accent hover:underline"><?= e($inquiry['email']) ?></a>
                </div>
                <div>
                    <span class="text-xs font-mono text-muted-gray uppercase block mb-1">COMPANY NAME</span>
                    <span class="text-sm text-offwhite"><?= e($inquiry['company_name'] ?: 'Not specified') ?></span>
                </div>
                <div>
                    <span class="text-xs font-mono text-muted-gray uppercase block mb-1">REQUESTED CAPABILITY</span>
                    <span class="px-2.5 py-1 rounded bg-[#222222] text-xs font-mono text-accent inline-block">
                        <?= e($inquiry['service_needed']) ?>
                    </span>
                </div>
                <div>
                    <span class="text-xs font-mono text-muted-gray uppercase block mb-1">BUDGET ESTIMATE</span>
                    <span class="font-mono text-sm text-white"><?= e($inquiry['estimated_budget'] ?: 'Not specified') ?></span>
                </div>
                <div>
                    <span class="text-xs font-mono text-muted-gray uppercase block mb-1">TIMELINE EXPECTATION</span>
                    <span class="font-mono text-sm text-white"><?= e($inquiry['project_timeline'] ?: 'Flexible') ?></span>
                </div>
            </div>

            <div class="pt-6 border-t border-border-dark">
                <span class="text-xs font-mono text-muted-gray uppercase block mb-3">PROJECT DESCRIPTION</span>
                <div class="p-6 rounded-xl bg-primary-dark border border-border-dark text-offwhite/90 text-sm leading-relaxed whitespace-pre-wrap font-light">
                    <?= e($inquiry['description']) ?>
                </div>
            </div>

            <div class="flex items-center justify-between text-xs font-mono text-muted-gray pt-4 border-t border-border-dark">
                <span>Received: <?= date('F d, Y \a\t H:i:s', strtotime($inquiry['created_at'])) ?></span>
                <span>Last Updated: <?= date('F d, Y \a\t H:i:s', strtotime($inquiry['updated_at'])) ?></span>
            </div>
        </div>
    </div>

    <!-- Right Column: Status Update & Admin Notes -->
    <div class="lg:col-span-4 space-y-6">
        <div class="p-8 rounded-2xl bg-card-bg border border-border-dark">
            <h3 class="font-heading font-bold text-white text-lg mb-6">Pipeline Status</h3>

            <form action="<?= url('admin/inquiry-detail.php?id=' . $inquiry['id']) ?>" method="POST" class="space-y-5">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="update_status">

                <div>
                    <label for="status" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                        Update Status
                    </label>
                    <select id="status" name="status" class="form-input text-xs font-mono">
                        <option value="New" <?= ($inquiry['status'] === 'New') ? 'selected' : '' ?>>New (Unread / Needs Review)</option>
                        <option value="In Review" <?= ($inquiry['status'] === 'In Review') ? 'selected' : '' ?>>In Review (Discovery in progress)</option>
                        <option value="Contacted" <?= ($inquiry['status'] === 'Contacted') ? 'selected' : '' ?>>Contacted (Call held / Proposal sent)</option>
                        <option value="Closed" <?= ($inquiry['status'] === 'Closed') ? 'selected' : '' ?>>Closed (Won / Lost / Archived)</option>
                    </select>
                </div>

                <div>
                    <label for="admin_notes" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                        Internal Admin Notes
                    </label>
                    <textarea id="admin_notes" name="admin_notes" rows="6" placeholder="Add confidential notes on discovery call, team assignment, proposal pricing..." class="form-input text-xs"><?= e($inquiry['admin_notes'] ?? '') ?></textarea>
                </div>

                <button type="submit" class="btn-primary w-full py-3 text-xs font-mono uppercase tracking-wider">
                    SAVE UPDATES
                </button>
            </form>

            <div class="pt-6 mt-6 border-t border-border-dark">
                <a href="mailto:<?= e($inquiry['email']) ?>?subject=Re:%20Project%20Inquiry%20with%20NOVA%20Agency" class="btn-secondary w-full py-2.5 text-xs font-mono flex items-center justify-center gap-2">
                    <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                    <span>Send Direct Email</span>
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

