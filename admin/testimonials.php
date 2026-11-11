<?php
/**
 * Admin Testimonials Management (admin/testimonials.php)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helpers.php';

$pageTitle = 'Manage Testimonials';
$db = getDB();

$editId = (int)($_GET['edit'] ?? 0);
$editItem = null;

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (verify_csrf()) {
        $delId = (int)($_POST['id'] ?? 0);
        if ($delId > 0) {
            $stmt = $db->prepare("DELETE FROM testimonials WHERE id = ?");
            $stmt->execute([$delId]);
            set_flash('success', 'Testimonial removed.');
            header('Location: ' . url('admin/testimonials.php'));
            exit;
        }
    }
}

// Handle Add / Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save') {
    if (verify_csrf()) {
        $id = (int)($_POST['id'] ?? 0);
        $client_name = trim($_POST['client_name'] ?? '');
        $role_company = trim($_POST['role_company'] ?? '');
        $quote = trim($_POST['quote'] ?? '');
        $rating = (int)($_POST['rating'] ?? 5);
        $display_order = (int)($_POST['display_order'] ?? 0);
        $status = trim($_POST['status'] ?? 'active');

        if (!empty($client_name) && !empty($quote)) {
            if ($id > 0) {
                $stmt = $db->prepare("UPDATE testimonials SET client_name = ?, role_company = ?, quote = ?, rating = ?, display_order = ?, status = ? WHERE id = ?");
                $stmt->execute([$client_name, $role_company, $quote, $rating, $display_order, $status, $id]);
                set_flash('success', 'Testimonial updated.');
            } else {
                $stmt = $db->prepare("INSERT INTO testimonials (client_name, role_company, avatar, quote, rating, display_order, status, created_at) VALUES (?, ?, 'avatar-1.svg', ?, ?, ?, ?, CURRENT_TIMESTAMP)");
                $stmt->execute([$client_name, $role_company, $quote, $rating, $display_order, $status]);
                set_flash('success', 'Testimonial added.');
            }
            header('Location: ' . url('admin/testimonials.php'));
            exit;
        } else {
            set_flash('error', 'Client name and quote are required.');
        }
    }
}

if ($editId > 0) {
    $stmt = $db->prepare("SELECT * FROM testimonials WHERE id = ? LIMIT 1");
    $stmt->execute([$editId]);
    $editItem = $stmt->fetch();
}

$testimonials = $db->query("SELECT * FROM testimonials ORDER BY display_order ASC, created_at DESC")->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<div class="mb-8">
    <h2 class="text-2xl font-heading font-extrabold text-white">Client Testimonials (<?= count($testimonials) ?>)</h2>
    <p class="text-xs text-muted-gray mt-1">Manage executive endorsements and social proof featured on the homepage.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <!-- List Column -->
    <div class="lg:col-span-7">
        <div class="rounded-2xl bg-card-bg border border-border-dark overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-[#141414] text-xs font-mono text-muted-gray uppercase border-b border-border-dark">
                        <tr>
                            <th class="px-6 py-4">Client</th>
                            <th class="px-6 py-4">Rating</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-dark/60 font-light text-offwhite/80">
                        <?php if (empty($testimonials)): ?>
                        <tr><td colspan="4" class="px-6 py-8 text-center text-xs font-mono text-muted-gray">No testimonials added yet.</td></tr>
                        <?php else: ?>
                            <?php foreach ($testimonials as $t): ?>
                            <tr class="hover:bg-charcoal/40 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-heading font-bold text-white"><?= e($t['client_name']) ?></div>
                                    <div class="text-xs text-muted-gray font-mono"><?= e($t['role_company']) ?></div>
                                    <p class="text-xs text-offwhite/70 italic mt-1 line-clamp-2">"<?= e($t['quote']) ?>"</p>
                                </td>
                                <td class="px-6 py-4 text-accent text-xs font-mono">
                                    <?= str_repeat('★', (int)$t['rating']) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-0.5 rounded text-[11px] font-mono <?= ($t['status'] === 'active') ? 'bg-emerald-500/20 text-emerald-300' : 'bg-muted-gray/20 text-muted-gray' ?>">
                                        <?= e($t['status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="<?= url('admin/testimonials.php?edit=' . $t['id']) ?>" class="p-1.5 rounded bg-charcoal hover:bg-accent hover:text-black inline-block text-xs font-mono transition-colors">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                    </a>
                                    <form action="<?= url('admin/testimonials.php') ?>" method="POST" class="inline" onsubmit="return confirm('Delete this testimonial?');">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $t['id'] ?>">
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
    </div>

    <!-- Form Column -->
    <div class="lg:col-span-5">
        <div class="p-6 sm:p-8 rounded-2xl bg-card-bg border border-border-dark">
            <h3 class="font-heading font-bold text-white text-lg mb-6"><?= $editItem ? 'Edit Testimonial' : 'Add New Testimonial' ?></h3>

            <form action="<?= url('admin/testimonials.php') ?>" method="POST" class="space-y-5">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="save">
                <?php if ($editItem): ?>
                    <input type="hidden" name="id" value="<?= $editItem['id'] ?>">
                <?php endif; ?>

                <div>
                    <label for="client_name" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                        Client Full Name <span class="text-accent">*</span>
                    </label>
                    <input type="text" id="client_name" name="client_name" required value="<?= e($editItem['client_name'] ?? '') ?>" placeholder="e.g. Sophia Lorenzen" class="form-input text-xs">
                </div>

                <div>
                    <label for="role_company" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                        Role &amp; Company
                    </label>
                    <input type="text" id="role_company" name="role_company" value="<?= e($editItem['role_company'] ?? '') ?>" placeholder="e.g. CPO at Aether Capital" class="form-input text-xs">
                </div>

                <div>
                    <label for="quote" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                        Client Testimonial Quote <span class="text-accent">*</span>
                    </label>
                    <textarea id="quote" name="quote" rows="4" required placeholder="What did the client say about NOVA?" class="form-input text-xs"><?= e($editItem['quote'] ?? '') ?></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="rating" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">Rating</label>
                        <select id="rating" name="rating" class="form-input text-xs font-mono">
                            <option value="5" <?= (($editItem['rating'] ?? 5) == 5) ? 'selected' : '' ?>>5 Stars (★★★★★)</option>
                            <option value="4" <?= (($editItem['rating'] ?? 5) == 4) ? 'selected' : '' ?>>4 Stars (★★★★☆)</option>
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">Status</label>
                        <select id="status" name="status" class="form-input text-xs font-mono">
                            <option value="active" <?= (($editItem['status'] ?? 'active') === 'active') ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= (($editItem['status'] ?? 'active') === 'inactive') ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="pt-2 flex items-center justify-between">
                    <button type="submit" class="btn-primary py-3 px-6 text-xs font-mono uppercase">
                        <?= $editItem ? 'UPDATE TESTIMONIAL' : 'SAVE TESTIMONIAL' ?>
                    </button>
                    <?php if ($editItem): ?>
                        <a href="<?= url('admin/testimonials.php') ?>" class="text-xs font-mono text-muted-gray hover:text-white">Cancel</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

