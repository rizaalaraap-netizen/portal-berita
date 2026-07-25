<?php $__env->startSection('title', 'Edit Kategori - PortalBerita'); ?>
<?php $__env->startSection('page_title', 'Edit Kategori'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="<?php echo e(route('admin.categories.update', $category)); ?>" method="POST">
                <?php echo method_field('PUT'); ?>
                <?php echo $__env->make('admin.categories._form', ['button' => 'Perbarui Kategori'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\website-berita\resources\views/admin/categories/edit.blade.php ENDPATH**/ ?>