<?php
/**
 * Admin Layout Header & Sidebar Component
 */
require_once __DIR__ . '/auth.php';

$currentAdmin = current_admin();
$adminPage = basename($_SERVER['PHP_SELF'] ?? 'index.php');

// Count new inquiries for notification badge
$db = getDB();
$newInquiriesCount = (int)$db->query("SELECT COUNT(*) FROM inquiries WHERE status = 'New'")->fetchColumn();
$pendingAppsCount = (int)$db->query("SELECT COUNT(*) FROM applications WHERE status = 'Pending'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' — ' : '' ?>NOVA Admin Console</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700;800;900&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-dark': '#0E0E0E',
                        'sidebar-dark': '#141414',
                        'charcoal': '#1F1F1F',
                        'card-bg': '#181818',
                        'accent': '#FF5A1F',
                        'accent-hover': '#E04B14',
                        'offwhite': '#F5F3EF',
                        'border-dark': '#2B2B2B',
                        'muted-gray': '#777777'
                    },
                    fontFamily: {
                        heading: ['"Space Grotesk"', 'sans-serif'],
                        body: ['Inter', 'sans-serif'],
                        mono: ['"Space Mono"', 'monospace']
                    }
                }
            }
        }
    </script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body class="bg-primary-dark text-offwhite min-h-screen flex flex-col font-body selection:bg-accent selection:text-black">

    <div class="flex flex-1 min-h-screen">
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-sidebar-dark border-r border-border-dark flex-shrink-0 flex flex-col justify-between hidden md:flex">
            <div>
                <!-- Brand Title -->
                <div class="h-20 flex items-center px-6 border-b border-border-dark">
                    <a href="<?= url('admin/index.php') ?>" class="flex items-center gap-1 group">
                        <span class="font-heading font-black text-2xl tracking-tighter text-white">NOVA</span>
                        <span class="w-2 h-2 rounded-full bg-accent inline-block transform translate-y-[-4px]"></span>
                        <span class="font-mono text-[10px] text-muted-gray uppercase ml-1">ADMIN</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <nav class="p-4 space-y-1.5 text-sm">
                    <a href="<?= url('admin/index.php') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-colors <?= ($adminPage === 'index.php') ? 'bg-accent text-black font-semibold' : 'text-offwhite/75 hover:bg-charcoal hover:text-white' ?>">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="<?= url('admin/inquiries.php') ?>" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-colors <?= (in_array($adminPage, ['inquiries.php', 'inquiry-detail.php'])) ? 'bg-accent text-black font-semibold' : 'text-offwhite/75 hover:bg-charcoal hover:text-white' ?>">
                        <div class="flex items-center gap-3">
                            <i data-lucide="inbox" class="w-4 h-4"></i>
                            <span>Inquiries</span>
                        </div>
                        <?php if ($newInquiriesCount > 0): ?>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold <?= ($adminPage === 'inquiries.php') ? 'bg-black text-white' : 'bg-accent text-black' ?>">
                            <?= $newInquiriesCount ?>
                        </span>
                        <?php endif; ?>
                    </a>

                    <a href="<?= url('admin/projects.php') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-colors <?= (in_array($adminPage, ['projects.php', 'project-edit.php'])) ? 'bg-accent text-black font-semibold' : 'text-offwhite/75 hover:bg-charcoal hover:text-white' ?>">
                        <i data-lucide="briefcase" class="w-4 h-4"></i>
                        <span>Portfolio / Work</span>
                    </a>

                    <a href="<?= url('admin/services.php') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-colors <?= (in_array($adminPage, ['services.php', 'service-edit.php'])) ? 'bg-accent text-black font-semibold' : 'text-offwhite/75 hover:bg-charcoal hover:text-white' ?>">
                        <i data-lucide="sparkles" class="w-4 h-4"></i>
                        <span>Services</span>
                    </a>

                    <a href="<?= url('admin/articles.php') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-colors <?= (in_array($adminPage, ['articles.php', 'article-edit.php'])) ? 'bg-accent text-black font-semibold' : 'text-offwhite/75 hover:bg-charcoal hover:text-white' ?>">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                        <span>Insights / Articles</span>
                    </a>

                    <a href="<?= url('admin/testimonials.php') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-colors <?= ($adminPage === 'testimonials.php') ? 'bg-accent text-black font-semibold' : 'text-offwhite/75 hover:bg-charcoal hover:text-white' ?>">
                        <i data-lucide="message-square" class="w-4 h-4"></i>
                        <span>Testimonials</span>
                    </a>

                    <div class="pt-4 pb-2">
                        <span class="px-3.5 text-[10px] font-mono text-muted-gray uppercase tracking-wider block">RECRUITMENT</span>
                    </div>

                    <a href="<?= url('admin/jobs.php') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-colors <?= (in_array($adminPage, ['jobs.php', 'job-edit.php'])) ? 'bg-accent text-black font-semibold' : 'text-offwhite/75 hover:bg-charcoal hover:text-white' ?>">
                        <i data-lucide="award" class="w-4 h-4"></i>
                        <span>Job Openings</span>
                    </a>

                    <a href="<?= url('admin/applications.php') ?>" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-colors <?= ($adminPage === 'applications.php') ? 'bg-accent text-black font-semibold' : 'text-offwhite/75 hover:bg-charcoal hover:text-white' ?>">
                        <div class="flex items-center gap-3">
                            <i data-lucide="users" class="w-4 h-4"></i>
                            <span>Applications</span>
                        </div>
                        <?php if ($pendingAppsCount > 0): ?>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold bg-blue-500 text-white">
                            <?= $pendingAppsCount ?>
                        </span>
                        <?php endif; ?>
                    </a>

                    <div class="pt-4 pb-2">
                        <span class="px-3.5 text-[10px] font-mono text-muted-gray uppercase tracking-wider block">SYSTEM</span>
                    </div>

                    <a href="<?= url('admin/settings.php') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-colors <?= ($adminPage === 'settings.php') ? 'bg-accent text-black font-semibold' : 'text-offwhite/75 hover:bg-charcoal hover:text-white' ?>">
                        <i data-lucide="settings" class="w-4 h-4"></i>
                        <span>Settings &amp; SEO</span>
                    </a>
                </nav>
            </div>

            <!-- Sidebar User & Site Link Footer -->
            <div class="p-4 border-t border-border-dark space-y-3">
                <a href="<?= url('index.php') ?>" target="_blank" class="flex items-center justify-between px-3 py-2 rounded-lg bg-charcoal hover:bg-[#2A2A2A] text-xs font-mono text-offwhite/80 transition-colors">
                    <span class="flex items-center gap-2">
                        <i data-lucide="external-link" class="w-3.5 h-3.5 text-accent"></i>
                        <span>Live Website</span>
                    </span>
                    <i data-lucide="arrow-up-right" class="w-3 h-3 text-muted-gray"></i>
                </a>

                <div class="flex items-center justify-between px-2 pt-2">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full bg-accent/20 border border-accent/40 flex items-center justify-center font-mono text-xs font-bold text-accent">
                            <?= substr($currentAdmin['username'] ?? 'A', 0, 1) ?>
                        </div>
                        <span class="text-xs font-medium text-white truncate max-w-[90px]"><?= e($currentAdmin['full_name']) ?></span>
                    </div>
                    <a href="<?= url('admin/logout.php') ?>" title="Logout" class="text-muted-gray hover:text-rose-400 p-1 transition-colors">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Admin Topbar -->
            <header class="h-20 bg-sidebar-dark border-b border-border-dark px-6 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <!-- Mobile Hamburger -->
                    <button id="admin-mobile-toggle" class="md:hidden text-offwhite p-1 rounded hover:bg-charcoal">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                    <h1 class="text-lg font-heading font-bold text-white"><?= isset($pageTitle) ? e($pageTitle) : 'Admin Console' ?></h1>
                </div>

                <div class="flex items-center gap-4">
                    <span class="text-xs font-mono text-muted-gray hidden sm:inline">DATABASE: <strong class="text-white"><?= strtoupper(Database::getDriverUsed()) ?></strong></span>
                    <a href="<?= url('admin/logout.php') ?>" class="px-3 py-1.5 rounded-lg bg-charcoal hover:bg-rose-950/60 hover:text-rose-300 border border-border-dark text-xs font-mono transition-colors flex items-center gap-1.5">
                        <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </header>

            <!-- Main Page Scrollable Body -->
            <main class="flex-1 overflow-y-auto p-6 lg:p-8 bg-primary-dark">
                <?php require __DIR__ . '/../../includes/notifications.php'; ?>

