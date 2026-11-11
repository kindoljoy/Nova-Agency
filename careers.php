<?php
/**
 * NOVA Creative Digital Agency
 * Careers Page & Job Application System (careers.php)
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/helpers.php';

$pageTitle = 'Careers — Join the Collective at NOVA®';
$pageDesc = 'Explore open roles in digital design, brand strategy, and full-stack engineering. Build boundary-pushing experiences with our global team.';

$db = getDB();

// Fetch open positions
$jobsStmt = $db->query("SELECT * FROM job_positions WHERE status = 'open' ORDER BY created_at DESC");
$jobs = $jobsStmt->fetchAll();

$preselectedJobId = (int)($_GET['job_id'] ?? 0);

$errors = [];
$formData = [
    'full_name'     => '',
    'email'         => '',
    'phone'         => '',
    'job_id'        => $preselectedJobId,
    'portfolio_url' => '',
    'cover_note'    => ''
];

// Handle Job Application Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $errors[] = 'Security token invalid. Please refresh the page and try again.';
    }

    $formData['full_name']     = trim($_POST['full_name'] ?? '');
    $formData['email']         = trim($_POST['email'] ?? '');
    $formData['phone']         = trim($_POST['phone'] ?? '');
    $formData['job_id']        = (int)($_POST['job_id'] ?? 0);
    $formData['portfolio_url'] = trim($_POST['portfolio_url'] ?? '');
    $formData['cover_note']    = trim($_POST['cover_note'] ?? '');

    // Validation
    if (empty($formData['full_name'])) {
        $errors[] = 'Full name is required.';
    }
    if (empty($formData['email']) || !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email address is required.';
    }
    if ($formData['job_id'] <= 0) {
        $errors[] = 'Please select the position you are applying for.';
    }

    // Validate CV File Upload
    $cvFilename = null;
    if (empty($_FILES['cv_file']['name'])) {
        $errors[] = 'Please upload your CV / resume (PDF or Word document).';
    } else {
        $uploadResult = handle_file_upload($_FILES['cv_file'], CV_UPLOADS_PATH, ALLOWED_CV_EXT, MAX_CV_SIZE);
        if (!$uploadResult['success']) {
            $errors[] = $uploadResult['error'];
        } else {
            $cvFilename = $uploadResult['filename'];
        }
    }

    if (empty($errors) && $cvFilename) {
        try {
            $stmt = $db->prepare("INSERT INTO applications (
                job_id, full_name, email, phone, portfolio_url, cv_path, cover_note, status, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");

            $stmt->execute([
                $formData['job_id'],
                $formData['full_name'],
                $formData['email'],
                $formData['phone'],
                $formData['portfolio_url'],
                $cvFilename,
                $formData['cover_note']
            ]);

            set_flash('success', 'Your application has been submitted successfully! Our recruiting team reviews every portfolio carefully and will contact you if there is a mutual match.');
            header('Location: ' . url('careers.php#apply'));
            exit;
        } catch (Exception $e) {
            $errors[] = 'Application submission failed: ' . $e->getMessage();
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Careers Hero Section -->
<section class="py-24 bg-primary-dark border-b border-[#222222] relative overflow-hidden">
    <div class="absolute left-1/3 top-0 w-96 h-96 bg-accent/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-4xl">
            <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-mono uppercase tracking-wider text-accent bg-accent/10 border border-accent/20 mb-6">
                CAREERS AT NOVA
            </span>
            <h1 class="text-4xl sm:text-6xl md:text-7xl font-heading font-black text-white tracking-tight leading-[1.08] mb-8">
                Do the best work of your career with people who care deeply.
            </h1>
            <p class="text-xl sm:text-2xl text-offwhite/70 font-light leading-relaxed max-w-3xl">
                We are an intentional team of craftspeople, designers, and engineers. We work on high-stakes digital projects with autonomy, generous compensation, and zero bureaucracy.
            </p>
        </div>
    </div>
</section>

<!-- Company Culture & Working at NOVA (PRD 4.7) -->
<section class="py-24 bg-[#141414]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-20">
            <span class="font-mono text-xs text-accent uppercase tracking-widest block mb-3">OUR CULTURE</span>
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-heading font-extrabold text-white tracking-tight">
                Why Build Your Future With NOVA?
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="p-8 rounded-2xl bg-primary-dark border border-[#2B2B2B]">
                <div class="w-12 h-12 rounded-xl bg-accent/10 border border-accent/25 flex items-center justify-center text-accent mb-6">
                    <i data-lucide="globe" class="w-6 h-6"></i>
                </div>
                <h3 class="text-xl font-heading font-bold text-white mb-3">Remote / Hybrid Flexibility</h3>
                <p class="text-sm text-offwhite/70 font-light leading-relaxed">
                    Work from anywhere in the world or join our collaborative studio hubs in Jakarta. We prioritize output, not arbitrary desk hours.
                </p>
            </div>

            <div class="p-8 rounded-2xl bg-primary-dark border border-[#2B2B2B]">
                <div class="w-12 h-12 rounded-xl bg-accent/10 border border-accent/25 flex items-center justify-center text-accent mb-6">
                    <i data-lucide="award" class="w-6 h-6"></i>
                </div>
                <h3 class="text-xl font-heading font-bold text-white mb-3">Top-Tier Compensation</h3>
                <p class="text-sm text-offwhite/70 font-light leading-relaxed">
                    Competitive global salaries, annual profit sharing, performance bonuses, and comprehensive health coverage for you and your family.
                </p>
            </div>

            <div class="p-8 rounded-2xl bg-primary-dark border border-[#2B2B2B]">
                <div class="w-12 h-12 rounded-xl bg-accent/10 border border-accent/25 flex items-center justify-center text-accent mb-6">
                    <i data-lucide="laptop" class="w-6 h-6"></i>
                </div>
                <h3 class="text-xl font-heading font-bold text-white mb-3">Latest Hardware &amp; Tools</h3>
                <p class="text-sm text-offwhite/70 font-light leading-relaxed">
                    High-end Apple MacBook Pro, 4K displays, ergonomic stipends, and subscriptions to Figma, Adobe Creative Cloud, and AI tools.
                </p>
            </div>

            <div class="p-8 rounded-2xl bg-primary-dark border border-[#2B2B2B]">
                <div class="w-12 h-12 rounded-xl bg-accent/10 border border-accent/25 flex items-center justify-center text-accent mb-6">
                    <i data-lucide="book-marked" class="w-6 h-6"></i>
                </div>
                <h3 class="text-xl font-heading font-bold text-white mb-3">Learning Stipend</h3>
                <p class="text-sm text-offwhite/70 font-light leading-relaxed">
                    $1,500 annual personal budget for international design conferences, masterclasses, books, and professional skill enhancement.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Open Positions Listing (PRD 4.7) -->
<section class="py-24 bg-primary-dark border-t border-[#222222]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16">
            <div>
                <span class="font-mono text-xs text-accent uppercase tracking-widest block mb-3">VACANCIES</span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-heading font-extrabold text-white tracking-tight">
                    Current Open Positions (<?= count($jobs) ?>)
                </h2>
            </div>
            <p class="mt-4 md:mt-0 text-offwhite/60 max-w-sm text-sm">
                Don't see your specific specialty? We are always keen to meet extraordinary individuals. Submit an open application below.
            </p>
        </div>

        <div class="space-y-6">
            <?php foreach ($jobs as $job): 
                $resp = array_filter(array_map('trim', explode("\n", $job['responsibilities'] ?? '')));
                $reqs = array_filter(array_map('trim', explode("\n", $job['requirements'] ?? '')));
            ?>
            <div class="p-8 rounded-3xl bg-[#171717] border border-[#2B2B2B] hover:border-accent/50 transition-all duration-300">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-6 border-b border-[#262626]">
                    <div>
                        <div class="flex items-center gap-3 text-xs font-mono text-muted-gray mb-2">
                            <span class="text-accent uppercase font-bold"><?= e($job['department']) ?></span>
                            <span>&bull;</span>
                            <span><?= e($job['work_type']) ?></span>
                            <span>&bull;</span>
                            <span><?= e($job['location']) ?></span>
                        </div>
                        <h3 class="text-2xl sm:text-3xl font-heading font-bold text-white">
                            <?= e($job['title']) ?>
                        </h3>
                    </div>
                    <a href="#apply" onclick="document.getElementById('job_id').value = '<?= $job['id'] ?>'" class="btn-primary text-xs self-start lg:self-center">
                        APPLY FOR THIS ROLE
                    </a>
                </div>

                <div class="pt-6 grid grid-cols-1 md:grid-cols-2 gap-8 text-sm text-offwhite/80 font-light">
                    <!-- Responsibilities -->
                    <div>
                        <h4 class="font-mono text-xs uppercase tracking-wider text-white font-bold mb-3">Key Responsibilities</h4>
                        <ul class="space-y-2 text-offwhite/70">
                            <?php foreach ($resp as $r): ?>
                            <li class="flex items-start gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-accent flex-shrink-0 mt-1.5"></span>
                                <span><?= e(ltrim($r, '- ')) ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Requirements -->
                    <div>
                        <h4 class="font-mono text-xs uppercase tracking-wider text-white font-bold mb-3">Requirements &amp; Experience</h4>
                        <ul class="space-y-2 text-offwhite/70">
                            <?php foreach ($reqs as $rq): ?>
                            <li class="flex items-start gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 flex-shrink-0 mt-1.5"></span>
                                <span><?= e(ltrim($rq, '- ')) ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Application Form (PRD 4.7) -->
<section id="apply" class="py-24 bg-[#141414] border-t border-[#222222]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="font-mono text-xs text-accent uppercase tracking-widest block mb-3">SUBMIT APPLICATION</span>
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-heading font-extrabold text-white tracking-tight">
                Join the Team.
            </h2>
            <p class="mt-4 text-offwhite/70 font-light max-w-xl mx-auto text-sm">
                Fill out the application below and upload your CV / portfolio. We respect your time and respond to all qualified applicants.
            </p>
        </div>

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

        <div class="p-8 sm:p-12 rounded-3xl bg-primary-dark border border-[#2B2B2B]">
            <form action="<?= url('careers.php#apply') ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                <?= csrf_field() ?>

                <!-- Full Name & Email -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="full_name" class="block text-xs font-mono uppercase tracking-wider text-offwhite/80 mb-2">
                            Full Name <span class="text-accent">*</span>
                        </label>
                        <input type="text" id="full_name" name="full_name" required value="<?= e($formData['full_name']) ?>" placeholder="e.g. Jordan Lee" class="form-input">
                    </div>
                    <div>
                        <label for="email" class="block text-xs font-mono uppercase tracking-wider text-offwhite/80 mb-2">
                            Email Address <span class="text-accent">*</span>
                        </label>
                        <input type="email" id="email" name="email" required value="<?= e($formData['email']) ?>" placeholder="jordan@example.com" class="form-input">
                    </div>
                </div>

                <!-- Phone & Position -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="phone" class="block text-xs font-mono uppercase tracking-wider text-offwhite/80 mb-2">
                            Phone / WhatsApp <span class="text-offwhite/40 text-[10px]">(Optional)</span>
                        </label>
                        <input type="text" id="phone" name="phone" value="<?= e($formData['phone']) ?>" placeholder="+62 812-xxxx-xxxx" class="form-input">
                    </div>
                    <div>
                        <label for="job_id" class="block text-xs font-mono uppercase tracking-wider text-offwhite/80 mb-2">
                            Position Applied For <span class="text-accent">*</span>
                        </label>
                        <select id="job_id" name="job_id" required class="form-input">
                            <option value="" disabled <?= empty($formData['job_id']) ? 'selected' : '' ?>>-- Select role --</option>
                            <?php foreach ($jobs as $j): ?>
                                <option value="<?= $j['id'] ?>" <?= ($formData['job_id'] == $j['id']) ? 'selected' : '' ?>>
                                    <?= e($j['title']) ?> (<?= e($j['department']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Portfolio / LinkedIn URL -->
                <div>
                    <label for="portfolio_url" class="block text-xs font-mono uppercase tracking-wider text-offwhite/80 mb-2">
                        Portfolio / Website / LinkedIn URL
                    </label>
                    <input type="url" id="portfolio_url" name="portfolio_url" value="<?= e($formData['portfolio_url']) ?>" placeholder="https://dribbble.com/yourhandle or https://yourportfolio.com" class="form-input">
                </div>

                <!-- CV File Upload -->
                <div>
                    <label for="cv_file" class="block text-xs font-mono uppercase tracking-wider text-offwhite/80 mb-2">
                        Upload CV / Resume <span class="text-accent">*</span> <span class="text-offwhite/40 text-[10px]">(PDF, DOC, DOCX - Max 10MB)</span>
                    </label>
                    <input type="file" id="cv_file" name="cv_file" required accept=".pdf,.doc,.docx" class="form-input file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-mono file:bg-charcoal file:text-white hover:file:bg-accent hover:file:text-black">
                </div>

                <!-- Cover Note -->
                <div>
                    <label for="cover_note" class="block text-xs font-mono uppercase tracking-wider text-offwhite/80 mb-2">
                        Introduction / Why NOVA? <span class="text-offwhite/40 text-[10px]">(Optional)</span>
                    </label>
                    <textarea id="cover_note" name="cover_note" rows="4" placeholder="Tell us about yourself, your favorite project, and what draws you to our work..." class="form-input"><?= e($formData['cover_note']) ?></textarea>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" class="btn-primary w-full py-4 text-sm group">
                        <span>SUBMIT APPLICATION</span>
                        <i data-lucide="check" class="w-4 h-4 ml-2 group-hover:scale-125 transition-transform"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

