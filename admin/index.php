<?php
/**
 * Admin Dashboard Overview (admin/index.php)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helpers.php';

$pageTitle = 'Dashboard Overview';

$db = getDB();

// Fetch statistics
$totalInquiries = (int)$db->query("SELECT COUNT(*) FROM inquiries")->fetchColumn();
$newInquiries = (int)$db->query("SELECT COUNT(*) FROM inquiries WHERE status = 'New'")->fetchColumn();

$totalProjects = (int)$db->query("SELECT COUNT(*) FROM projects")->fetchColumn();
$totalServices = (int)$db->query("SELECT COUNT(*) FROM services")->fetchColumn();
$totalArticles = (int)$db->query("SELECT COUNT(*) FROM articles")->fetchColumn();
$totalApplications = (int)$db->query("SELECT COUNT(*) FROM applications")->fetchColumn();
$pendingApplications = (int)$db->query("SELECT COUNT(*) FROM applications WHERE status = 'Pending'")->fetchColumn();

// Fetch 5 most recent inquiries
$inqStmt = $db->query("SELECT * FROM inquiries ORDER BY created_at DESC LIMIT 5");
$recentInquiries = $inqStmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<!-- KPI Statistics Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <!-- Card 1: Inquiries -->
    <div class="p-6 rounded-2xl bg-card-bg border border-border-dark relative overflow-hidden">
        <div class="flex items-center justify-between mb-4">
            <span class="font-mono text-xs text-muted-gray uppercase">TOTAL INQUIRIES</span>
            <div class="w-9 h-9 rounded-xl bg-accent/15 text-accent flex items-center justify-center">
                <i data-lucide="inbox" class="w-4 h-4"></i>
            </div>
        </div>
        <div class="flex items-baseline justify-between">
            <span class="font-heading text-3xl font-bold text-white"><?= $totalInquiries ?></span>
            <?php if ($newInquiries > 0): ?>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-bold bg-accent/20 text-accent border border-accent/30">
                <?= $newInquiries ?> NEW
            </span>
            <?php endif; ?>
        </div>
        <a href="<?= url('admin/inquiries.php') ?>" class="mt-4 text-xs font-mono text-accent hover:underline flex items-center gap-1">
            <span>Manage inquiries</span>
            <i data-lucide="arrow-right" class="w-3 h-3"></i>
        </a>
    </div>

    <!-- Card 2: Portfolio Projects -->
    <div class="p-6 rounded-2xl bg-card-bg border border-border-dark relative overflow-hidden">
        <div class="flex items-center justify-between mb-4">
            <span class="font-mono text-xs text-muted-gray uppercase">PORTFOLIO WORK</span>
            <div class="w-9 h-9 rounded-xl bg-blue-500/15 text-blue-400 flex items-center justify-center">
                <i data-lucide="briefcase" class="w-4 h-4"></i>
            </div>
        </div>
        <div class="flex items-baseline justify-between">
            <span class="font-heading text-3xl font-bold text-white"><?= $totalProjects ?></span>
            <span class="text-xs text-muted-gray font-mono">PUBLISHED</span>
        </div>
        <a href="<?= url('admin/projects.php') ?>" class="mt-4 text-xs font-mono text-blue-400 hover:underline flex items-center gap-1">
            <span>View all projects</span>
            <i data-lucide="arrow-right" class="w-3 h-3"></i>
        </a>
    </div>

    <!-- Card 3: Articles -->
    <div class="p-6 rounded-2xl bg-card-bg border border-border-dark relative overflow-hidden">
        <div class="flex items-center justify-between mb-4">
            <span class="font-mono text-xs text-muted-gray uppercase">INSIGHT ARTICLES</span>
            <div class="w-9 h-9 rounded-xl bg-emerald-500/15 text-emerald-400 flex items-center justify-center">
                <i data-lucide="file-text" class="w-4 h-4"></i>
            </div>
        </div>
        <div class="flex items-baseline justify-between">
            <span class="font-heading text-3xl font-bold text-white"><?= $totalArticles ?></span>
            <span class="text-xs text-muted-gray font-mono"><?= $totalServices ?> SERVICES</span>
        </div>
        <a href="<?= url('admin/articles.php') ?>" class="mt-4 text-xs font-mono text-emerald-400 hover:underline flex items-center gap-1">
            <span>Manage articles</span>
            <i data-lucide="arrow-right" class="w-3 h-3"></i>
        </a>
    </div>

    <!-- Card 4: Job Applications -->
    <div class="p-6 rounded-2xl bg-card-bg border border-border-dark relative overflow-hidden">
        <div class="flex items-center justify-between mb-4">
            <span class="font-mono text-xs text-muted-gray uppercase">JOB APPLICANTS</span>
            <div class="w-9 h-9 rounded-xl bg-purple-500/15 text-purple-400 flex items-center justify-center">
                <i data-lucide="users" class="w-4 h-4"></i>
            </div>
        </div>
        <div class="flex items-baseline justify-between">
            <span class="font-heading text-3xl font-bold text-white"><?= $totalApplications ?></span>
            <?php if ($pendingApplications > 0): ?>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-bold bg-purple-500/20 text-purple-300 border border-purple-500/30">
                <?= $pendingApplications ?> PENDING
            </span>
            <?php endif; ?>
        </div>
        <a href="<?= url('admin/applications.php') ?>" class="mt-4 text-xs font-mono text-purple-400 hover:underline flex items-center gap-1">
            <span>Review candidates</span>
            <i data-lucide="arrow-right" class="w-3 h-3"></i>
        </a>
    </div>
</div>

<!-- Quick Action Strip -->
<div class="p-6 rounded-2xl bg-card-bg border border-border-dark mb-10">
    <span class="text-xs font-mono text-muted-gray uppercase tracking-wider block mb-4">QUICK MANAGEMENT ACTIONS</span>
    <div class="flex flex-wrap items-center gap-4">
        <a href="<?= url('admin/project-edit.php') ?>" class="btn-primary !py-2.5 !px-5 text-xs flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Add New Project</span>
        </a>
        <a href="<?= url('admin/article-edit.php') ?>" class="btn-secondary !py-2.5 !px-5 text-xs flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Write New Article</span>
        </a>
        <a href="<?= url('admin/service-edit.php') ?>" class="btn-secondary !py-2.5 !px-5 text-xs flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Add Service</span>
        </a>
        <a href="<?= url('admin/job-edit.php') ?>" class="btn-secondary !py-2.5 !px-5 text-xs flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Post Job Vacancy</span>
        </a>
    </div>
</div>

<!-- Recent Inquiries Table -->
<div class="rounded-2xl bg-card-bg border border-border-dark overflow-hidden mb-10">
    <div class="p-6 border-b border-border-dark flex items-center justify-between">
        <div>
            <h2 class="font-heading font-bold text-white text-lg">Incoming Project Inquiries</h2>
            <p class="text-xs text-muted-gray mt-0.5">Most recent prospective clients awaiting agency response.</p>
        </div>
        <a href="<?= url('admin/inquiries.php') ?>" class="font-mono text-xs text-accent hover:underline flex items-center gap-1">
            <span>View All (<?= $totalInquiries ?>)</span>
            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-[#141414] text-xs font-mono text-muted-gray uppercase border-b border-border-dark">
                <tr>
                    <th class="px-6 py-4">Client</th>
                    <th class="px-6 py-4">Service</th>
                    <th class="px-6 py-4">Budget / Timeline</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-dark/60 font-light text-offwhite/80">
                <?php if (empty($recentInquiries)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-muted-gray font-mono text-xs">
                        No inquiries submitted yet.
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($recentInquiries as $inq): 
                        $statusClass = ($inq['status'] === 'New') ? 'bg-accent/20 text-accent border-accent/40' :
                                       (($inq['status'] === 'In Review') ? 'bg-amber-500/20 text-amber-300 border-amber-500/30' :
                                       (($inq['status'] === 'Contacted') ? 'bg-blue-500/20 text-blue-300 border-blue-500/30' :
                                       'bg-emerald-500/20 text-emerald-300 border-emerald-500/30'));
                    ?>
                    <tr class="hover:bg-charcoal/40 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-heading font-bold text-white"><?= e($inq['full_name']) ?></div>
                            <div class="text-xs text-muted-gray font-mono"><?= e($inq['email']) ?></div>
                            <?php if (!empty($inq['company_name'])): ?>
                                <span class="text-[11px] text-offwhite/60"><?= e($inq['company_name']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded bg-[#202020] text-xs font-mono text-offwhite/90">
                                <?= e($inq['service_needed']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs font-mono">
                            <div><?= e($inq['estimated_budget'] ?: 'Not specified') ?></div>
                            <div class="text-muted-gray"><?= e($inq['project_timeline'] ?: 'Flexible') ?></div>
                        </td>
                        <td class="px-6 py-4 text-xs font-mono text-muted-gray">
                            <?= date('M d, Y H:i', strtotime($inq['created_at'])) ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-mono font-bold border <?= $statusClass ?>">
                                <?= e($inq['status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="<?= url('admin/inquiry-detail.php?id=' . $inq['id']) ?>" class="px-3 py-1.5 rounded bg-charcoal hover:bg-accent hover:text-black text-xs font-mono transition-colors inline-flex items-center gap-1">
                                <span>Review</span>
                                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

