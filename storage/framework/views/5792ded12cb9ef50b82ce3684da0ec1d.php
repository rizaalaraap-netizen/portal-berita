<?php $__env->startSection('title', 'Search - PortalBerita'); ?>

<?php $__env->startSection('content'); ?>
    <section class="container page-hero">
        <h1 class="page-title">Hasil Pencarian</h1>
        <p class="page-description">Menampilkan hasil untuk: <?php echo e($query ?: 'semua berita'); ?></p>
    </section>

    <section class="container">
        <div class="news-grid">
            <?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php echo $__env->make('frontend.partials.card', ['post' => $post], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p>Tidak ada berita yang cocok.</p>
            <?php endif; ?>
        </div>
        <div style="margin-top:24px"><?php echo e($posts->links()); ?></div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\website-berita\resources\views/frontend/search.blade.php ENDPATH**/ ?>