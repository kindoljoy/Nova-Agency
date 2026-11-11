<?php
/**
 * Admin Add / Edit Article (admin/article-edit.php)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helpers.php';

$db = getDB();
$id = (int)($_GET['id'] ?? 0);
$isEditing = ($id > 0);

$pageTitle = $isEditing ? 'Edit Article' : 'Write New Article';

// Fetch article categories
$catStmt = $db->query("SELECT * FROM categories WHERE type = 'article' ORDER BY name ASC");
$categories = $catStmt->fetchAll();

$article = [
    'title'        => '',
    'slug'         => '',
    'category_id'  => 0,
    'author'       => 'Alex Nova',
    'excerpt'      => '',
    'body'         => '',
    'cover_image'  => '',
    'read_time'    => '5 min read',
    'publish_date' => date('Y-m-d'),
    'is_featured'  => 0,
    'status'       => 'published'
];

if ($isEditing) {
    $stmt = $db->prepare("SELECT * FROM articles WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $existing = $stmt->fetch();
    if (!$existing) {
        set_flash('error', 'Article not found.');
        header('Location: ' . url('admin/articles.php'));
        exit;
    }
    $article = array_merge($article, $existing);
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $errors[] = 'Security token invalid.';
    }

    $title        = trim($_POST['title'] ?? '');
    $slug         = trim($_POST['slug'] ?? '');
    $category_id  = (int)($_POST['category_id'] ?? 0);
    $author       = trim($_POST['author'] ?? 'Alex Nova');
    $excerpt      = trim($_POST['excerpt'] ?? '');
    $body         = trim($_POST['body'] ?? '');
    $read_time    = trim($_POST['read_time'] ?? '5 min read');
    $publish_date = trim($_POST['publish_date'] ?? date('Y-m-d'));
    $is_featured  = isset($_POST['is_featured']) ? 1 : 0;
    $status       = trim($_POST['status'] ?? 'published');

    if (empty($title)) {
        $errors[] = 'Article title is required.';
    }

    if (empty($slug)) {
        $slug = slugify($title);
    } else {
        $slug = slugify($slug);
    }

    // Check slug uniqueness
    $checkSql = "SELECT id FROM articles WHERE slug = ?" . ($isEditing ? " AND id != ?" : "");
    $checkParams = $isEditing ? [$slug, $id] : [$slug];
    $checkStmt = $db->prepare($checkSql);
    $checkStmt->execute($checkParams);
    if ($checkStmt->fetch()) {
        $errors[] = 'The slug "' . $slug . '" is already in use by another article.';
    }

    // Handle Cover Image Upload
    $cover_image = $article['cover_image'];
    if (!empty($_FILES['image_file']['name'])) {
        $upload = handle_file_upload($_FILES['image_file'], ARTICLE_UPLOADS_PATH, ALLOWED_IMAGE_EXT, MAX_IMAGE_SIZE);
        if ($upload['success']) {
            $cover_image = $upload['filename'];
        } else {
            $errors[] = $upload['error'];
        }
    }

    if (empty($errors)) {
        if ($isEditing) {
            $upSql = "UPDATE articles SET 
                title = ?, slug = ?, category_id = ?, author = ?, excerpt = ?, 
                body = ?, cover_image = ?, read_time = ?, publish_date = ?, 
                is_featured = ?, status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
            $upStmt = $db->prepare($upSql);
            $upStmt->execute([
                $title, $slug, $category_id, $author, $excerpt,
                $body, $cover_image, $read_time, $publish_date,
                $is_featured, $status, $id
            ]);
            set_flash('success', 'Article updated successfully.');
        } else {
            $inSql = "INSERT INTO articles (
                title, slug, category_id, author, excerpt, 
                body, cover_image, read_time, publish_date, 
                is_featured, status, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
            $inStmt = $db->prepare($inSql);
            $inStmt->execute([
                $title, $slug, $category_id, $author, $excerpt,
                $body, $cover_image, $read_time, $publish_date,
                $is_featured, $status
            ]);
            set_flash('success', 'New article published successfully.');
        }

        header('Location: ' . url('admin/articles.php'));
        exit;
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="mb-8">
    <a href="<?= url('admin/articles.php') ?>" class="inline-flex items-center gap-2 font-mono text-xs text-accent hover:text-white transition-colors mb-4">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        <span>BACK TO ARTICLES LIST</span>
    </a>
    <h2 class="text-3xl font-heading font-extrabold text-white"><?= $isEditing ? 'Edit: ' . e($article['title']) : 'Write New Perspective Essay' ?></h2>
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

<form action="<?= url('admin/article-edit.php' . ($isEditing ? '?id=' . $id : '')) ?>" method="POST" enctype="multipart/form-data" class="space-y-8">
    <?= csrf_field() ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-8 space-y-6">
            <div class="p-8 rounded-2xl bg-card-bg border border-border-dark space-y-6">
                <div>
                    <label for="title" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                        Article Headline <span class="text-accent">*</span>
                    </label>
                    <input type="text" id="title" name="title" required value="<?= e($article['title']) ?>" placeholder="e.g. Radical Character Wins in 2027" class="form-input text-sm">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="category_id" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                            Category
                        </label>
                        <select id="category_id" name="category_id" class="form-input text-sm">
                            <option value="">-- Select Category --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= ($article['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                    <?= e($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="slug" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                            Slug <span class="text-offwhite/40 text-[10px]">(Auto-generated if empty)</span>
                        </label>
                        <input type="text" id="slug" name="slug" value="<?= e($article['slug']) ?>" placeholder="radical-character-wins-2027" class="form-input text-sm font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <label for="author" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                            Author Name
                        </label>
                        <input type="text" id="author" name="author" value="<?= e($article['author']) ?>" class="form-input text-sm">
                    </div>
                    <div>
                        <label for="read_time" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                            Read Time
                        </label>
                        <input type="text" id="read_time" name="read_time" value="<?= e($article['read_time']) ?>" placeholder="6 min read" class="form-input text-sm font-mono">
                    </div>
                    <div>
                        <label for="publish_date" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                            Publication Date
                        </label>
                        <input type="date" id="publish_date" name="publish_date" value="<?= e($article['publish_date']) ?>" class="form-input text-sm font-mono">
                    </div>
                </div>

                <div>
                    <label for="excerpt" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                        Summary Excerpt
                    </label>
                    <textarea id="excerpt" name="excerpt" rows="3" placeholder="Brief teaser paragraph displayed on card listings..." class="form-input text-sm"><?= e($article['excerpt']) ?></textarea>
                </div>

                <div>
                    <label for="body" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                        Full Article Body Content (HTML supported)
                    </label>
                    <textarea id="body" name="body" rows="12" placeholder="Write full article text here. You can use standard HTML like &lt;p&gt;, &lt;h3&gt;, &lt;ul&gt;, &lt;li&gt;, &lt;blockquote&gt;..." class="form-input text-sm font-mono"><?= e($article['body']) ?></textarea>
                </div>
            </div>
        </div>

        <!-- Sidebar Options -->
        <div class="lg:col-span-4 space-y-6">
            <div class="p-8 rounded-2xl bg-card-bg border border-border-dark space-y-6">
                <h3 class="font-heading font-bold text-white text-lg border-b border-border-dark pb-4">Publishing Controls</h3>

                <div>
                    <label for="status" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                        Status
                    </label>
                    <select id="status" name="status" class="form-input text-xs font-mono">
                        <option value="published" <?= ($article['status'] === 'published') ? 'selected' : '' ?>>Published (Visible to public)</option>
                        <option value="draft" <?= ($article['status'] === 'draft') ? 'selected' : '' ?>>Draft (Internal)</option>
                    </select>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <input type="checkbox" id="is_featured" name="is_featured" value="1" <?= !empty($article['is_featured']) ? 'checked' : '' ?> class="w-4 h-4 rounded text-accent bg-primary-dark border-border-dark focus:ring-accent">
                    <label for="is_featured" class="text-sm font-medium text-white cursor-pointer select-none">
                        Featured on Insights Hero
                    </label>
                </div>

                <div class="pt-4 border-t border-border-dark">
                    <button type="submit" class="btn-primary w-full py-3.5 text-xs font-mono uppercase tracking-wider">
                        <?= $isEditing ? 'UPDATE ARTICLE' : 'PUBLISH ESSAY' ?>
                    </button>
                </div>
            </div>

            <!-- Cover Image Upload -->
            <div class="p-8 rounded-2xl bg-card-bg border border-border-dark space-y-4">
                <h3 class="font-heading font-bold text-white text-lg">Article Cover Image</h3>

                <?php if (!empty($article['cover_image'])): ?>
                <div class="aspect-[16/10] rounded-xl overflow-hidden bg-black border border-border-dark">
                    <img src="<?= upload_url('articles', $article['cover_image']) ?>" alt="Current preview" class="w-full h-full object-cover">
                </div>
                <span class="text-[11px] font-mono text-muted-gray block">Current file: <?= e($article['cover_image']) ?></span>
                <?php endif; ?>

                <div>
                    <label for="image_file" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">
                        Upload Cover Image <span class="text-offwhite/40 text-[10px]">(JPG, PNG, WebP, SVG)</span>
                    </label>
                    <input type="file" id="image_file" name="image_file" accept=".jpg,.jpeg,.png,.webp,.svg" class="form-input file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-mono file:bg-charcoal file:text-white hover:file:bg-accent hover:file:text-black">
                </div>
            </div>
        </div>
    </div>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

