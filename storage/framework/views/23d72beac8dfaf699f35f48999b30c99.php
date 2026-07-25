<?php echo '<'.'?xml version="1.0" encoding="UTF-8"?'.'>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc><?php echo e(route('home')); ?></loc>
        <lastmod><?php echo e(now()->toAtomString()); ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc><?php echo e(route('about')); ?></loc>
        <lastmod><?php echo e(now()->toAtomString()); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc><?php echo e(route('contact')); ?></loc>
        <lastmod><?php echo e(now()->toAtomString()); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <url>
            <loc><?php echo e(route('category.show', $category)); ?></loc>
            <lastmod><?php echo e($category->updated_at->toAtomString()); ?></lastmod>
            <changefreq>daily</changefreq>
            <priority>0.7</priority>
        </url>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <url>
            <loc><?php echo e(route('posts.show', $post)); ?></loc>
            <lastmod><?php echo e($post->updated_at->toAtomString()); ?></lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</urlset>
<?php /**PATH C:\xampp\htdocs\website-berita\resources\views/frontend/sitemap.blade.php ENDPATH**/ ?>