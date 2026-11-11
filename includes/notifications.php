<?php
/**
 * Flash Notification Alerts
 */
$flashes = get_flashes();
if (!empty($flashes)):
?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <?php foreach ($flashes as $flash): 
        $type = $flash['type'] ?? 'info';
        $msg = $flash['message'] ?? '';
        $bgColor = ($type === 'success') ? 'bg-emerald-950/80 border-emerald-500/50 text-emerald-200' :
                   (($type === 'error') ? 'bg-rose-950/80 border-rose-500/50 text-rose-200' : 
                   'bg-amber-950/80 border-amber-500/50 text-amber-200');
        $iconName = ($type === 'success') ? 'check-circle-2' : (($type === 'error') ? 'alert-triangle' : 'info');
    ?>
    <div class="flash-alert flex items-center justify-between p-4 mb-4 rounded-xl border <?= $bgColor ?> backdrop-blur-md transition-all duration-300">
        <div class="flex items-center gap-3">
            <i data-lucide="<?= $iconName ?>" class="w-5 h-5 flex-shrink-0"></i>
            <span class="text-sm font-medium"><?= e($msg) ?></span>
        </div>
        <button type="button" class="alert-close text-offwhite/60 hover:text-white p-1 rounded transition-colors" aria-label="Close alert">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

