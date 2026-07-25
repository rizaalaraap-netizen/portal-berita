<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Portal Berita Admin</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js']); ?>
    <style>
        body { min-height: 100vh; background: linear-gradient(135deg, #151923, #d60000); display: grid; place-items: center; }
        .login-card { width: min(92vw, 430px); border: 0; border-radius: 1.25rem; box-shadow: 0 24px 80px rgba(0,0,0,.24); }
    </style>
</head>
<body>
    <div class="card login-card">
        <div class="card-body p-4 p-md-5">
            <img src="<?php echo e(asset('images/logo.svg')); ?>" alt="PortalBerita" class="mb-4" style="max-width: 220px;">
            <h1 class="h3 mb-2">Login Admin</h1>
            <p class="text-muted mb-4">Masuk untuk mengelola berita dan kategori.</p>

            <form method="POST" action="<?php echo e(route('login.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input id="email" name="email" type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('email')); ?>" required autofocus>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Password</label>
                    <input id="password" name="password" type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-check mb-4">
                    <input id="remember" name="remember" type="checkbox" class="form-check-input">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>
                <button class="btn btn-danger w-100 py-2" type="submit">Login</button>
            </form>
            <p class="text-muted small mt-4 mb-0">Default seeder: admin@portalberita.test / password</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\website-berita\resources\views/auth/login.blade.php ENDPATH**/ ?>