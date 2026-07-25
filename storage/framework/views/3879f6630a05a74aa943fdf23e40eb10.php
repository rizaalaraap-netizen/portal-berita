<?php $__env->startSection('title', 'Kelola Berita | Portal Berita Admin'); ?>
<?php $__env->startSection('page_title', 'Kelola Berita'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
                <form id="postFilterForm" class="d-flex flex-wrap gap-2" method="GET">
                    <input class="form-control" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari judul..." data-realtime-search style="max-width: 220px;">
                    <select class="form-select" name="status">
                        <option value="">Semua status</option>
                        <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($status); ?>" <?php if(request('status') === $status): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <option value="trashed" <?php if(request('status') === 'trashed'): echo 'selected'; endif; ?>>Terhapus</option>
                    </select>
                    <select class="form-select" name="category_id">
                        <option value="">Semua kategori</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>" <?php if(request('category_id') == $category->id): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <select class="form-select" name="author_id">
                        <option value="">Semua penulis</option>
                        <?php $__currentLoopData = $authors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $author): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($author->id); ?>" <?php if(request('author_id') == $author->id): echo 'selected'; endif; ?>><?php echo e($author->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <select class="form-select" name="sort">
                        <option value="latest" <?php if(request('sort', 'latest') === 'latest'): echo 'selected'; endif; ?>>Terbaru</option>
                        <option value="oldest" <?php if(request('sort') === 'oldest'): echo 'selected'; endif; ?>>Terlama</option>
                    </select>
                    <button class="btn btn-outline-danger">Filter</button>
                </form>
                <div class="d-flex gap-2">
                    <a class="btn btn-outline-secondary" href="<?php echo e(route('admin.posts.trash')); ?>">Trash</a>
                    <a class="btn btn-danger" href="<?php echo e(route('admin.posts.create')); ?>">Tambah Berita</a>
                </div>
            </div>

            <?php if (! ($isTrash)): ?>
                <form id="bulkForm" action="<?php echo e(route('admin.posts.bulk')); ?>" method="POST" class="d-flex flex-wrap gap-2 align-items-center mb-3">
                    <?php echo csrf_field(); ?>
                    <select class="form-select" name="action" style="max-width: 180px;" required>
                        <option value="">Bulk Action</option>
                        <?php if(auth()->user()->canPublishPosts()): ?>
                            <option value="publish">Publish</option>
                        <?php endif; ?>
                        <?php if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin()): ?>
                            <option value="archive">Archive</option>
                        <?php endif; ?>
                        <option value="draft">Draft</option>
                        <option value="delete">Soft Delete</option>
                    </select>
                    <button class="btn btn-outline-danger" onclick="return confirm('Proses berita yang dipilih?')">Terapkan</button>
                </form>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <?php if (! ($isTrash)): ?>
                                <th style="width: 40px;"><input type="checkbox" class="form-check-input" data-check-all></th>
                            <?php endif; ?>
                            <th>Thumbnail</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Penulis</th>
                            <th>Status</th>
                            <th>Publish</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <?php if (! ($isTrash)): ?>
                                    <td><input type="checkbox" class="form-check-input" name="post_ids[]" value="<?php echo e($post->id); ?>" form="bulkForm" data-row-check></td>
                                <?php endif; ?>
                                <td><img src="<?php echo e($post->thumbnail_url); ?>" alt="<?php echo e($post->title); ?>" style="width: 88px; height: 58px; object-fit: cover; border-radius: .5rem;"></td>
                                <td><?php echo e($post->title); ?></td>
                                <td><?php echo e($post->category->name); ?></td>
                                <td><?php echo e($post->author->name); ?></td>
                                <td><span class="badge text-bg-<?php echo e($post->status_badge); ?>"><?php echo e($post->status_label); ?></span></td>
                                <td><?php echo e($post->published_at?->format('d/m/Y H:i') ?? '-'); ?></td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-secondary" href="<?php echo e(route('admin.posts.preview', $post)); ?>" target="_blank">Preview</a>

                                    <?php if($post->trashed()): ?>
                                        <form action="<?php echo e(route('admin.posts.restore', $post)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button class="btn btn-sm btn-outline-success">Restore</button>
                                        </form>
                                        <form action="<?php echo e(route('admin.posts.force-delete', $post)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus permanen berita ini?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-sm btn-outline-danger">Force Delete</button>
                                        </form>
                                    <?php else: ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $post)): ?>
                                            <a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('admin.posts.edit', $post)); ?>">Edit</a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('submitReview', $post)): ?>
                                            <form action="<?php echo e(route('admin.posts.submit-review', $post)); ?>" method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <button class="btn btn-sm btn-outline-warning">Kirim Review</button>
                                            </form>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approve', $post)): ?>
                                            <form action="<?php echo e(route('admin.posts.approve', $post)); ?>" method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <button class="btn btn-sm btn-outline-success">Approve</button>
                                            </form>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('returnToDraft', $post)): ?>
                                            <form action="<?php echo e(route('admin.posts.return-draft', $post)); ?>" method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <button class="btn btn-sm btn-outline-secondary">Draft</button>
                                            </form>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('publish', $post)): ?>
                                            <form action="<?php echo e(route('admin.posts.publish', $post)); ?>" method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <button class="btn btn-sm btn-outline-success">Publish</button>
                                            </form>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('unpublish', $post)): ?>
                                            <form action="<?php echo e(route('admin.posts.unpublish', $post)); ?>" method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <button class="btn btn-sm btn-outline-secondary">Unpublish</button>
                                            </form>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('archive', $post)): ?>
                                            <form action="<?php echo e(route('admin.posts.archive', $post)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Arsipkan berita ini?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <button class="btn btn-sm btn-outline-danger">Archive</button>
                                            </form>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('restoreArchived', $post)): ?>
                                            <form action="<?php echo e(route('admin.posts.restore-archived', $post)); ?>" method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <button class="btn btn-sm btn-outline-secondary">Restore Arsip</button>
                                            </form>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $post)): ?>
                                            <form action="<?php echo e(route('admin.posts.destroy', $post)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Pindahkan berita ini ke tempat sampah?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                            </form>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <?php echo e($posts->links()); ?>

        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            const realtimeSearch = document.querySelector('[data-realtime-search]');
            const postFilterForm = document.getElementById('postFilterForm');
            let searchTimer;

            if (realtimeSearch && postFilterForm) {
                realtimeSearch.addEventListener('input', () => {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(() => postFilterForm.requestSubmit(), 450);
                });
            }

            document.querySelector('[data-check-all]')?.addEventListener('change', (event) => {
                document.querySelectorAll('[data-row-check]').forEach((checkbox) => {
                    checkbox.checked = event.target.checked;
                });
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\website-berita\resources\views/admin/posts/index.blade.php ENDPATH**/ ?>