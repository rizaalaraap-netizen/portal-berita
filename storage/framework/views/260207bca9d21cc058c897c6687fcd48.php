<?php $__env->startSection('title', 'Dashboard | Portal Berita Admin'); ?>
<?php $__env->startSection('page_title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <form class="card border-0 shadow-sm mb-4" method="GET">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label" for="period">Filter Analytics</label>
                    <select id="period" name="period" class="form-select">
                        <option value="day" <?php if($period === 'day'): echo 'selected'; endif; ?>>Hari ini</option>
                        <option value="week" <?php if($period === 'week'): echo 'selected'; endif; ?>>7 hari terakhir</option>
                        <option value="month" <?php if($period === 'month'): echo 'selected'; endif; ?>>30 hari terakhir</option>
                        <option value="custom" <?php if($period === 'custom'): echo 'selected'; endif; ?>>Custom Date</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="start_date">Dari</label>
                    <input id="start_date" class="form-control" type="date" name="start_date" value="<?php echo e(request('start_date', $startDate->toDateString())); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="end_date">Sampai</label>
                    <input id="end_date" class="form-control" type="date" name="end_date" value="<?php echo e(request('end_date', $endDate->toDateString())); ?>">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-danger w-100">Terapkan</button>
                    <a class="btn btn-outline-secondary" href="<?php echo e(route('admin.dashboard')); ?>">Reset</a>
                </div>
            </div>
        </div>
    </form>

    <div class="row g-4 mb-4">
        <?php $__currentLoopData = [
            ['label' => 'Total Berita', 'value' => $stats['totalPosts']],
            ['label' => 'Published', 'value' => $stats['totalPublished']],
            ['label' => 'Draft', 'value' => $stats['totalDrafts']],
            ['label' => 'Review', 'value' => $stats['totalReviews']],
            ['label' => 'Archived', 'value' => $stats['totalArchived']],
            ['label' => 'Jumlah User', 'value' => $stats['totalUsers']],
            ['label' => 'Jumlah Kategori', 'value' => $stats['totalCategories']],
            ['label' => 'Total View', 'value' => $stats['totalViews']],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-6 col-xl-3">
                <div class="card card-stat h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1"><?php echo e($card['label']); ?></p>
                        <h2 class="mb-0"><?php echo e(number_format($card['value'], 0, ',', '.')); ?></h2>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Berita Dipublish per Hari - 7 Hari Terakhir</h5>
                    <div style="height: 320px;">
                        <canvas id="published7DaysChart" data-labels='<?php echo json_encode($published7Days['labels'], 15, 512) ?>' data-values='<?php echo json_encode($published7Days['values'], 15, 512) ?>'></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Berita Dipublish per Hari - 30 Hari Terakhir</h5>
                    <div style="height: 320px;">
                        <canvas id="published30DaysChart" data-labels='<?php echo json_encode($published30Days['labels'], 15, 512) ?>' data-values='<?php echo json_encode($published30Days['values'], 15, 512) ?>'></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">10 Berita Paling Banyak Dibaca</h5>
                    <?php echo $__env->make('admin.partials.post-list', ['posts' => $mostRead, 'emptyText' => 'Belum ada data pembaca.', 'showViews' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Author Paling Produktif</h5>
                    <?php $__empty_1 = true; $__currentLoopData = $topAuthors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $author): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="d-flex justify-content-between py-3 border-bottom">
                            <span class="fw-semibold"><?php echo e($author->name); ?></span>
                            <span class="badge text-bg-danger"><?php echo e(number_format($author->published_posts_count, 0, ',', '.')); ?> artikel</span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted mb-0">Belum ada author produktif pada periode ini.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <?php $__currentLoopData = [
            'Trending Hari Ini' => $trendingToday,
            'Trending Minggu Ini' => $trendingWeek,
            'Trending Bulan Ini' => $trendingMonth,
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $title => $posts): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-12 col-xl-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3"><?php echo e($title); ?></h5>
                        <?php echo $__env->make('admin.partials.post-list', ['posts' => $posts, 'emptyText' => 'Belum ada data trending.', 'showPeriodViews' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Kategori dengan Berita Terbanyak</h5>
                    <?php $__empty_1 = true; $__currentLoopData = $topCategoriesByPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="d-flex justify-content-between py-3 border-bottom">
                            <span class="fw-semibold"><?php echo e($category->name); ?></span>
                            <span class="badge text-bg-secondary"><?php echo e(number_format($category->posts_count, 0, ',', '.')); ?> berita</span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted mb-0">Belum ada kategori.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Kategori dengan View Terbanyak</h5>
                    <?php $__empty_1 = true; $__currentLoopData = $topCategoriesByViews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="d-flex justify-content-between py-3 border-bottom">
                            <span class="fw-semibold"><?php echo e($category->name); ?></span>
                            <span class="badge text-bg-success"><?php echo e(number_format($category->total_views, 0, ',', '.')); ?> view</span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted mb-0">Belum ada data view kategori.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Recent Activity</h5>
                    <?php $__empty_1 = true; $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="py-3 border-bottom">
                            <div class="fw-semibold"><?php echo e($activity->description); ?></div>
                            <div class="small text-muted">
                                <?php echo e($activity->created_at->format('d/m/Y H:i')); ?>

                                <span class="mx-1">|</span>
                                <?php echo e($activity->user?->role ?? '-'); ?>

                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted mb-0">Belum ada activity pada periode ini.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\website-berita\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>