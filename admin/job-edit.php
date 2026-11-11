<?php
/**
 * Admin Add / Edit Job Position (admin/job-edit.php)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helpers.php';

$db = getDB();
$id = (int)($_GET['id'] ?? 0);
$isEditing = ($id > 0);

$pageTitle = $isEditing ? 'Edit Job Opening' : 'Post New Job Vacancy';

$job = [
    'title'             => '',
    'slug'              => '',
    'department'        => 'Design',
    'work_type'         => 'Full-time',
    'location'          => 'Remote / Hybrid (Jakarta)',
    'short_description' => '',
    'description'       => '',
    'responsibilities'  => '',
    'requirements'      => '',
    'status'            => 'open'
];

if ($isEditing) {
    $stmt = $db->prepare("SELECT * FROM job_positions WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $existing = $stmt->fetch();
    if (!$existing) {
        set_flash('error', 'Job position not found.');
        header('Location: ' . url('admin/jobs.php'));
        exit;
    }
    $job = array_merge($job, $existing);
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $errors[] = 'Security token invalid.';
    }

    $title             = trim($_POST['title'] ?? '');
    $slug              = trim($_POST['slug'] ?? '');
    $department        = trim($_POST['department'] ?? 'Design');
    $work_type         = trim($_POST['work_type'] ?? 'Full-time');
    $location          = trim($_POST['location'] ?? 'Remote');
    $short_description = trim($_POST['short_description'] ?? '');
    $description       = trim($_POST['description'] ?? '');
    $responsibilities  = trim($_POST['responsibilities'] ?? '');
    $requirements      = trim($_POST['requirements'] ?? '');
    $status            = trim($_POST['status'] ?? 'open');

    if (empty($title)) {
        $errors[] = 'Job title is required.';
    }

    if (empty($slug)) {
        $slug = slugify($title);
    } else {
        $slug = slugify($slug);
    }

    if (empty($errors)) {
        if ($isEditing) {
            $upSql = "UPDATE job_positions SET 
                title = ?, slug = ?, department = ?, work_type = ?, location = ?, 
                short_description = ?, description = ?, responsibilities = ?, requirements = ?, 
                status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
            $upStmt = $db->prepare($upSql);
            $upStmt->execute([
                $title, $slug, $department, $work_type, $location,
                $short_description, $description, $responsibilities, $requirements,
                $status, $id
            ]);
            set_flash('success', 'Job position updated.');
        } else {
            $inSql = "INSERT INTO job_positions (
                title, slug, department, work_type, location, 
                short_description, description, responsibilities, requirements, 
                status, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
            $inStmt = $db->prepare($inSql);
            $inStmt->execute([
                $title, $slug, $department, $work_type, $location,
                $short_description, $description, $responsibilities, $requirements,
                $status
            ]);
            set_flash('success', 'New job vacancy posted.');
        }

        header('Location: ' . url('admin/jobs.php'));
        exit;
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="mb-8">
    <a href="<?= url('admin/jobs.php') ?>" class="inline-flex items-center gap-2 font-mono text-xs text-accent hover:text-white transition-colors mb-4">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        <span>BACK TO JOBS LIST</span>
    </a>
    <h2 class="text-3xl font-heading font-extrabold text-white"><?= $isEditing ? 'Edit: ' . e($job['title']) : 'Post New Job Vacancy' ?></h2>
</div>

<?php if (!empty($errors)): ?>
<div class="mb-8 p-4 rounded-xl bg-rose-950/80 border border-rose-500/50 text-rose-200 text-sm">
    <ul class="list-disc pl-5 space-y-1 text-xs">
        <?php foreach ($errors as $err): ?>
            <li><?= e($err) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form action="<?= url('admin/job-edit.php' . ($isEditing ? '?id=' . $id : '')) ?>" method="POST" class="space-y-8 max-w-4xl">
    <?= csrf_field() ?>

    <div class="p-8 rounded-2xl bg-card-bg border border-border-dark space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="title" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                    Job Title <span class="text-accent">*</span>
                </label>
                <input type="text" id="title" name="title" required value="<?= e($job['title']) ?>" placeholder="e.g. Senior UI/UX Designer" class="form-input text-sm">
            </div>
            <div>
                <label for="slug" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                    Slug <span class="text-offwhite/40 text-[10px]">(Auto-generated if empty)</span>
                </label>
                <input type="text" id="slug" name="slug" value="<?= e($job['slug']) ?>" placeholder="senior-ui-ux-designer" class="form-input text-sm font-mono">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
            <div>
                <label for="department" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">Department</label>
                <input type="text" id="department" name="department" value="<?= e($job['department']) ?>" placeholder="Design, Engineering, Strategy" class="form-input text-xs">
            </div>
            <div>
                <label for="work_type" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">Type</label>
                <select id="work_type" name="work_type" class="form-input text-xs font-mono">
                    <option value="Full-time" <?= ($job['work_type'] === 'Full-time') ? 'selected' : '' ?>>Full-time</option>
                    <option value="Part-time" <?= ($job['work_type'] === 'Part-time') ? 'selected' : '' ?>>Part-time</option>
                    <option value="Contract" <?= ($job['work_type'] === 'Contract') ? 'selected' : '' ?>>Contract</option>
                </select>
            </div>
            <div>
                <label for="location" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">Location</label>
                <input type="text" id="location" name="location" value="<?= e($job['location']) ?>" placeholder="Remote / Jakarta" class="form-input text-xs">
            </div>
            <div>
                <label for="status" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">Status</label>
                <select id="status" name="status" class="form-input text-xs font-mono">
                    <option value="open" <?= ($job['status'] === 'open') ? 'selected' : '' ?>>Open</option>
                    <option value="closed" <?= ($job['status'] === 'closed') ? 'selected' : '' ?>>Closed</option>
                </select>
            </div>
        </div>

        <div>
            <label for="short_description" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">Teaser Summary</label>
            <textarea id="short_description" name="short_description" rows="2" placeholder="Brief summary of the role..." class="form-input text-sm"><?= e($job['short_description']) ?></textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="responsibilities" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                    Key Responsibilities <span class="text-offwhite/40 text-[10px]">(One per line)</span>
                </label>
                <textarea id="responsibilities" name="responsibilities" rows="6" placeholder="- Lead design discovery workshops&#10;- Maintain design token library&#10;- Conduct user research..." class="form-input text-sm"><?= e($job['responsibilities']) ?></textarea>
            </div>
            <div>
                <label for="requirements" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                    Requirements &amp; Experience <span class="text-offwhite/40 text-[10px]">(One per line)</span>
                </label>
                <textarea id="requirements" name="requirements" rows="6" placeholder="- 5+ years of product design experience&#10;- Deep mastery of Figma&#10;- Strong portfolio demonstrating impact..." class="form-input text-sm"><?= e($job['requirements']) ?></textarea>
            </div>
        </div>

        <div class="pt-4 border-t border-border-dark flex justify-end">
            <button type="submit" class="btn-primary py-3.5 px-8 text-xs font-mono uppercase tracking-wider">
                <?= $isEditing ? 'UPDATE JOB VACANCY' : 'POST JOB VACANCY' ?>
            </button>
        </div>
    </div>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

