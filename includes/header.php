<?php
/**
 * NOVA Creative Digital Agency
 * Global Public Header Component
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helpers.php';

$pageTitle = isset($pageTitle) ? $pageTitle . ' — ' . APP_NAME : get_setting('meta_title', APP_NAME . ' — Creative Digital Agency');
$pageDesc = isset($pageDesc) ? $pageDesc : get_setting('meta_description', APP_TAGLINE);
$currentPage = basename($_SERVER['PHP_SELF'] ?? 'index.php');
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="<?= e($pageDesc) ?>">

    <!-- Open Graph / Meta -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= e($pageTitle) ?>">
    <meta property="og:description" content="<?= e($pageDesc) ?>">
    <meta property="og:image" content="<?= asset('images/project-aether.svg') ?>">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;600;700;800;900&family=Space+Mono:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-dark': '#111111',
                        'surface-dark': '#181818',
                        'charcoal': '#262626',
                        'card-border': '#333333',
                        'offwhite': '#F5F3EF',
                        'accent': '#FF5A1F',
                        'accent-hover': '#E04B14',
                        'muted-gray': '#777777',
                    },
                    fontFamily: {
                        heading: ['"Space Grotesk"', 'sans-serif'],
                        body: ['Inter', 'sans-serif'],
                        mono: ['"Space Mono"', 'monospace'],
                    }
                }
            }
        }
    </script>

    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Core Stylesheet -->
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body class="bg-primary-dark text-offwhite min-h-screen flex flex-col font-body selection:bg-accent selection:text-black">

    <!-- Top Sticky Header -->
    <header id="site-header" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 py-5 bg-primary-dark/80 backdrop-blur-md border-b border-[#222222]/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <!-- Agency Brand Logo -->
                <a href="<?= url('index.php') ?>" class="group flex items-center gap-1 focus:outline-none focus:ring-2 focus:ring-accent rounded-sm">
                    <span class="font-heading font-black text-2xl tracking-tighter text-offwhite group-hover:text-white transition-colors">NOVA</span>
                    <span class="w-2 h-2 rounded-full bg-accent inline-block transform translate-y-[-4px] group-hover:scale-125 transition-transform"></span>
                    <span class="font-mono text-[10px] text-muted-gray uppercase ml-0.5">®</span>
                </a>

                <!-- Desktop Navigation Menu -->
                <nav class="hidden md:flex items-center space-x-8 text-sm font-medium tracking-wide">
                    <a href="<?= url('index.php') ?>" class="transition-colors pb-1 border-b-2 <?= ($currentPage === 'index.php') ? 'text-accent border-accent font-semibold' : 'text-offwhite/80 hover:text-white border-transparent' ?>">Home</a>
                    <a href="<?= url('about.php') ?>" class="transition-colors pb-1 border-b-2 <?= ($currentPage === 'about.php') ? 'text-accent border-accent font-semibold' : 'text-offwhite/80 hover:text-white border-transparent' ?>">About</a>
                    <a href="<?= url('services.php') ?>" class="transition-colors pb-1 border-b-2 <?= ($currentPage === 'services.php') ? 'text-accent border-accent font-semibold' : 'text-offwhite/80 hover:text-white border-transparent' ?>">Services</a>
                    <a href="<?= url('work.php') ?>" class="transition-colors pb-1 border-b-2 <?= (in_array($currentPage, ['work.php', 'project-detail.php'])) ? 'text-accent border-accent font-semibold' : 'text-offwhite/80 hover:text-white border-transparent' ?>">Work</a>
                    <a href="<?= url('insights.php') ?>" class="transition-colors pb-1 border-b-2 <?= (in_array($currentPage, ['insights.php', 'article-detail.php'])) ? 'text-accent border-accent font-semibold' : 'text-offwhite/80 hover:text-white border-transparent' ?>">Insights</a>
                    <a href="<?= url('contact.php') ?>" class="transition-colors pb-1 border-b-2 <?= ($currentPage === 'contact.php') ? 'text-accent border-accent font-semibold' : 'text-offwhite/80 hover:text-white border-transparent' ?>">Contact</a>
                </nav>

                <!-- Primary CTA Button (Let's Talk) -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="<?= url('contact.php') ?>" class="btn-primary flex items-center gap-2 group !py-2.5 !px-5 text-xs">
                        <span>LET'S TALK</span>
                        <i data-lucide="arrow-up-right" class="w-4 h-4 transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"></i>
                    </a>
                </div>

                <!-- Mobile Hamburger Button -->
                <button id="mobile-menu-btn" class="md:hidden text-offwhite p-2 rounded-md hover:bg-charcoal transition-colors focus:outline-none focus:ring-2 focus:ring-accent" aria-label="Toggle navigation menu">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Slide-Down Menu Overlay -->
        <div id="mobile-menu" class="hidden md:hidden fixed inset-0 top-[73px] bg-primary-dark/95 backdrop-blur-xl border-t border-charcoal z-40 p-6 flex flex-col justify-between overflow-y-auto">
            <div class="flex flex-col space-y-5 text-xl font-heading font-semibold">
                <a href="<?= url('index.php') ?>" class="<?= ($currentPage === 'index.php') ? 'text-accent' : 'text-offwhite hover:text-accent' ?>">Home</a>
                <a href="<?= url('about.php') ?>" class="<?= ($currentPage === 'about.php') ? 'text-accent' : 'text-offwhite hover:text-accent' ?>">About</a>
                <a href="<?= url('services.php') ?>" class="<?= ($currentPage === 'services.php') ? 'text-accent' : 'text-offwhite hover:text-accent' ?>">Services</a>
                <a href="<?= url('work.php') ?>" class="<?= (in_array($currentPage, ['work.php', 'project-detail.php'])) ? 'text-accent' : 'text-offwhite hover:text-accent' ?>">Work</a>
                <a href="<?= url('insights.php') ?>" class="<?= (in_array($currentPage, ['insights.php', 'article-detail.php'])) ? 'text-accent' : 'text-offwhite hover:text-accent' ?>">Insights</a>
                <a href="<?= url('careers.php') ?>" class="<?= ($currentPage === 'careers.php') ? 'text-accent' : 'text-offwhite hover:text-accent' ?>">Careers</a>
                <a href="<?= url('contact.php') ?>" class="<?= ($currentPage === 'contact.php') ? 'text-accent' : 'text-offwhite hover:text-accent' ?>">Contact</a>
            </div>
            <div class="pt-8 border-t border-charcoal">
                <a href="<?= url('contact.php') ?>" class="btn-primary w-full text-center py-3">LET'S TALK</a>
                <div class="mt-6 text-xs font-mono text-muted-gray text-center">
                    <?= e(get_setting('contact_email', 'hello@nova.agency')) ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Wrapper with Top Margin for Fixed Navbar -->
    <main class="flex-grow pt-[85px]">
        <?php require __DIR__ . '/notifications.php'; ?>

