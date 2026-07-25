<article class="card">
    <img src="<?php echo e($post->thumbnail_url); ?>" alt="<?php echo e($post->title); ?>">
    <div class="card-body">
        <span><?php echo e($post->category->name); ?></span>
        <h3><?php echo e($post->title); ?></h3>
        <p><?php echo e(str($post->content)->stripTags()->limit(90)); ?></p>
        <a href="<?php echo e(route('posts.show', $post)); ?>">Baca Selengkapnya -></a>
    </div>
</article>
<?php /**PATH C:\xampp\htdocs\website-berita\resources\views/frontend/partials/card.blade.php ENDPATH**/ ?>