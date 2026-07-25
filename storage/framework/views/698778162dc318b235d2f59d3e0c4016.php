<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard | Portal Berita Admin'); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js']); ?>
    <style>
        body { background: #f5f6f8; }
        .sidebar { min-height: 100vh; background: #171923; }
        .sidebar a { color: #d8dee9; text-decoration: none; display: block; padding: .75rem 1rem; border-radius: .5rem; }
        .sidebar a:hover, .sidebar a.active { background: #d60000; color: #fff; }
        .card-stat { border: 0; border-radius: 1rem; box-shadow: 0 8px 24px rgba(0,0,0,.08); }
        .thumbnail-preview { max-width: 180px; border-radius: .75rem; }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <aside class="col-lg-2 sidebar p-3">
                <h4 class="text-white mb-4">PortalBerita</h4>
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => request()->routeIs('admin.dashboard')]); ?>">Dashboard</a>
                <?php if(auth()->user()?->hasAnyRole([\App\Models\User::ROLE_SUPER_ADMIN, \App\Models\User::ROLE_ADMIN])): ?>
                    <a href="<?php echo e(route('admin.contact-messages.index')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => request()->routeIs('admin.contact-messages.*')]); ?>">Pesan Masuk</a>
                    <a href="<?php echo e(route('admin.activity-logs.index')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => request()->routeIs('admin.activity-logs.*')]); ?>">Activity Log</a>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewAny', \App\Models\Post::class)): ?>
                    <a href="<?php echo e(route('admin.posts.index')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => request()->routeIs('admin.posts.*')]); ?>">Berita</a>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewAny', \App\Models\Media::class)): ?>
                    <a href="<?php echo e(route('admin.media.index')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => request()->routeIs('admin.media.*')]); ?>">Media</a>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewAny', \App\Models\Category::class)): ?>
                    <a href="<?php echo e(route('admin.categories.index')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => request()->routeIs('admin.categories.*')]); ?>">Kategori</a>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewAny', \App\Models\User::class)): ?>
                    <a href="<?php echo e(route('admin.users.index')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => request()->routeIs('admin.users.*')]); ?>">Admin/User</a>
                <?php endif; ?>
                <a href="<?php echo e(route('home')); ?>" target="_blank">Lihat Website</a>
                <form action="<?php echo e(route('logout')); ?>" method="POST" class="mt-4">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-outline-light w-100" type="submit">Logout</button>
                </form>
            </aside>
            <main class="col-lg-10 p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h1 class="h3 mb-1"><?php echo $__env->yieldContent('page_title', 'Dashboard'); ?></h1>
                        <p class="text-muted mb-0">Halo, <?php echo e(auth()->user()->name ?? 'Admin'); ?></p>
                    </div>
                </div>

                <?php if(session('success')): ?>
                    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>

    <script>
        document.querySelectorAll('[data-preview-input]').forEach((input) => {
            input.addEventListener('change', (event) => {
                const preview = document.querySelector(input.dataset.previewInput);
                const file = event.target.files?.[0];
                if (preview && file) preview.src = URL.createObjectURL(file);
            });
        });
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\website-berita\resources\views/layouts/admin.blade.php ENDPATH**/ ?>