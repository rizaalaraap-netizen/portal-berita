<?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="d-flex gap-3 py-3 border-bottom">
        <img src="<?php echo e($post->thumbnail_url); ?>" alt="<?php echo e($post->title); ?>" style="width: 78px; height: 54px; object-fit: cover; border-radius: .5rem;">
        <div class="min-width-0">
            <a class="fw-semibold text-dark text-decoration-none" href="<?php echo e(route('admin.posts.edit', $post)); ?>">
                <?php echo e($post->title); ?>

            </a>
            <div class="small text-muted">
                <?php echo e($post->category->name); ?>

                <span class="mx-1">|</span>
                <?php echo e($post->created_at->format('d/m/Y')); ?>

                <?php if($showViews ?? false): ?>
                    <span class="mx-1">|</span>
                    <?php echo e(number_format($post->views, 0, ',', '.')); ?> view
                <?php endif; ?>
                <?php if($showPeriodViews ?? false): ?>
                    <span class="mx-1">|</span>
                    <?php echo e(number_format($post->period_views ?? 0, 0, ',', '.')); ?> view periode
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <p class="text-muted mb-0"><?php echo e($emptyText); ?></p>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\website-berita\resources\views/admin/partials/post-list.blade.php ENDPATH**/ ?>