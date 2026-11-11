<?php
/**
 * NOVA Creative Digital Agency
 * Contact Page & Project Inquiry Form (contact.php)
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/helpers.php';

$pageTitle = 'Contact Us — Start a Project Inquiry';
$pageDesc = 'Get in touch with NOVA. Submit a project inquiry for Brand Identity, UI/UX Design, Web Development, or Creative Strategy.';

$preselectedService = trim($_GET['service'] ?? '');

$errors = [];
$formData = [
    'full_name'        => '',
    'email'            => '',
    'company_name'     => '',
    'service_needed'   => $preselectedService,
    'estimated_budget' => '',
    'project_timeline' => '',
    'description'      => ''
];

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $errors[] = 'Security token invalid or expired. Please reload the page and try again.';
    }

    $formData['full_name']        = trim($_POST['full_name'] ?? '');
    $formData['email']            = trim($_POST['email'] ?? '');
    $formData['company_name']     = trim($_POST['company_name'] ?? '');
    $formData['service_needed']   = trim($_POST['service_needed'] ?? '');
    $formData['estimated_budget'] = trim($_POST['estimated_budget'] ?? '');
    $formData['project_timeline'] = trim($_POST['project_timeline'] ?? '');
    $formData['description']      = trim($_POST['description'] ?? '');

    // Validation
    if (empty($formData['full_name'])) {
        $errors[] = 'Full name is required.';
    }
    if (empty($formData['email']) || !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email address is required.';
    }
    if (empty($formData['service_needed'])) {
        $errors[] = 'Please select a service needed.';
    }
    if (empty($formData['description']) || strlen($formData['description']) < 15) {
        $errors[] = 'Please provide a project description (at least 15 characters).';
    }

    // Save to Database
    if (empty($errors)) {
        try {
            $db = getDB();
            $stmt = $db->prepare("INSERT INTO inquiries (
                full_name, email, company_name, service_needed, estimated_budget, project_timeline, description, status, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, 'New', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");

            $stmt->execute([
                $formData['full_name'],
                $formData['email'],
                $formData['company_name'],
                $formData['service_needed'],
                $formData['estimated_budget'],
                $formData['project_timeline'],
                $formData['description']
            ]);

            set_flash('success', 'Thank you! Your project inquiry has been received. Our leadership team will review your requirements and reach out within 24 business hours.');
            header('Location: ' . url('contact.php?status=success'));
            exit;
        } catch (Exception $e) {
            $errors[] = 'Failed to submit inquiry due to a database error. Please try again or email us directly.';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Contact Hero Section -->
<section class="py-24 bg-primary-dark border-b border-[#222222] relative overflow-hidden">
    <div class="absolute -right-20 top-10 w-96 h-96 bg-accent/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-4xl">
            <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-mono uppercase tracking-wider text-accent bg-accent/10 border border-accent/20 mb-6">
                GET IN TOUCH
            </span>
            <h1 class="text-4xl sm:text-6xl md:text-7xl font-heading font-black text-white tracking-tight leading-[1.08] mb-8">
                Let's start a conversation that transforms your business.
            </h1>
            <p class="text-xl sm:text-2xl text-offwhite/70 font-light leading-relaxed max-w-3xl">
                Tell us about your objectives, timeline, and vision. We will follow up with an initial discovery perspective within 24 hours.
            </p>
        </div>
    </div>
</section>

<!-- Contact Content & Inquiry Form -->
<section class="py-24 bg-[#141414]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
            <!-- Left Column: Agency Contact Details & Office -->
            <div class="lg:col-span-5 space-y-12">
                <div>
                    <span class="font-mono text-xs text-accent uppercase tracking-widest block mb-3">DIRECT CHANNELS</span>
                    <h2 class="text-3xl font-heading font-bold text-white mb-6">
                        We're ready to partner with you.
                    </h2>
                    <p class="text-offwhite/70 font-light leading-relaxed mb-8">
                        Whether you have an RFP ready for review or simply want to explore potential collaboration, our partners are accessible directly.
                    </p>
                </div>

                <!-- Contact Details Cards -->
                <div class="space-y-6">
                    <div class="p-6 rounded-2xl bg-primary-dark border border-[#2B2B2B] flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-accent/10 border border-accent/25 flex items-center justify-center text-accent flex-shrink-0">
                            <i data-lucide="mail" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <span class="font-mono text-xs text-muted-gray uppercase block mb-1">GENERAL &amp; NEW BUSINESS</span>
                            <a href="mailto:<?= e(get_setting('contact_email', 'hello@nova.agency')) ?>" class="font-heading font-bold text-white text-lg hover:text-accent transition-colors">
                                <?= e(get_setting('contact_email', 'hello@nova.agency')) ?>
                            </a>
                        </div>
                    </div>

                    <div class="p-6 rounded-2xl bg-primary-dark border border-[#2B2B2B] flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-accent/10 border border-accent/25 flex items-center justify-center text-accent flex-shrink-0">
                            <i data-lucide="phone-call" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <span class="font-mono text-xs text-muted-gray uppercase block mb-1">PHONE &amp; WHATSAPP</span>
                            <a href="tel:<?= e(get_setting('contact_whatsapp', '+6281289006682')) ?>" class="font-heading font-bold text-white text-lg hover:text-accent transition-colors block">
                                <?= e(get_setting('contact_phone', '+62 21 555-8900')) ?>
                            </a>
                            <span class="text-xs font-mono text-emerald-400 mt-1 block">WhatsApp: <?= e(get_setting('contact_whatsapp', '+62 812-8900-6682')) ?></span>
                        </div>
                    </div>

                    <div class="p-6 rounded-2xl bg-primary-dark border border-[#2B2B2B] flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-accent/10 border border-accent/25 flex items-center justify-center text-accent flex-shrink-0">
                            <i data-lucide="map-pin" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <span class="font-mono text-xs text-muted-gray uppercase block mb-1">OFFICE HEADQUARTERS</span>
                            <p class="font-heading font-bold text-white text-base leading-snug">
                                <?= e(get_setting('office_address', 'SCBD District 8, Treasury Tower Level 24, Jakarta, Indonesia')) ?>
                            </p>
                            <span class="text-xs font-mono text-muted-gray mt-2 block">
                                <?= e(get_setting('working_hours', 'Mon - Fri: 09:00 - 18:00 WIB')) ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Response Commitment Badge -->
                <div class="p-6 rounded-2xl bg-surface-dark border border-[#262626]">
                    <div class="flex items-center gap-3 text-white font-heading font-bold text-sm mb-2">
                        <i data-lucide="shield-check" class="w-5 h-5 text-accent"></i>
                        <span>Client Privacy &amp; Non-Disclosure</span>
                    </div>
                    <p class="text-xs text-offwhite/60 leading-relaxed font-light">
                        All project inquiries and business ideas shared with NOVA are strictly confidential. We are pleased to execute an NDA prior to in-depth discovery calls.
                    </p>
                </div>
            </div>

            <!-- Right Column: Project Inquiry Form (PRD 4.6) -->
            <div class="lg:col-span-7">
                <div class="p-8 sm:p-12 rounded-3xl bg-primary-dark border border-[#2B2B2B] relative">
                    <span class="font-mono text-xs text-accent uppercase tracking-widest block mb-2">PROJECT INQUIRY FORM</span>
                    <h3 class="text-2xl sm:text-3xl font-heading font-extrabold text-white mb-8">
                        Tell us about your project.
                    </h3>

                    <?php if (!empty($errors)): ?>
                    <div class="mb-8 p-4 rounded-xl bg-rose-950/80 border border-rose-500/50 text-rose-200 text-sm">
                        <div class="flex items-center gap-2 font-bold mb-2">
                            <i data-lucide="alert-circle" class="w-4 h-4"></i>
                            <span>Please correct the following:</span>
                        </div>
                        <ul class="list-disc pl-5 space-y-1 text-xs">
                            <?php foreach ($errors as $err): ?>
                                <li><?= e($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <form action="<?= url('contact.php') ?>" method="POST" class="space-y-6">
                        <?= csrf_field() ?>

                        <!-- Full Name & Email -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="full_name" class="block text-xs font-mono uppercase tracking-wider text-offwhite/80 mb-2">
                                    Full Name <span class="text-accent">*</span>
                                </label>
                                <input type="text" 
                                       id="full_name" 
                                       name="full_name" 
                                       required
                                       value="<?= e($formData['full_name']) ?>" 
                                       placeholder="e.g. Alex Morgan" 
                                       class="form-input">
                            </div>
                            <div>
                                <label for="email" class="block text-xs font-mono uppercase tracking-wider text-offwhite/80 mb-2">
                                    Work Email Address <span class="text-accent">*</span>
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       required
                                       value="<?= e($formData['email']) ?>" 
                                       placeholder="alex@company.com" 
                                       class="form-input">
                            </div>
                        </div>

                        <!-- Company Name -->
                        <div>
                            <label for="company_name" class="block text-xs font-mono uppercase tracking-wider text-offwhite/80 mb-2">
                                Company / Brand Name <span class="text-offwhite/40 text-[10px]">(Optional)</span>
                            </label>
                            <input type="text" 
                                   id="company_name" 
                                   name="company_name" 
                                   value="<?= e($formData['company_name']) ?>" 
                                   placeholder="e.g. Acme Studio or Stealth Startup" 
                                   class="form-input">
                        </div>

                        <!-- Service Needed -->
                        <div>
                            <label for="service_needed" class="block text-xs font-mono uppercase tracking-wider text-offwhite/80 mb-2">
                                Service Needed <span class="text-accent">*</span>
                            </label>
                            <select id="service_needed" name="service_needed" required class="form-input">
                                <option value="" disabled <?= empty($formData['service_needed']) ? 'selected' : '' ?>>-- Select primary capability --</option>
                                <option value="Brand Identity" <?= ($formData['service_needed'] === 'Brand Identity') ? 'selected' : '' ?>>Brand Identity (Logo, Strategy, Guidelines)</option>
                                <option value="UI/UX Design" <?= ($formData['service_needed'] === 'UI/UX Design') ? 'selected' : '' ?>>UI/UX Design (Web, Mobile App, Design System)</option>
                                <option value="Website Development" <?= ($formData['service_needed'] === 'Website Development') ? 'selected' : '' ?>>Website Development (Full-Stack, CMS, 3D/Interactive)</option>
                                <option value="Graphic Design" <?= ($formData['service_needed'] === 'Graphic Design') ? 'selected' : '' ?>>Graphic Design (Pitch Decks, Editorial, Packaging)</option>
                                <option value="Digital Marketing" <?= ($formData['service_needed'] === 'Digital Marketing') ? 'selected' : '' ?>>Digital Marketing (Campaigns, SEO, Strategy)</option>
                                <option value="Comprehensive Partner" <?= ($formData['service_needed'] === 'Comprehensive Partner') ? 'selected' : '' ?>>Comprehensive Full-Stack Agency Partner</option>
                            </select>
                        </div>

                        <!-- Budget & Timeline -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="estimated_budget" class="block text-xs font-mono uppercase tracking-wider text-offwhite/80 mb-2">
                                    Estimated Budget <span class="text-offwhite/40 text-[10px]">(USD)</span>
                                </label>
                                <select id="estimated_budget" name="estimated_budget" class="form-input">
                                    <option value="" <?= empty($formData['estimated_budget']) ? 'selected' : '' ?>>-- Select budget range --</option>
                                    <option value="< $5,000" <?= ($formData['estimated_budget'] === '< $5,000') ? 'selected' : '' ?>>&lt; $5,000</option>
                                    <option value="$5,000 - $10,000" <?= ($formData['estimated_budget'] === '$5,000 - $10,000') ? 'selected' : '' ?>>$5,000 - $10,000</option>
                                    <option value="$10,000 - $25,000" <?= ($formData['estimated_budget'] === '$10,000 - $25,000') ? 'selected' : '' ?>>$10,000 - $25,000</option>
                                    <option value="$25,000+" <?= ($formData['estimated_budget'] === '$25,000+') ? 'selected' : '' ?>>$25,000+</option>
                                </select>
                            </div>
                            <div>
                                <label for="project_timeline" class="block text-xs font-mono uppercase tracking-wider text-offwhite/80 mb-2">
                                    Project Timeline
                                </label>
                                <select id="project_timeline" name="project_timeline" class="form-input">
                                    <option value="" <?= empty($formData['project_timeline']) ? 'selected' : '' ?>>-- Desired launch target --</option>
                                    <option value="< 1 month" <?= ($formData['project_timeline'] === '< 1 month') ? 'selected' : '' ?>>Immediate (&lt; 1 month)</option>
                                    <option value="1-3 months" <?= ($formData['project_timeline'] === '1-3 months') ? 'selected' : '' ?>>1 – 3 months</option>
                                    <option value="3-6 months" <?= ($formData['project_timeline'] === '3-6 months') ? 'selected' : '' ?>>3 – 6 months</option>
                                    <option value="Flexible" <?= ($formData['project_timeline'] === 'Flexible') ? 'selected' : '' ?>>Flexible timeline</option>
                                </select>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-xs font-mono uppercase tracking-wider text-offwhite/80 mb-2">
                                Project Description &amp; Objectives <span class="text-accent">*</span>
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="5" 
                                      required
                                      placeholder="Briefly describe your company, target audience, core objectives, and any reference links or inspirations..." 
                                      class="form-input"><?= e($formData['description']) ?></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <button type="submit" class="btn-primary w-full py-4 text-sm group">
                                <span>SUBMIT PROJECT INQUIRY</span>
                                <i data-lucide="send" class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </button>
                            <span class="text-[11px] font-mono text-muted-gray text-center block mt-3">
                                We respect your privacy. No spam, ever.
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

