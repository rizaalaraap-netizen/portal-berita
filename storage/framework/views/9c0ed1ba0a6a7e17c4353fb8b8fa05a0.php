<?php $__env->startSection('title', 'Media Manager | Portal Berita Admin'); ?>
<?php $__env->startSection('page_title', 'Media Manager'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row g-4">
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Upload Gambar</h5>
                    <form action="<?php echo e(route('admin.media.store')); ?>" method="POST" enctype="multipart/form-data" data-media-upload-form>
                        <?php echo csrf_field(); ?>
                        <label class="border rounded-3 bg-light d-block text-center p-4 mb-3" data-drop-zone>
                            <input
                                id="mediaUpload"
                                name="images[]"
                                type="file"
                                accept="image/jpeg,image/png,image/webp,image/svg+xml"
                                class="d-none <?php $__errorArgs = ['images'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> <?php $__errorArgs = ['images.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                multiple
                                required>
                            <strong>Drag & drop gambar</strong>
                            <span class="d-block text-muted small mt-1">atau klik untuk memilih beberapa file.</span>
                        </label>
                        <?php $__errorArgs = ['images'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <?php $__errorArgs = ['images.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                        <div class="row g-2 mb-3" data-media-upload-preview></div>

                        <div class="progress d-none mb-3" style="height: 8px;" data-media-progress-wrap>
                            <div class="progress-bar bg-danger" style="width: 0%;" data-media-progress></div>
                        </div>

                        <p class="text-muted small mb-3">
                            Format: <?php echo e(implode(', ', $allowedMimes)); ?>. Maksimal <?php echo e(number_format($maxUploadKb / 1024, 1)); ?> MB per file.
                        </p>
                        <button class="btn btn-danger w-100">Upload</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form class="row g-2 align-items-end mb-4" method="GET">
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Search</label>
                            <input class="form-control" name="search" value="<?php echo e($filters['search'] ?? ''); ?>" placeholder="Nama, alt, caption...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Extension</label>
                            <select class="form-select" name="extension">
                                <option value="">Semua</option>
                                <?php $__currentLoopData = $extensions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $extension): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($extension); ?>" <?php if(($filters['extension'] ?? '') === $extension): echo 'selected'; endif; ?>><?php echo e(strtoupper($extension)); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Status</label>
                            <select class="form-select" name="status">
                                <option value="active" <?php if(($filters['status'] ?? 'active') === 'active'): echo 'selected'; endif; ?>>Aktif</option>
                                <option value="trash" <?php if(($filters['status'] ?? '') === 'trash'): echo 'selected'; endif; ?>>Trash</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Sort</label>
                            <select class="form-select" name="sort">
                                <option value="latest" <?php if(($filters['sort'] ?? 'latest') === 'latest'): echo 'selected'; endif; ?>>Terbaru</option>
                                <option value="oldest" <?php if(($filters['sort'] ?? '') === 'oldest'): echo 'selected'; endif; ?>>Terlama</option>
                                <option value="name" <?php if(($filters['sort'] ?? '') === 'name'): echo 'selected'; endif; ?>>Nama</option>
                                <option value="size" <?php if(($filters['sort'] ?? '') === 'size'): echo 'selected'; endif; ?>>Ukuran</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">View</label>
                            <select class="form-select" name="view">
                                <option value="grid" <?php if(($filters['view'] ?? 'grid') === 'grid'): echo 'selected'; endif; ?>>Grid</option>
                                <option value="list" <?php if(($filters['view'] ?? '') === 'list'): echo 'selected'; endif; ?>>List</option>
                            </select>
                        </div>
                        <div class="col-12 d-flex gap-2">
                            <button class="btn btn-outline-danger">Terapkan</button>
                            <a class="btn btn-outline-secondary" href="<?php echo e(route('admin.media.index')); ?>">Reset</a>
                        </div>
                    </form>

                    <?php if(($filters['view'] ?? 'grid') === 'list'): ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Preview</th>
                                        <th>Nama</th>
                                        <th>Dimensi</th>
                                        <th>Ukuran</th>
                                        <th>Pemilik</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $mediaItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><img src="<?php echo e($media->thumbnail_url); ?>" alt="<?php echo e($media->alt ?: $media->original_name); ?>" class="rounded-2" style="width:72px;height:56px;object-fit:cover;"></td>
                                            <td>
                                                <strong><?php echo e($media->original_name); ?></strong>
                                                <div class="small text-muted"><?php echo e($media->path); ?></div>
                                            </td>
                                            <td class="small text-muted"><?php echo e($media->width && $media->height ? $media->width.' x '.$media->height : '-'); ?></td>
                                            <td class="small text-muted"><?php echo e($media->human_size); ?></td>
                                            <td class="small text-muted"><?php echo e($media->user?->name ?? '-'); ?></td>
                                            <td><?php echo $__env->make('admin.media.partials.actions', ['media' => $media, 'filters' => $filters], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr><td colspan="6" class="text-muted">Belum ada media.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="row g-3">
                            <?php $__empty_1 = true; $__currentLoopData = $mediaItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="col-md-6 col-xxl-4">
                                    <div class="border rounded-3 p-2 h-100 bg-white">
                                        <a href="<?php echo e($media->url); ?>" target="_blank">
                                            <img src="<?php echo e($media->thumbnail_url); ?>" alt="<?php echo e($media->alt ?: $media->original_name); ?>" class="w-100 rounded-3 mb-2" style="height: 160px; object-fit: cover;">
                                        </a>
                                        <p class="small fw-semibold mb-1 text-truncate" title="<?php echo e($media->original_name); ?>"><?php echo e($media->original_name); ?></p>
                                        <p class="small text-muted mb-2">
                                            <?php echo e(strtoupper($media->extension)); ?> | <?php echo e($media->human_size); ?>

                                            <?php if($media->width && $media->height): ?>
                                                | <?php echo e($media->width); ?> x <?php echo e($media->height); ?>

                                            <?php endif; ?>
                                        </p>
                                        <div class="input-group input-group-sm mb-2">
                                            <input class="form-control" value="<?php echo e($media->url); ?>" readonly>
                                            <button class="btn btn-outline-secondary" type="button" data-copy-url="<?php echo e($media->url); ?>">Copy</button>
                                        </div>
                                        <form action="<?php echo e(route('admin.media.update', $media)); ?>" method="POST" class="border-top pt-2 mt-2">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PUT'); ?>
                                            <input class="form-control form-control-sm mb-2" name="original_name" value="<?php echo e(old('original_name', $media->original_name)); ?>" placeholder="Nama file">
                                            <input class="form-control form-control-sm mb-2" name="alt" value="<?php echo e(old('alt', $media->alt)); ?>" placeholder="Alt text">
                                            <textarea class="form-control form-control-sm mb-2" name="caption" rows="2" placeholder="Caption"><?php echo e(old('caption', $media->caption)); ?></textarea>
                                            <button class="btn btn-sm btn-outline-primary w-100">Simpan Metadata</button>
                                        </form>
                                        <?php echo $__env->make('admin.media.partials.actions', ['media' => $media, 'filters' => $filters], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="col-12">
                                    <p class="text-muted mb-0">Belum ada media.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="mt-4">
                        <?php echo e($mediaItems->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        document.querySelectorAll('[data-copy-url]').forEach((button) => {
            button.addEventListener('click', async () => {
                await navigator.clipboard.writeText(button.dataset.copyUrl);
                button.textContent = 'Copied';
                setTimeout(() => button.textContent = 'Copy', 1200);
            });
        });

        const mediaInput = document.querySelector('#mediaUpload');
        const dropZone = document.querySelector('[data-drop-zone]');
        const mediaPreview = document.querySelector('[data-media-upload-preview]');
        const mediaForm = document.querySelector('[data-media-upload-form]');
        const progressWrap = document.querySelector('[data-media-progress-wrap]');
        const progress = document.querySelector('[data-media-progress]');

        const renderUploadPreview = () => {
            mediaPreview.innerHTML = '';

            Array.from(mediaInput.files || []).slice(0, 8).forEach((file) => {
                const item = document.createElement('div');
                item.className = 'col-4';
                item.innerHTML = `<img class="w-100 rounded-2" style="height:72px;object-fit:cover;" alt="${file.name}"><span class="small text-muted text-truncate d-block">${file.name}</span>`;
                item.querySelector('img').src = URL.createObjectURL(file);
                mediaPreview.appendChild(item);
            });
        };

        mediaInput?.addEventListener('change', renderUploadPreview);

        dropZone?.addEventListener('dragover', (event) => {
            event.preventDefault();
            dropZone.classList.add('border-danger');
        });

        dropZone?.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-danger');
        });

        dropZone?.addEventListener('drop', (event) => {
            event.preventDefault();
            dropZone.classList.remove('border-danger');
            if (mediaInput) {
                mediaInput.files = event.dataTransfer.files;
                renderUploadPreview();
            }
        });

        mediaForm?.addEventListener('submit', () => {
            progressWrap?.classList.remove('d-none');
            if (progress) progress.style.width = '100%';
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\website-berita\resources\views/admin/media/index.blade.php ENDPATH**/ ?>