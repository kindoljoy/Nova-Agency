<?php
/**
 * Admin Add / Edit Project (admin/project-edit.php)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helpers.php';

$db = getDB();
$id = (int)($_GET['id'] ?? 0);
$isEditing = ($id > 0);

$pageTitle = $isEditing ? 'Edit Project' : 'Create New Project';

// Fetch categories
$catStmt = $db->query("SELECT * FROM categories WHERE type = 'project' ORDER BY name ASC");
$categories = $catStmt->fetchAll();

// Project data defaults
$project = [
    'title'            => '',
    'slug'             => '',
    'client'           => '',
    'category_id'      => 0,
    'category_name'    => '',
    'year'             => date('Y'),
    'summary'          => '',
    'featured_image'   => '',
    'objectives'       => '',
    'challenges'       => '',
    'creative_process' => '',
    'final_output'     => '',
    'outcomes'         => '',
    'is_featured'      => 0,
    'display_order'    => 0,
    'status'           => 'published'
];

if ($isEditing) {
    $stmt = $db->prepare("SELECT * FROM projects WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $existing = $stmt->fetch();
    if (!$existing) {
        set_flash('error', 'Project not found.');
        header('Location: ' . url('admin/projects.php'));
        exit;
    }
    $project = array_merge($project, $existing);
}

$errors = [];

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $errors[] = 'Security token invalid.';
    }

    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $client = trim($_POST['client'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $year = trim($_POST['year'] ?? date('Y'));
    $summary = trim($_POST['summary'] ?? '');
    $objectives = trim($_POST['objectives'] ?? '');
    $challenges = trim($_POST['challenges'] ?? '');
    $creative_process = trim($_POST['creative_process'] ?? '');
    $final_output = trim($_POST['final_output'] ?? '');
    $outcomes = trim($_POST['outcomes'] ?? '');
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $display_order = (int)($_POST['display_order'] ?? 0);
    $status = trim($_POST['status'] ?? 'published');

    if (empty($title)) {
        $errors[] = 'Project title is required.';
    }
    if (empty($client)) {
        $errors[] = 'Client name is required.';
    }

    if (empty($slug)) {
        $slug = slugify($title);
    } else {
        $slug = slugify($slug);
    }

    // Lookup category name
    $category_name = 'General';
    foreach ($categories as $c) {
        if ($c['id'] == $category_id) {
            $category_name = $c['name'];
            break;
        }
    }

    // Check unique slug
    $checkSql = "SELECT id FROM projects WHERE slug = ?" . ($isEditing ? " AND id != ?" : "");
    $checkParams = $isEditing ? [$slug, $id] : [$slug];
    $checkStmt = $db->prepare($checkSql);
    $checkStmt->execute($checkParams);
    if ($checkStmt->fetch()) {
        $errors[] = 'The slug "' . $slug . '" is already in use by another project.';
    }

    // Handle Image Upload if provided
    $featured_image = $project['featured_image'];
    if (!empty($_FILES['image_file']['name'])) {
        $upload = handle_file_upload($_FILES['image_file'], PROJECT_UPLOADS_PATH, ALLOWED_IMAGE_EXT, MAX_IMAGE_SIZE);
        if ($upload['success']) {
            $featured_image = $upload['filename'];
        } else {
            $errors[] = $upload['error'];
        }
    }

    if (empty($errors)) {
        if ($isEditing) {
            $updateSql = "UPDATE projects SET 
                title = ?, slug = ?, client = ?, category_id = ?, category_name = ?, year = ?, 
                summary = ?, featured_image = ?, objectives = ?, challenges = ?, creative_process = ?, 
                final_output = ?, outcomes = ?, is_featured = ?, display_order = ?, status = ?, 
                updated_at = CURRENT_TIMESTAMP WHERE id = ?";
            $upStmt = $db->prepare($updateSql);
            $upStmt->execute([
                $title, $slug, $client, $category_id, $category_name, $year,
                $summary, $featured_image, $objectives, $challenges, $creative_process,
                $final_output, $outcomes, $is_featured, $display_order, $status, $id
            ]);
            set_flash('success', 'Project updated successfully.');
        } else {
            $insertSql = "INSERT INTO projects (
                title, slug, client, category_id, category_name, year, 
                summary, featured_image, objectives, challenges, creative_process, 
                final_output, outcomes, is_featured, display_order, status, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
            $inStmt = $db->prepare($insertSql);
            $inStmt->execute([
                $title, $slug, $client, $category_id, $category_name, $year,
                $summary, $featured_image, $objectives, $challenges, $creative_process,
                $final_output, $outcomes, $is_featured, $display_order, $status
            ]);
            set_flash('success', 'New project case study published successfully.');
        }

        header('Location: ' . url('admin/projects.php'));
        exit;
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="mb-8">
    <a href="<?= url('admin/projects.php') ?>" class="inline-flex items-center gap-2 font-mono text-xs text-accent hover:text-white transition-colors mb-4">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        <span>BACK TO PORTFOLIO ARCHIVE</span>
    </a>
    <h2 class="text-3xl font-heading font-extrabold text-white"><?= $isEditing ? 'Edit: ' . e($project['title']) : 'Create New Project Case Study' ?></h2>
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

<form action="<?= url('admin/project-edit.php' . ($isEditing ? '?id=' . $id : '')) ?>" method="POST" enctype="multipart/form-data" class="space-y-8">
    <?= csrf_field() ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Main Case Study Content -->
        <div class="lg:col-span-8 space-y-6">
            <div class="p-8 rounded-2xl bg-card-bg border border-border-dark space-y-6">
                <h3 class="font-heading font-bold text-white text-lg border-b border-border-dark pb-4">Core Information</h3>

                <div>
                    <label for="title" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                        Project Title <span class="text-accent">*</span>
                    </label>
                    <input type="text" id="title" name="title" required value="<?= e($project['title']) ?>" placeholder="e.g. Aether Fintech — Operating System" class="form-input text-sm">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="client" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                            Client Name <span class="text-accent">*</span>
                        </label>
                        <input type="text" id="client" name="client" required value="<?= e($project['client']) ?>" placeholder="e.g. Aether Capital Corp" class="form-input text-sm">
                    </div>
                    <div>
                        <label for="year" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                            Production Year
                        </label>
                        <input type="text" id="year" name="year" value="<?= e($project['year']) ?>" placeholder="2026" class="form-input text-sm font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="category_id" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                            Category
                        </label>
                        <select id="category_id" name="category_id" class="form-input text-sm">
                            <option value="">-- Select Category --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= ($project['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                    <?= e($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="slug" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                            URL Slug <span class="text-offwhite/40 text-[10px]">(Auto-generated if empty)</span>
                        </label>
                        <input type="text" id="slug" name="slug" value="<?= e($project['slug']) ?>" placeholder="aether-fintech-os" class="form-input text-sm font-mono">
                    </div>
                </div>

                <div>
                    <label for="summary" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                        Executive Summary / Short Teaser
                    </label>
                    <textarea id="summary" name="summary" rows="3" placeholder="A concise 2-sentence summary shown on cards and portfolio index..." class="form-input text-sm"><?= e($project['summary']) ?></textarea>
                </div>
            </div>

            <!-- Deep Case Study Sections (PRD 4.4) -->
            <div class="p-8 rounded-2xl bg-card-bg border border-border-dark space-y-6">
                <h3 class="font-heading font-bold text-white text-lg border-b border-border-dark pb-4">In-Depth Case Study Framework</h3>

                <div>
                    <label for="objectives" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                        1. Project Objectives &amp; Client Goals
                    </label>
                    <textarea id="objectives" name="objectives" rows="4" placeholder="What was the primary mission of this engagement?" class="form-input text-sm"><?= e($project['objectives']) ?></textarea>
                </div>

                <div>
                    <label for="challenges" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                        2. Key Challenges &amp; Complexities
                    </label>
                    <textarea id="challenges" name="challenges" rows="4" placeholder="What obstacles, constraints, or technical hurdles were solved?" class="form-input text-sm"><?= e($project['challenges']) ?></textarea>
                </div>

                <div>
                    <label for="creative_process" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                        3. Strategic &amp; Creative Process
                    </label>
                    <textarea id="creative_process" name="creative_process" rows="4" placeholder="How did the team conduct research, prototyping, and engineering?" class="form-input text-sm"><?= e($project['creative_process']) ?></textarea>
                </div>

                <div>
                    <label for="final_output" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                        4. Final Deliverables &amp; Output
                    </label>
                    <textarea id="final_output" name="final_output" rows="4" placeholder="Detailed deliverables handed off to the client..." class="form-input text-sm"><?= e($project['final_output']) ?></textarea>
                </div>

                <div>
                    <label for="outcomes" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                        5. Measurable Outcomes &amp; Business Results
                    </label>
                    <textarea id="outcomes" name="outcomes" rows="3" placeholder="Quantifiable metrics, revenue surge, user growth, awards won..." class="form-input text-sm"><?= e($project['outcomes']) ?></textarea>
                </div>
            </div>
        </div>

        <!-- Sidebar Options: Image, Status, Featured -->
        <div class="lg:col-span-4 space-y-6">
            <div class="p-8 rounded-2xl bg-card-bg border border-border-dark space-y-6">
                <h3 class="font-heading font-bold text-white text-lg border-b border-border-dark pb-4">Publishing Controls</h3>

                <div>
                    <label for="status" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                        Publication Status
                    </label>
                    <select id="status" name="status" class="form-input text-xs font-mono">
                        <option value="published" <?= ($project['status'] === 'published') ? 'selected' : '' ?>>Published (Visible to public)</option>
                        <option value="draft" <?= ($project['status'] === 'draft') ? 'selected' : '' ?>>Draft (Internal only)</option>
                    </select>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <input type="checkbox" id="is_featured" name="is_featured" value="1" <?= !empty($project['is_featured']) ? 'checked' : '' ?> class="w-4 h-4 rounded text-accent bg-primary-dark border-border-dark focus:ring-accent">
                    <label for="is_featured" class="text-sm font-medium text-white cursor-pointer select-none">
                        Feature on Homepage
                    </label>
                </div>

                <div>
                    <label for="display_order" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                        Display Order Sort
                    </label>
                    <input type="number" id="display_order" name="display_order" value="<?= (int)$project['display_order'] ?>" class="form-input text-xs font-mono">
                </div>

                <div class="pt-4 border-t border-border-dark">
                    <button type="submit" class="btn-primary w-full py-3.5 text-xs font-mono uppercase tracking-wider">
                        <?= $isEditing ? 'UPDATE CASE STUDY' : 'PUBLISH CASE STUDY' ?>
                    </button>
                </div>
            </div>

            <!-- Featured Image Upload Card -->
            <div class="p-8 rounded-2xl bg-card-bg border border-border-dark space-y-4">
                <h3 class="font-heading font-bold text-white text-lg">Featured Showcase Image</h3>

                <?php if (!empty($project['featured_image'])): ?>
                <div class="aspect-[16/10] rounded-xl overflow-hidden bg-black border border-border-dark">
                    <img src="<?= upload_url('projects', $project['featured_image']) ?>" alt="Current preview" class="w-full h-full object-cover">
                </div>
                <span class="text-[11px] font-mono text-muted-gray block">Current file: <?= e($project['featured_image']) ?></span>
                <?php endif; ?>

                <div>
                    <label for="image_file" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                        Upload New Image <span class="text-offwhite/40 text-[10px]">(JPG, PNG, WebP, SVG)</span>
                    </label>
                    <input type="file" id="image_file" name="image_file" accept=".jpg,.jpeg,.png,.webp,.svg" class="form-input file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-mono file:bg-charcoal file:text-white hover:file:bg-accent hover:file:text-black">
                </div>
            </div>
        </div>
    </div>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

