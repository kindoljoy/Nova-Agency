<?php
/**
 * Admin Services Management (admin/services.php)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helpers.php';

$pageTitle = 'Manage Services';
$db = getDB();

// Handle Delete Service
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (verify_csrf()) {
        $delId = (int)($_POST['id'] ?? 0);
        if ($delId > 0) {
            $stmt = $db->prepare("DELETE FROM services WHERE id = ?");
            $stmt->execute([$delId]);
            set_flash('success', 'Service has been deleted.');
            header('Location: ' . url('admin/services.php'));
            exit;
        }
    } else {
        set_flash('error', 'Security token invalid.');
    }
}

// Fetch services
$servicesStmt = $db->query("SELECT * FROM services ORDER BY display_order ASC");
$services = $servicesStmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="text-2xl font-heading font-extrabold text-white">Agency Services (<?= count($services) ?>)</h2>
        <p class="text-xs text-muted-gray mt-1">Manage core capabilities, benefits, and deliverables shown across the website.</p>
    </div>
    <a href="<?= url('admin/service-edit.php') ?>" class="btn-primary !py-2.5 !px-5 text-xs flex items-center gap-2 self-start sm:self-auto">
        <i data-lucide="plus" class="w-4 h-4"></i>
        <span>ADD NEW SERVICE</span>
    </a>
</div>

<div class="rounded-2xl bg-card-bg border border-border-dark overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-[#141414] text-xs font-mono text-muted-gray uppercase border-b border-border-dark">
                <tr>
                    <th class="px-6 py-4">Order</th>
                    <th class="px-6 py-4">Service Title</th>
                    <th class="px-6 py-4">Icon</th>
                    <th class="px-6 py-4">Deliverables</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-dark/60 font-light text-offwhite/80">
                <?php if (empty($services)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-muted-gray font-mono text-xs">
                        No services configured yet.
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($services as $svc): 
                        $delivCount = count(array_filter(explode("\n", $svc['deliverables'] ?? '')));
                    ?>
                    <tr class="hover:bg-charcoal/40 transition-colors">
                        <td class="px-6 py-4 font-mono text-xs text-muted-gray">
                            <?= (int)$svc['display_order'] ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-heading font-bold text-white"><?= e($svc['title']) ?></div>
                            <div class="text-xs text-muted-gray font-mono">slug: /<?= e($svc['slug']) ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="w-8 h-8 rounded-lg bg-[#222222] flex items-center justify-center text-accent">
                                <i data-lucide="<?= e($svc['icon'] ?? 'sparkles') ?>" class="w-4 h-4"></i>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs font-mono">
                            <?= $delivCount ?> item(s)
                        </td>
                        <td class="px-6 py-4">
                            <?php if ($svc['status'] === 'active'): ?>
                                <span class="px-2.5 py-1 rounded-full text-xs font-mono font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Active</span>
                            <?php else: ?>
                                <span class="px-2.5 py-1 rounded-full text-xs font-mono font-bold bg-muted-gray/20 text-muted-gray border border-border-dark">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="<?= url('services.php#' . e($svc['slug'])) ?>" target="_blank" class="p-1.5 rounded bg-charcoal hover:bg-[#2A2A2A] text-offwhite inline-block transition-colors" title="View Public Page">
                                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                            </a>
                            <a href="<?= url('admin/service-edit.php?id=' . $svc['id']) ?>" class="p-1.5 rounded bg-charcoal hover:bg-accent hover:text-black text-offwhite inline-block transition-colors" title="Edit Service">
                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                            </a>
                            <form action="<?= url('admin/services.php') ?>" method="POST" class="inline" onsubmit="return confirm('Permanently delete this service?');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $svc['id'] ?>">
                                <button type="submit" class="p-1.5 rounded bg-charcoal hover:bg-rose-950 text-muted-gray hover:text-rose-300 transition-colors" title="Delete Service">
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

