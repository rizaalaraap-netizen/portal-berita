<?php $__env->startSection('title', 'Portal Berita - Berita Terkini Indonesia'); ?>

<?php
    $readingTime = fn ($post) => max(1, ceil(str_word_count(strip_tags($post->content)) / 200));
?>

<?php $__env->startSection('content'); ?>
    <section class="breaking-news pro-breaking">
        <div class="container breaking-wrapper">
            <strong class="breaking-title">Breaking News</strong>
            <div class="breaking-marquee" aria-label="Berita terbaru">
                <div class="breaking-track">
                    <?php $__empty_1 = true; $__currentLoopData = $breaking; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <a href="<?php echo e(route('posts.show', $post)); ?>"><?php echo e($post->title); ?></a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <span>Belum ada berita terbaru.</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php if($headline): ?>
        <section class="portal-hero">
            <div class="container portal-hero-grid">
                <article class="hero-main">
                    <img src="<?php echo e($headline->thumbnail_url); ?>" alt="<?php echo e($headline->title); ?>">
                    <div class="hero-main-overlay">
                        <span class="category"><?php echo e($headline->category->name); ?></span>
                        <h1><?php echo e($headline->title); ?></h1>
                        <p><?php echo e($headline->excerpt ?: str($headline->content)->stripTags()->limit(170)); ?></p>
                        <div class="news-meta">
                            <span><i class="fa-regular fa-calendar"></i> <?php echo e($headline->published_at?->translatedFormat('d F Y')); ?></span>
                            <span><i class="fa-regular fa-clock"></i> <?php echo e($readingTime($headline)); ?> menit baca</span>
                        </div>
                        <a href="<?php echo e(route('posts.show', $headline)); ?>" class="read-btn">Baca Selengkapnya</a>
                    </div>
                </article>

                <aside class="hero-side">
                    <h2>Trending News</h2>
                    <?php $__currentLoopData = $trending; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a class="rank-card" href="<?php echo e(route('posts.show', $post)); ?>">
                            <strong><?php echo e($loop->iteration); ?></strong>
                            <img src="<?php echo e($post->thumbnail_url); ?>" alt="<?php echo e($post->title); ?>">
                            <span><?php echo e($post->title); ?></span>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </aside>
            </div>
        </section>
    <?php endif; ?>

    <section class="container portal-layout">
        <div class="portal-main">
            <div class="section-heading">
                <h2>Berita Terbaru</h2>
                <a href="<?php echo e(route('search')); ?>">Lihat Semua</a>
            </div>
            <div class="modern-news-grid">
                <?php $__currentLoopData = $latest; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <article class="modern-card">
                        <a href="<?php echo e(route('posts.show', $post)); ?>">
                            <img src="<?php echo e($post->thumbnail_url); ?>" alt="<?php echo e($post->title); ?>">
                        </a>
                        <div class="modern-card-body">
                            <span><?php echo e($post->category->name); ?></span>
                            <h3><a href="<?php echo e(route('posts.show', $post)); ?>"><?php echo e($post->title); ?></a></h3>
                            <p><?php echo e($post->excerpt ?: str($post->content)->stripTags()->limit(110)); ?></p>
                            <div class="news-meta">
                                <small><?php echo e($post->published_at?->translatedFormat('d F Y')); ?></small>
                                <small><?php echo e($readingTime($post)); ?> menit baca</small>
                            </div>
                        </div>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="section-heading mt-section">
                <h2>Berita Populer</h2>
            </div>
            <div class="popular-stack">
                <?php $__currentLoopData = $popular; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a class="popular-card pro-popular-card" href="<?php echo e(route('posts.show', $post)); ?>">
                        <img src="<?php echo e($post->thumbnail_url); ?>" alt="<?php echo e($post->title); ?>">
                        <div>
                            <span><?php echo e($post->category->name); ?></span>
                            <h3><?php echo e($post->title); ?></h3>
                            <p><?php echo e(number_format($post->views, 0, ',', '.')); ?> dibaca | <?php echo e($post->published_at?->translatedFormat('d F Y')); ?></p>
                        </div>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <?php $__currentLoopData = $categorySections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="category-news-section">
                    <div class="section-heading">
                        <h2><?php echo e($category->name); ?></h2>
                        <a href="<?php echo e(route('category.show', $category)); ?>">Lihat Kategori</a>
                    </div>
                    <div class="category-news-grid">
                        <?php $__currentLoopData = $category->publishedPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <article class="category-mini-card">
                                <a href="<?php echo e(route('posts.show', $post)); ?>">
                                    <img src="<?php echo e($post->thumbnail_url); ?>" alt="<?php echo e($post->title); ?>">
                                    <h3><?php echo e($post->title); ?></h3>
                                    <small><?php echo e($post->published_at?->translatedFormat('d F Y')); ?></small>
                                </a>
                            </article>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <aside class="portal-sidebar">
            <div class="sidebar-widget">
                <h3>Editor's Pick</h3>
                <?php $__currentLoopData = $editorsPick; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a class="sidebar-pick" href="<?php echo e(route('posts.show', $post)); ?>">
                        <img src="<?php echo e($post->thumbnail_url); ?>" alt="<?php echo e($post->title); ?>">
                        <span><?php echo e($post->title); ?></span>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="sidebar-widget">
                <h3>Most Read</h3>
                <ol class="most-read-list">
                    <?php $__currentLoopData = $popular; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><a href="<?php echo e(route('posts.show', $post)); ?>"><?php echo e($post->title); ?></a></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ol>
            </div>

            <div class="sidebar-widget">
                <h3>Kategori Populer</h3>
                <div class="tag-cloud">
                    <?php $__currentLoopData = $categories->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('category.show', $category)); ?>"><?php echo e($category->name); ?></a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <div class="sidebar-widget">
                <h3>Tag Populer</h3>
                <div class="tag-cloud">
                    <?php $__currentLoopData = $categories->take(12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('category.show', $category)); ?>">#<?php echo e(str($category->name)->slug()); ?></a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </aside>
    </section>

    <section class="newsletter pro-newsletter">
        <div class="container">
            <h2>Berlangganan Newsletter</h2>
            <p>Dapatkan berita utama dan analisis pilihan langsung ke email Anda.</p>
            <form>
                <input type="email" placeholder="Masukkan email" aria-label="Email newsletter" required>
                <button type="submit">Berlangganan</button>
            </form>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\website-berita\resources\views/frontend/home.blade.php ENDPATH**/ ?>