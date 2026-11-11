<?php
/**
 * Admin Add / Edit Service (admin/service-edit.php)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helpers.php';

$db = getDB();
$id = (int)($_GET['id'] ?? 0);
$isEditing = ($id > 0);

$pageTitle = $isEditing ? 'Edit Service' : 'Add New Service';

$service = [
    'title'             => '',
    'slug'              => '',
    'icon'              => 'sparkles',
    'short_description' => '',
    'description'       => '',
    'benefits'          => '',
    'deliverables'      => '',
    'display_order'     => 0,
    'status'            => 'active'
];

if ($isEditing) {
    $stmt = $db->prepare("SELECT * FROM services WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $existing = $stmt->fetch();
    if (!$existing) {
        set_flash('error', 'Service not found.');
        header('Location: ' . url('admin/services.php'));
        exit;
    }
    $service = array_merge($service, $existing);
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $errors[] = 'Security token invalid.';
    }

    $title             = trim($_POST['title'] ?? '');
    $slug              = trim($_POST['slug'] ?? '');
    $icon              = trim($_POST['icon'] ?? 'sparkles');
    $short_description = trim($_POST['short_description'] ?? '');
    $description       = trim($_POST['description'] ?? '');
    $benefits          = trim($_POST['benefits'] ?? '');
    $deliverables      = trim($_POST['deliverables'] ?? '');
    $display_order     = (int)($_POST['display_order'] ?? 0);
    $status            = trim($_POST['status'] ?? 'active');

    if (empty($title)) {
        $errors[] = 'Service title is required.';
    }

    if (empty($slug)) {
        $slug = slugify($title);
    } else {
        $slug = slugify($slug);
    }

    // Check slug uniqueness
    $checkSql = "SELECT id FROM services WHERE slug = ?" . ($isEditing ? " AND id != ?" : "");
    $checkParams = $isEditing ? [$slug, $id] : [$slug];
    $checkStmt = $db->prepare($checkSql);
    $checkStmt->execute($checkParams);
    if ($checkStmt->fetch()) {
        $errors[] = 'The slug "' . $slug . '" is already in use by another service.';
    }

    if (empty($errors)) {
        if ($isEditing) {
            $upSql = "UPDATE services SET 
                title = ?, slug = ?, icon = ?, short_description = ?, description = ?, 
                benefits = ?, deliverables = ?, display_order = ?, status = ?, updated_at = CURRENT_TIMESTAMP 
                WHERE id = ?";
            $upStmt = $db->prepare($upSql);
            $upStmt->execute([
                $title, $slug, $icon, $short_description, $description,
                $benefits, $deliverables, $display_order, $status, $id
            ]);
            set_flash('success', 'Service updated successfully.');
        } else {
            $inSql = "INSERT INTO services (
                title, slug, icon, short_description, description, 
                benefits, deliverables, display_order, status, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
            $inStmt = $db->prepare($inSql);
            $inStmt->execute([
                $title, $slug, $icon, $short_description, $description,
                $benefits, $deliverables, $display_order, $status
            ]);
            set_flash('success', 'New service added successfully.');
        }

        header('Location: ' . url('admin/services.php'));
        exit;
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="mb-8">
    <a href="<?= url('admin/services.php') ?>" class="inline-flex items-center gap-2 font-mono text-xs text-accent hover:text-white transition-colors mb-4">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        <span>BACK TO SERVICES LIST</span>
    </a>
    <h2 class="text-3xl font-heading font-extrabold text-white"><?= $isEditing ? 'Edit: ' . e($service['title']) : 'Add New Agency Service' ?></h2>
</div>

<?php if (!empty($errors)): ?>
<div class="mb-8 p-4 rounded-xl bg-rose-950/80 border border-rose-500/50 text-rose-200 text-sm">
    <div class="flex items-center gap-2 font-bold mb-2">
        <i data-lucide="alert-circle" class="w-4 h-4"></i>
        <span>Submission errors:</span>
    </div>
    <ul class="list-disc pl-5 space-y-1 text-xs">
        <?php foreach ($errors as $err): ?>
            <li><?= e($err) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form action="<?= url('admin/service-edit.php' . ($isEditing ? '?id=' . $id : '')) ?>" method="POST" class="space-y-8 max-w-4xl">
    <?= csrf_field() ?>

    <div class="p-8 rounded-2xl bg-card-bg border border-border-dark space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="title" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                    Service Title <span class="text-accent">*</span>
                </label>
                <input type="text" id="title" name="title" required value="<?= e($service['title']) ?>" placeholder="e.g. Brand Identity" class="form-input text-sm">
            </div>
            <div>
                <label for="slug" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                    Slug <span class="text-offwhite/40 text-[10px]">(Auto-generated if empty)</span>
                </label>
                <input type="text" id="slug" name="slug" value="<?= e($service['slug']) ?>" placeholder="brand-identity" class="form-input text-sm font-mono">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div>
                <label for="icon" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                    Lucide Icon Name
                </label>
                <input type="text" id="icon" name="icon" value="<?= e($service['icon']) ?>" placeholder="sparkles, layout, code-2, palette" class="form-input text-sm font-mono">
            </div>
            <div>
                <label for="display_order" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                    Display Order
                </label>
                <input type="number" id="display_order" name="display_order" value="<?= (int)$service['display_order'] ?>" class="form-input text-sm font-mono">
            </div>
            <div>
                <label for="status" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                    Status
                </label>
                <select id="status" name="status" class="form-input text-xs font-mono">
                    <option value="active" <?= ($service['status'] === 'active') ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= ($service['status'] === 'inactive') ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
        </div>

        <div>
            <label for="short_description" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                Short Description <span class="text-offwhite/40 text-[10px]">(Shown on Homepage cards)</span>
            </label>
            <textarea id="short_description" name="short_description" rows="2" placeholder="Brief summary of capability..." class="form-input text-sm"><?= e($service['short_description']) ?></textarea>
        </div>

        <div>
            <label for="description" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                Full Service Overview
            </label>
            <textarea id="description" name="description" rows="4" placeholder="Detailed service description shown on Services page..." class="form-input text-sm"><?= e($service['description']) ?></textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="benefits" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                    Strategic Benefits <span class="text-offwhite/40 text-[10px]">(One per line)</span>
                </label>
                <textarea id="benefits" name="benefits" rows="5" placeholder="Establish commanding authority&#10;Unified visual consistency&#10;WCAG accessibility..." class="form-input text-sm"><?= e($service['benefits']) ?></textarea>
            </div>
            <div>
                <label for="deliverables" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                    Deliverables Checklist <span class="text-offwhite/40 text-[10px]">(One per line)</span>
                </label>
                <textarea id="deliverables" name="deliverables" rows="5" placeholder="Logo Suite &amp; Guidelines&#10;Interactive Figma Prototypes&#10;Design System Tokens..." class="form-input text-sm"><?= e($service['deliverables']) ?></textarea>
            </div>
        </div>

        <div class="pt-4 border-t border-border-dark flex justify-end">
            <button type="submit" class="btn-primary py-3.5 px-8 text-xs font-mono uppercase tracking-wider">
                <?= $isEditing ? 'UPDATE SERVICE' : 'SAVE SERVICE' ?>
            </button>
        </div>
    </div>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

