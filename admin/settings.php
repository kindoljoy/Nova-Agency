<?php
/**
 * Admin Website Settings & Profile (admin/settings.php)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helpers.php';

$pageTitle = 'Website Settings';
$db = getDB();

$adminId = $_SESSION['admin_id'];

// Handle Settings Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_settings') {
    if (verify_csrf()) {
        $allowedKeys = [
            'agency_name', 'agency_tagline', 'contact_email', 'contact_phone',
            'contact_whatsapp', 'office_address', 'working_hours',
            'social_instagram', 'social_linkedin', 'social_dribbble',
            'social_behance', 'social_x', 'meta_title', 'meta_description'
        ];

        $stmt = $db->prepare("INSERT INTO site_settings (setting_key, setting_value, updated_at) VALUES (?, ?, CURRENT_TIMESTAMP)
            ON CONFLICT(setting_key) DO UPDATE SET setting_value = excluded.setting_value, updated_at = CURRENT_TIMESTAMP");

        // Check if sqlite or mysql for upsert syntax
        $isMysql = (Database::getDriverUsed() === 'mysql');
        if ($isMysql) {
            $stmt = $db->prepare("INSERT INTO site_settings (setting_key, setting_value, updated_at) VALUES (?, ?, CURRENT_TIMESTAMP)
                ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_at = CURRENT_TIMESTAMP");
        }

        foreach ($allowedKeys as $key) {
            if (isset($_POST[$key])) {
                $val = trim($_POST[$key]);
                $stmt->execute([$key, $val]);
            }
        }

        set_flash('success', 'Agency website settings updated successfully.');
        header('Location: ' . url('admin/settings.php'));
        exit;
    }
}

// Handle Admin Password Change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'change_password') {
    if (verify_csrf()) {
        $currentPass = $_POST['current_password'] ?? '';
        $newPass = $_POST['new_password'] ?? '';
        $confirmPass = $_POST['confirm_password'] ?? '';

        if (empty($newPass) || strlen($newPass) < 6) {
            set_flash('error', 'New password must be at least 6 characters long.');
        } elseif ($newPass !== $confirmPass) {
            set_flash('error', 'New password and confirmation do not match.');
        } else {
            $chkStmt = $db->prepare("SELECT password_hash FROM admins WHERE id = ? LIMIT 1");
            $chkStmt->execute([$adminId]);
            $adminRow = $chkStmt->fetch();

            if ($adminRow && password_verify($currentPass, $adminRow['password_hash'])) {
                $newHash = password_hash($newPass, PASSWORD_BCRYPT);
                $upStmt = $db->prepare("UPDATE admins SET password_hash = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
                $upStmt->execute([$newHash, $adminId]);
                set_flash('success', 'Admin password changed successfully.');
            } else {
                set_flash('error', 'The current password you entered is incorrect.');
            }
        }
        header('Location: ' . url('admin/settings.php'));
        exit;
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="mb-8">
    <h2 class="text-2xl font-heading font-extrabold text-white">Website &amp; Agency Settings</h2>
    <p class="text-xs text-muted-gray mt-1">Configure global contact details, social links, SEO defaults, and admin security.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <!-- General Settings Form -->
    <div class="lg:col-span-8 space-y-8">
        <form action="<?= url('admin/settings.php') ?>" method="POST" class="space-y-8">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="save_settings">

            <!-- Agency Brand & Identity -->
            <div class="p-8 rounded-2xl bg-card-bg border border-border-dark space-y-6">
                <h3 class="font-heading font-bold text-white text-lg border-b border-border-dark pb-4">Brand Information</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="agency_name" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">Agency Name</label>
                        <input type="text" id="agency_name" name="agency_name" value="<?= e(get_setting('agency_name', 'NOVA® Creative Digital Agency')) ?>" class="form-input text-xs">
                    </div>
                    <div>
                        <label for="agency_tagline" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">Tagline</label>
                        <input type="text" id="agency_tagline" name="agency_tagline" value="<?= e(get_setting('agency_tagline', 'We Build Brands. We Create Experiences.')) ?>" class="form-input text-xs">
                    </div>
                </div>
            </div>

            <!-- Contact & Office -->
            <div class="p-8 rounded-2xl bg-card-bg border border-border-dark space-y-6">
                <h3 class="font-heading font-bold text-white text-lg border-b border-border-dark pb-4">Direct Contact &amp; Headquarters</h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <label for="contact_email" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">New Business Email</label>
                        <input type="email" id="contact_email" name="contact_email" value="<?= e(get_setting('contact_email', 'hello@nova.agency')) ?>" class="form-input text-xs">
                    </div>
                    <div>
                        <label for="contact_phone" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">Office Phone</label>
                        <input type="text" id="contact_phone" name="contact_phone" value="<?= e(get_setting('contact_phone', '+62 21 555-8900')) ?>" class="form-input text-xs">
                    </div>
                    <div>
                        <label for="contact_whatsapp" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">WhatsApp Line</label>
                        <input type="text" id="contact_whatsapp" name="contact_whatsapp" value="<?= e(get_setting('contact_whatsapp', '+62 812-8900-6682')) ?>" class="form-input text-xs">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="office_address" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">Headquarters Address</label>
                        <textarea id="office_address" name="office_address" rows="2" class="form-input text-xs"><?= e(get_setting('office_address', 'SCBD District 8, Treasury Tower Level 24, South Jakarta, Indonesia')) ?></textarea>
                    </div>
                    <div>
                        <label for="working_hours" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">Operating Hours</label>
                        <input type="text" id="working_hours" name="working_hours" value="<?= e(get_setting('working_hours', 'Monday – Friday, 09:00 – 18:00 WIB')) ?>" class="form-input text-xs">
                    </div>
                </div>
            </div>

            <!-- Social Media Channels -->
            <div class="p-8 rounded-2xl bg-card-bg border border-border-dark space-y-6">
                <h3 class="font-heading font-bold text-white text-lg border-b border-border-dark pb-4">Social Presence</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="social_instagram" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">Instagram URL</label>
                        <input type="url" id="social_instagram" name="social_instagram" value="<?= e(get_setting('social_instagram', 'https://instagram.com/novaagency')) ?>" class="form-input text-xs font-mono">
                    </div>
                    <div>
                        <label for="social_linkedin" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">LinkedIn URL</label>
                        <input type="url" id="social_linkedin" name="social_linkedin" value="<?= e(get_setting('social_linkedin', 'https://linkedin.com/company/nova-creative-agency')) ?>" class="form-input text-xs font-mono">
                    </div>
                    <div>
                        <label for="social_dribbble" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">Dribbble URL</label>
                        <input type="url" id="social_dribbble" name="social_dribbble" value="<?= e(get_setting('social_dribbble', 'https://dribbble.com/novaagency')) ?>" class="form-input text-xs font-mono">
                    </div>
                    <div>
                        <label for="social_x" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">Twitter / X URL</label>
                        <input type="url" id="social_x" name="social_x" value="<?= e(get_setting('social_x', 'https://x.com/novaagency')) ?>" class="form-input text-xs font-mono">
                    </div>
                </div>
            </div>

            <!-- SEO Defaults -->
            <div class="p-8 rounded-2xl bg-card-bg border border-border-dark space-y-6">
                <h3 class="font-heading font-bold text-white text-lg border-b border-border-dark pb-4">Search Engine Optimization (SEO)</h3>

                <div>
                    <label for="meta_title" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">Default Meta Title</label>
                    <input type="text" id="meta_title" name="meta_title" value="<?= e(get_setting('meta_title', 'NOVA® — Creative Digital Agency | We Build Brands, We Create Experiences')) ?>" class="form-input text-xs">
                </div>

                <div>
                    <label for="meta_description" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">Default Meta Description</label>
                    <textarea id="meta_description" name="meta_description" rows="3" class="form-input text-xs"><?= e(get_setting('meta_description', 'NOVA is an award-winning creative digital agency specializing in Brand Identity, UI/UX Design, Website Development, and Digital Strategy for ambitious companies.')) ?></textarea>
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit" class="btn-primary py-3.5 px-8 text-xs font-mono uppercase tracking-wider">
                    SAVE WEBSITE SETTINGS
                </button>
            </div>
        </form>
    </div>

    <!-- Security & Password Change Column -->
    <div class="lg:col-span-4 space-y-6">
        <div class="p-8 rounded-2xl bg-card-bg border border-border-dark">
            <h3 class="font-heading font-bold text-white text-lg mb-6">Change Admin Password</h3>

            <form action="<?= url('admin/settings.php') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="change_password">

                <div>
                    <label for="current_password" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required placeholder="••••••••" class="form-input text-xs">
                </div>

                <div>
                    <label for="new_password" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">New Password (Min 6 chars)</label>
                    <input type="password" id="new_password" name="new_password" required placeholder="••••••••" class="form-input text-xs">
                </div>

                <div>
                    <label for="confirm_password" class="block text-xs font-mono uppercase tracking-wider text-muted-gray mb-2">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required placeholder="••••••••" class="form-input text-xs">
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn-secondary w-full py-3 text-xs font-mono uppercase tracking-wider hover:border-accent hover:text-accent">
                        UPDATE PASSWORD
                    </button>
                </div>
            </form>
        </div>

        <!-- System Information -->
        <div class="p-6 rounded-2xl bg-[#141414] border border-border-dark space-y-3 text-xs font-mono">
            <span class="text-muted-gray uppercase block tracking-wider">ENVIRONMENT INFO</span>
            <div class="flex justify-between text-offwhite/80">
                <span>PHP Version:</span>
                <span class="text-white"><?= PHP_VERSION ?></span>
            </div>
            <div class="flex justify-between text-offwhite/80">
                <span>Database Driver:</span>
                <span class="text-white uppercase"><?= Database::getDriverUsed() ?></span>
            </div>
            <div class="flex justify-between text-offwhite/80">
                <span>Platform:</span>
                <span class="text-white">Windows</span>
            </div>
            <div class="flex justify-between text-offwhite/80">
                <span>App Version:</span>
                <span class="text-white"><?= APP_VERSION ?></span>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

