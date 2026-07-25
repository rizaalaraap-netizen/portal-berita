<?php $__env->startSection('title', 'Kelola Kategori | Portal Berita Admin'); ?>
<?php $__env->startSection('page_title', 'Kelola Kategori'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
                <form class="d-flex gap-2" method="GET">
                    <input class="form-control" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari kategori...">
                    <button class="btn btn-outline-danger">Cari</button>
                </form>
                <a class="btn btn-danger" href="<?php echo e(route('admin.categories.create')); ?>">Tambah Kategori</a>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Slug</th>
                            <th>Berita</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($category->name); ?></td>
                                <td><?php echo e($category->slug); ?></td>
                                <td><?php echo e($category->posts_count); ?></td>
                                <td><span class="badge text-bg-<?php echo e($category->is_active ? 'success' : 'secondary'); ?>"><?php echo e($category->is_active ? 'Aktif' : 'Nonaktif'); ?></span></td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('admin.categories.edit', $category)); ?>">Edit</a>
                                    <form action="<?php echo e(route('admin.categories.destroy', $category)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <?php echo e($categories->links()); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\website-berita\resources\views/admin/categories/index.blade.php ENDPATH**/ ?>