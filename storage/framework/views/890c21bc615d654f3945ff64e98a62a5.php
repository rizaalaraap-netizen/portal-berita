<?php $__env->startSection('title', $category->name.' | Portal Berita'); ?>

<?php $__env->startSection('content'); ?>
    <section class="container page-hero">
        <h1 class="page-title"><?php echo e($category->name); ?></h1>
        <p class="page-description"><?php echo e($category->description); ?></p>
    </section>

    <section class="container">
        <div class="category-menu">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('category.show', $item)); ?>"><?php echo e($item->name); ?></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>

    <section class="container">
        <h2 class="section-title">Artikel <?php echo e($category->name); ?></h2>
        <div class="news-grid">
            <?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php echo $__env->make('frontend.partials.card', ['post' => $post], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p>Belum ada berita pada kategori ini.</p>
            <?php endif; ?>
        </div>
        <div style="margin-top:24px"><?php echo e($posts->links()); ?></div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\website-berita\resources\views/frontend/category.blade.php ENDPATH**/ ?>