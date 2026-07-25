<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Portal Berita - Berita Terkini Indonesia'); ?></title>
    <?php echo $__env->yieldContent('meta'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/frontend.js']); ?>
</head>
<body>
    <div class="top-header">
        <div class="container top-wrapper">
            <div class="left-top">
                <i class="fa-solid fa-calendar-days"></i>
                <span id="tanggal"><?php echo e(now()->translatedFormat('l, d F Y')); ?></span>
            </div>
            <div class="right-top" aria-label="Media sosial">
                <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                <a href="#" aria-label="X"><i class="fa-brands fa-x-twitter"></i></a>
                <a href="#" aria-label="TikTok"><i class="fa-brands fa-tiktok"></i></a>
            </div>
        </div>
    </div>

    <header class="site-header">
        <div class="container header-content">
            <a class="brand" href="<?php echo e(route('home')); ?>" aria-label="PortalBerita">
                <img src="<?php echo e(asset('images/logo.svg')); ?>" alt="PortalBerita">
            </a>

            <form class="search-box" role="search" action="<?php echo e(route('search')); ?>">
                <input type="search" name="q" value="<?php echo e(request('q')); ?>" placeholder="Cari berita..." aria-label="Cari berita">
                <button type="submit" aria-label="Cari"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>

            <button class="live-button" type="button">
                <i class="fa-solid fa-circle"></i>
                LIVE
            </button>

            <div class="profile">
                <button id="loginBtn" class="profile-btn" type="button" aria-label="Buka menu akun">
                    <i class="fa-regular fa-user"></i>
                </button>
                <div id="profilePopup" class="profile-popup" aria-hidden="true">
                    <div class="popup-header"><i class="fa-regular fa-circle-user"></i></div>
                    <div class="popup-body">
                        <h3>Masuk ke akun Anda</h3>
                        <p>Area pengelola berita<br><a href="<?php echo e(route('login')); ?>">Login Admin</a></p>
                        <hr>
                        <a href="#">Pedoman Media Siber</a>
                        <a href="<?php echo e(route('contact')); ?>">Hubungi Kami</a>
                        <a href="#">Privacy Policy</a>
                        <a href="#">Redaksi</a>
                    </div>
                </div>
            </div>

            <button class="menu-btn" type="button" aria-label="Buka peta provinsi" aria-controls="provinceOverlay" aria-expanded="false">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        <nav class="navbar" aria-label="Navigasi utama">
            <ul>
                <li><a href="<?php echo e(route('home')); ?>">Home</a></li>
                <?php $__currentLoopData = $navCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $navCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><a href="<?php echo e(route('category.show', $navCategory)); ?>"><?php echo e($navCategory->name); ?></a></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <li><a href="<?php echo e(route('about')); ?>">Tentang</a></li>
                <li><a href="<?php echo e(route('contact')); ?>">Kontak</a></li>
            </ul>
        </nav>
    </header>

    <?php
        $indonesiaProvinces = [
            ['code' => 'AC', 'name' => 'Aceh', 'slug' => 'aceh', 'x' => 44, 'y' => 132, 'w' => 54, 'h' => 30],
            ['code' => 'SU', 'name' => 'Sumatera Utara', 'slug' => 'sumatera-utara', 'x' => 102, 'y' => 158, 'w' => 70, 'h' => 30],
            ['code' => 'SB', 'name' => 'Sumatera Barat', 'slug' => 'sumatera-barat', 'x' => 174, 'y' => 196, 'w' => 70, 'h' => 30],
            ['code' => 'RI', 'name' => 'Riau', 'slug' => 'riau', 'x' => 248, 'y' => 184, 'w' => 54, 'h' => 30],
            ['code' => 'KR', 'name' => 'Kepulauan Riau', 'slug' => 'kepulauan-riau', 'x' => 306, 'y' => 154, 'w' => 62, 'h' => 28],
            ['code' => 'JA', 'name' => 'Jambi', 'slug' => 'jambi', 'x' => 278, 'y' => 224, 'w' => 58, 'h' => 30],
            ['code' => 'BE', 'name' => 'Bengkulu', 'slug' => 'bengkulu', 'x' => 246, 'y' => 262, 'w' => 64, 'h' => 30],
            ['code' => 'SS', 'name' => 'Sumatera Selatan', 'slug' => 'sumatera-selatan', 'x' => 318, 'y' => 264, 'w' => 78, 'h' => 30],
            ['code' => 'BB', 'name' => 'Kepulauan Bangka Belitung', 'slug' => 'kepulauan-bangka-belitung', 'x' => 398, 'y' => 226, 'w' => 62, 'h' => 28],
            ['code' => 'LA', 'name' => 'Lampung', 'slug' => 'lampung', 'x' => 376, 'y' => 306, 'w' => 64, 'h' => 30],
            ['code' => 'BT', 'name' => 'Banten', 'slug' => 'banten', 'x' => 438, 'y' => 354, 'w' => 56, 'h' => 28],
            ['code' => 'JK', 'name' => 'DKI Jakarta', 'slug' => 'dki-jakarta', 'x' => 500, 'y' => 352, 'w' => 52, 'h' => 28],
            ['code' => 'JB', 'name' => 'Jawa Barat', 'slug' => 'jawa-barat', 'x' => 554, 'y' => 354, 'w' => 64, 'h' => 28],
            ['code' => 'JT', 'name' => 'Jawa Tengah', 'slug' => 'jawa-tengah', 'x' => 620, 'y' => 356, 'w' => 68, 'h' => 28],
            ['code' => 'YO', 'name' => 'DI Yogyakarta', 'slug' => 'di-yogyakarta', 'x' => 690, 'y' => 388, 'w' => 58, 'h' => 28],
            ['code' => 'JI', 'name' => 'Jawa Timur', 'slug' => 'jawa-timur', 'x' => 692, 'y' => 356, 'w' => 68, 'h' => 28],
            ['code' => 'BA', 'name' => 'Bali', 'slug' => 'bali', 'x' => 770, 'y' => 374, 'w' => 46, 'h' => 26],
            ['code' => 'NB', 'name' => 'Nusa Tenggara Barat', 'slug' => 'nusa-tenggara-barat', 'x' => 824, 'y' => 378, 'w' => 62, 'h' => 26],
            ['code' => 'NT', 'name' => 'Nusa Tenggara Timur', 'slug' => 'nusa-tenggara-timur', 'x' => 896, 'y' => 382, 'w' => 68, 'h' => 26],
            ['code' => 'KB', 'name' => 'Kalimantan Barat', 'slug' => 'kalimantan-barat', 'x' => 488, 'y' => 176, 'w' => 72, 'h' => 34],
            ['code' => 'KT', 'name' => 'Kalimantan Tengah', 'slug' => 'kalimantan-tengah', 'x' => 566, 'y' => 214, 'w' => 76, 'h' => 34],
            ['code' => 'KS', 'name' => 'Kalimantan Selatan', 'slug' => 'kalimantan-selatan', 'x' => 642, 'y' => 262, 'w' => 76, 'h' => 34],
            ['code' => 'KI', 'name' => 'Kalimantan Timur', 'slug' => 'kalimantan-timur', 'x' => 650, 'y' => 174, 'w' => 74, 'h' => 34],
            ['code' => 'KU', 'name' => 'Kalimantan Utara', 'slug' => 'kalimantan-utara', 'x' => 604, 'y' => 126, 'w' => 72, 'h' => 32],
            ['code' => 'SA', 'name' => 'Sulawesi Utara', 'slug' => 'sulawesi-utara', 'x' => 850, 'y' => 126, 'w' => 72, 'h' => 30],
            ['code' => 'GO', 'name' => 'Gorontalo', 'slug' => 'gorontalo', 'x' => 792, 'y' => 156, 'w' => 62, 'h' => 30],
            ['code' => 'ST', 'name' => 'Sulawesi Tengah', 'slug' => 'sulawesi-tengah', 'x' => 760, 'y' => 196, 'w' => 74, 'h' => 32],
            ['code' => 'SR', 'name' => 'Sulawesi Barat', 'slug' => 'sulawesi-barat', 'x' => 738, 'y' => 238, 'w' => 70, 'h' => 30],
            ['code' => 'SN', 'name' => 'Sulawesi Selatan', 'slug' => 'sulawesi-selatan', 'x' => 786, 'y' => 278, 'w' => 78, 'h' => 32],
            ['code' => 'SG', 'name' => 'Sulawesi Tenggara', 'slug' => 'sulawesi-tenggara', 'x' => 846, 'y' => 232, 'w' => 80, 'h' => 32],
            ['code' => 'MA', 'name' => 'Maluku', 'slug' => 'maluku', 'x' => 966, 'y' => 232, 'w' => 58, 'h' => 30],
            ['code' => 'MU', 'name' => 'Maluku Utara', 'slug' => 'maluku-utara', 'x' => 950, 'y' => 170, 'w' => 70, 'h' => 30],
            ['code' => 'PB', 'name' => 'Papua Barat', 'slug' => 'papua-barat', 'x' => 1054, 'y' => 208, 'w' => 70, 'h' => 32],
            ['code' => 'PD', 'name' => 'Papua Barat Daya', 'slug' => 'papua-barat-daya', 'x' => 1036, 'y' => 252, 'w' => 78, 'h' => 32],
            ['code' => 'PT', 'name' => 'Papua Tengah', 'slug' => 'papua-tengah', 'x' => 1130, 'y' => 230, 'w' => 72, 'h' => 32],
            ['code' => 'PP', 'name' => 'Papua Pegunungan', 'slug' => 'papua-pegunungan', 'x' => 1208, 'y' => 206, 'w' => 82, 'h' => 32],
            ['code' => 'PS', 'name' => 'Papua Selatan', 'slug' => 'papua-selatan', 'x' => 1192, 'y' => 276, 'w' => 76, 'h' => 32],
            ['code' => 'PA', 'name' => 'Papua', 'slug' => 'papua', 'x' => 1280, 'y' => 244, 'w' => 58, 'h' => 32],
        ];
    ?>

    <div id="provinceOverlay" class="province-overlay" aria-hidden="true">
        <div class="province-overlay__backdrop" data-close-province></div>
        <section class="province-panel" role="dialog" aria-modal="true" aria-labelledby="provinceOverlayTitle">
            <button class="province-close" type="button" aria-label="Tutup peta provinsi" data-close-province>X</button>
            <div class="province-panel__header">
                <span class="province-kicker">Jelajahi Indonesia</span>
                <h2 id="provinceOverlayTitle">Pilih Provinsi Indonesia</h2>
                <p>Temukan berita daerah melalui peta interaktif atau daftar provinsi.</p>
            </div>

            <label class="province-search" for="provinceSearch">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input id="provinceSearch" type="search" placeholder="Cari provinsi..." autocomplete="off">
            </label>

            <div class="province-message" role="status" aria-live="polite"></div>

            <div class="province-map-wrap">
                <svg class="indonesia-map" viewBox="0 0 1380 460" role="img" aria-labelledby="indonesiaMapTitle indonesiaMapDesc">
                    <title id="indonesiaMapTitle">Peta interaktif provinsi Indonesia</title>
                    <desc id="indonesiaMapDesc">Pilih salah satu provinsi untuk membuka berita daerah.</desc>
                    <path class="island-shape" d="M35 132 C94 96 198 128 250 182 C310 246 402 276 444 335 C374 348 276 314 210 268 C144 222 78 200 35 132Z"/>
                    <path class="island-shape" d="M468 132 C546 78 684 96 724 166 C774 254 700 314 604 296 C512 280 454 206 468 132Z"/>
                    <path class="island-shape" d="M432 346 C522 326 680 334 764 350 C820 362 878 362 962 380 C812 416 596 400 432 346Z"/>
                    <path class="island-shape" d="M732 136 C818 92 924 112 950 190 C898 198 894 244 930 286 C856 336 742 310 720 224 C710 184 716 158 732 136Z"/>
                    <path class="island-shape" d="M938 156 C994 124 1044 162 1026 224 C1008 284 954 294 932 250 C912 210 908 176 938 156Z"/>
                    <path class="island-shape" d="M1036 194 C1136 132 1294 150 1350 234 C1302 322 1150 332 1042 284 C1018 252 1016 218 1036 194Z"/>

                    <?php $__currentLoopData = $indonesiaProvinces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $province): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(url('/provinsi/'.$province['slug'])); ?>" class="province-map-link" data-province="<?php echo e($province['name']); ?>" data-count="0" data-url="<?php echo e(url('/provinsi/'.$province['slug'])); ?>">
                            <rect x="<?php echo e($province['x']); ?>" y="<?php echo e($province['y']); ?>" width="<?php echo e($province['w']); ?>" height="<?php echo e($province['h']); ?>" rx="12"></rect>
                            <text x="<?php echo e($province['x'] + ($province['w'] / 2)); ?>" y="<?php echo e($province['y'] + ($province['h'] / 2) + 5); ?>" text-anchor="middle"><?php echo e($province['code']); ?></text>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </svg>
                <div class="province-tooltip" aria-hidden="true"></div>
            </div>

            <div class="province-grid" aria-label="Daftar provinsi Indonesia">
                <?php $__currentLoopData = $indonesiaProvinces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $province): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(url('/provinsi/'.$province['slug'])); ?>" class="province-item" data-province="<?php echo e($province['name']); ?>" data-count="0" data-url="<?php echo e(url('/provinsi/'.$province['slug'])); ?>">
                        <span><?php echo e($province['name']); ?></span>
                        <small>0 berita</small>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </section>
    </div>

    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <footer>
        <div class="container footer-grid">
            <div>
                <img src="<?php echo e(asset('images/logo-white.svg')); ?>" alt="PortalBerita" class="footer-logo">
                <p>PortalBerita menyajikan berita terpercaya, cepat, dan akurat setiap hari.</p>
            </div>
            <div>
                <h3>Kategori</h3>
                <?php $__currentLoopData = $navCategories->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('category.show', $category)); ?>"><?php echo e($category->name); ?></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div>
                <h3>Informasi</h3>
                <a href="<?php echo e(route('about')); ?>">Tentang</a>
                <a href="<?php echo e(route('contact')); ?>">Kontak</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Pedoman Media Siber</a>
            </div>
            <div>
                <h3>Ikuti Kami</h3>
                <div class="footer-social">
                    <i class="fa-brands fa-facebook" aria-label="Facebook"></i>
                    <i class="fa-brands fa-instagram" aria-label="Instagram"></i>
                    <i class="fa-brands fa-youtube" aria-label="YouTube"></i>
                    <i class="fa-brands fa-x-twitter" aria-label="X"></i>
                    <i class="fa-brands fa-tiktok" aria-label="TikTok"></i>
                </div>
            </div>
        </div>
        <div class="copyright">&copy; 2026 PortalBerita. All Rights Reserved.</div>
    </footer>

    <script src="<?php echo e(asset('js/script.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\website-berita\resources\views/layouts/frontend.blade.php ENDPATH**/ ?>