

<?php $__env->startSection('title', 'Terminal POS'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('pos-terminal', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-4270301043-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.pos', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\bodega-app\resources\views/pos/index.blade.php ENDPATH**/ ?>