<?php $__env->startSection('title', $post->title.' | Portal Berita'); ?>

<?php $__env->startSection('meta'); ?>
    <meta name="description" content="<?php echo e($post->seo_description); ?>">
    <link rel="canonical" href="<?php echo e(route('posts.show', $post)); ?>">

    <meta property="og:type" content="article">
    <meta property="og:site_name" content="PortalBerita">
    <meta property="og:title" content="<?php echo e($post->seo_title); ?>">
    <meta property="og:description" content="<?php echo e($post->seo_description); ?>">
    <meta property="og:image" content="<?php echo e($post->og_image_url); ?>">
    <meta property="og:url" content="<?php echo e(route('posts.show', $post)); ?>">
    <meta property="article:published_time" content="<?php echo e($post->published_at?->toIso8601String()); ?>">
    <meta property="article:modified_time" content="<?php echo e($post->updated_at->toIso8601String()); ?>">
    <meta property="article:author" content="<?php echo e($post->author->name); ?>">
    <meta property="article:section" content="<?php echo e($post->category->name); ?>">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo e($post->seo_title); ?>">
    <meta name="twitter:description" content="<?php echo e($post->seo_description); ?>">
    <meta name="twitter:image" content="<?php echo e($post->og_image_url); ?>">

    <script type="application/ld+json">
        <?php echo json_encode($newsArticleSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>

    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="container article-page">
        <article class="article-content">
            <span class="category"><?php echo e($post->category->name); ?></span>
            <h1><?php echo e($post->title); ?></h1>
            <div class="article-info">
                <span><i class="fa-regular fa-pen-to-square"></i> <?php echo e($post->author->name); ?></span>
                <span><i class="fa-regular fa-calendar"></i> <?php echo e($post->published_at?->translatedFormat('d F Y')); ?></span>
                <span><i class="fa-regular fa-eye"></i> <?php echo e(number_format($post->views, 0, ',', '.')); ?> dibaca</span>
            </div>

            <img src="<?php echo e($post->thumbnail_url); ?>" alt="<?php echo e($post->title); ?>" class="article-image">
            <?php echo $post->content; ?>


            <div class="share">
                <h3>Bagikan Artikel</h3>
                <a href="#">Facebook</a>
                <a href="#">Instagram</a>
                <a href="#">WhatsApp</a>
                <a href="#">Twitter</a>
            </div>
        </article>

        <aside class="sidebar">
            <h3>Berita Populer</h3>
            <?php $__currentLoopData = $popular; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a class="sidebar-news" href="<?php echo e(route('posts.show', $item)); ?>">
                    <img src="<?php echo e($item->thumbnail_url); ?>" alt="<?php echo e($item->title); ?>">
                    <span><?php echo e($item->title); ?></span>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </aside>
    </section>

    <section class="container">
        <h2 class="section-title">Artikel Terkait</h2>
        <div class="news-grid">
            <?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('frontend.partials.card', ['post' => $item], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\website-berita\resources\views/frontend/show.blade.php ENDPATH**/ ?>