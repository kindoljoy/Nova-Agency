<?php
/**
 * Admin Login Page (admin/login.php)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helpers.php';

// Redirect if already logged in
if (is_admin()) {
    header('Location: ' . url('admin/index.php'));
    exit;
}

$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $error = 'Security session expired. Please reload and try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $error = 'Both username and password are required.';
        } else {
            $db = getDB();
            $stmt = $db->prepare("SELECT * FROM admins WHERE username = ? OR email = ? LIMIT 1");
            $stmt->execute([$username, $username]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password_hash'])) {
                // Regenerate session ID to prevent session fixation attacks
                session_regenerate_id(true);

                $_SESSION['admin_id']       = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_name']     = $admin['full_name'];
                $_SESSION['admin_email']    = $admin['email'];
                $_SESSION['admin_role']     = $admin['role'];

                set_flash('success', 'Welcome back, ' . $admin['full_name'] . '. You are now logged in.');
                header('Location: ' . url('admin/index.php'));
                exit;
            } else {
                $error = 'Invalid credentials. Please verify your username and password.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — NOVA® Agency</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700;800;900&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-dark': '#111111',
                        'charcoal': '#262626',
                        'accent': '#FF5A1F',
                        'offwhite': '#F5F3EF'
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
    <!-- Lucide -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body class="bg-primary-dark text-offwhite min-h-screen flex items-center justify-center p-4 selection:bg-accent selection:text-black">

    <div class="w-full max-w-md">
        <!-- Logo & Brand Header -->
        <div class="text-center mb-8">
            <a href="<?= url('index.php') ?>" class="inline-flex items-center gap-1 group mb-3">
                <span class="font-heading font-black text-3xl tracking-tighter text-white">NOVA</span>
                <span class="w-2.5 h-2.5 rounded-full bg-accent inline-block transform translate-y-[-4px]"></span>
                <span class="font-mono text-xs text-muted-gray uppercase ml-0.5">®</span>
            </a>
            <p class="font-mono text-xs text-muted-gray uppercase tracking-widest">
                Agency Administration Console
            </p>
        </div>

        <!-- Login Card -->
        <div class="p-8 sm:p-10 rounded-3xl bg-[#171717] border border-[#2B2B2B] shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-accent to-orange-400"></div>

            <h2 class="text-2xl font-heading font-bold text-white mb-2">Sign In</h2>
            <p class="text-sm text-offwhite/60 font-light mb-6">Enter your administrative credentials to continue.</p>

            <?php if (!empty($error)): ?>
            <div class="mb-6 p-4 rounded-xl bg-rose-950/80 border border-rose-500/50 text-rose-200 text-xs flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0"></i>
                <span><?= e($error) ?></span>
            </div>
            <?php endif; ?>

            <form action="<?= url('admin/login.php') ?>" method="POST" class="space-y-5">
                <?= csrf_field() ?>

                <div>
                    <label for="username" class="block text-xs font-mono uppercase tracking-wider text-offwhite/80 mb-2">
                        Username or Email
                    </label>
                    <div class="relative">
                        <input type="text" 
                               id="username" 
                               name="username" 
                               required 
                               value="<?= e($username) ?>" 
                               placeholder="admin" 
                               class="form-input !pl-10">
                        <i data-lucide="user" class="w-4 h-4 text-muted-gray absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-mono uppercase tracking-wider text-offwhite/80 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required 
                               placeholder="••••••••" 
                               class="form-input !pl-10">
                        <i data-lucide="lock" class="w-4 h-4 text-muted-gray absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn-primary w-full py-3.5 text-xs font-mono uppercase tracking-wider group">
                        <span>AUTHENTICATE &amp; ENTER</span>
                        <i data-lucide="log-in" class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-6 border-t border-[#262626] text-center">
                <div class="p-3 rounded-lg bg-black/40 border border-[#262626] text-[11px] font-mono text-muted-gray">
                    Default Credentials: <strong class="text-white">admin</strong> / <strong class="text-white">admin123</strong>
                </div>
                <div class="mt-4">
                    <a href="<?= url('index.php') ?>" class="text-xs font-mono text-offwhite/60 hover:text-white transition-colors">
                        &larr; Return to Public Website
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>

