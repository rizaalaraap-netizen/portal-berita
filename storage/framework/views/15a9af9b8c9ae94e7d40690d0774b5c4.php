<?php $__env->startSection('title', 'Pesan Masuk | Portal Berita Admin'); ?>
<?php $__env->startSection('page_title', 'Pesan Masuk'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
                <form class="d-flex flex-wrap gap-2" method="GET">
                    <input class="form-control" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nama, email, subjek..." style="max-width: 280px;">
                    <select class="form-select" name="status" style="max-width: 190px;">
                        <option value="">Semua status</option>
                        <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($status); ?>" <?php if(request('status') === $status): echo 'selected'; endif; ?>><?php echo e($status); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button class="btn btn-outline-danger">Filter</button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Subjek</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($message->nama); ?></td>
                                <td><?php echo e($message->email); ?></td>
                                <td><?php echo e($message->subjek); ?></td>
                                <td>
                                    <span class="badge text-bg-<?php echo e($message->status === \App\Models\ContactMessage::STATUS_UNREAD ? 'danger' : 'success'); ?>">
                                        <?php echo e($message->status); ?>

                                    </span>
                                </td>
                                <td><?php echo e($message->created_at->format('d/m/Y H:i')); ?></td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('admin.contact-messages.show', $message)); ?>">Detail</a>
                                    <?php if($message->status === \App\Models\ContactMessage::STATUS_UNREAD): ?>
                                        <form class="d-inline" action="<?php echo e(route('admin.contact-messages.read', $message)); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button class="btn btn-sm btn-outline-success">Sudah Dibaca</button>
                                        </form>
                                    <?php endif; ?>
                                    <form class="d-inline" action="<?php echo e(route('admin.contact-messages.destroy', $message)); ?>" method="POST" onsubmit="return confirm('Hapus pesan ini?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada pesan masuk.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php echo e($messages->links()); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\website-berita\resources\views/admin/contact-messages/index.blade.php ENDPATH**/ ?>