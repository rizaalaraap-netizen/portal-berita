@extends('layouts.admin')

@section('title', 'Dashboard | Portal Berita Admin')
@section('page_title', 'Dashboard')

@section('content')
    <form class="card border-0 shadow-sm mb-4" method="GET">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label" for="period">Filter Analytics</label>
                    <select id="period" name="period" class="form-select">
                        <option value="day" @selected($period === 'day')>Hari ini</option>
                        <option value="week" @selected($period === 'week')>7 hari terakhir</option>
                        <option value="month" @selected($period === 'month')>30 hari terakhir</option>
                        <option value="custom" @selected($period === 'custom')>Custom Date</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="start_date">Dari</label>
                    <input id="start_date" class="form-control" type="date" name="start_date" value="{{ request('start_date', $startDate->toDateString()) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="end_date">Sampai</label>
                    <input id="end_date" class="form-control" type="date" name="end_date" value="{{ request('end_date', $endDate->toDateString()) }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-danger w-100">Terapkan</button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.dashboard') }}">Reset</a>
                </div>
            </div>
        </div>
    </form>

    <div class="row g-4 mb-4">
        @foreach([
            ['label' => 'Total Berita', 'value' => $stats['totalPosts']],
            ['label' => 'Published', 'value' => $stats['totalPublished']],
            ['label' => 'Draft', 'value' => $stats['totalDrafts']],
            ['label' => 'Review', 'value' => $stats['totalReviews']],
            ['label' => 'Archived', 'value' => $stats['totalArchived']],
            ['label' => 'Jumlah User', 'value' => $stats['totalUsers']],
            ['label' => 'Jumlah Kategori', 'value' => $stats['totalCategories']],
            ['label' => 'Total View', 'value' => $stats['totalViews']],
        ] as $card)
            <div class="col-6 col-xl-3">
                <div class="card card-stat h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">{{ $card['label'] }}</p>
                        <h2 class="mb-0">{{ number_format($card['value'], 0, ',', '.') }}</h2>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Berita Dipublish per Hari - 7 Hari Terakhir</h5>
                    <div style="height: 320px;">
                        <canvas id="published7DaysChart" data-labels='@json($published7Days['labels'])' data-values='@json($published7Days['values'])'></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Berita Dipublish per Hari - 30 Hari Terakhir</h5>
                    <div style="height: 320px;">
                        <canvas id="published30DaysChart" data-labels='@json($published30Days['labels'])' data-values='@json($published30Days['values'])'></canvas>
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
                    @include('admin.partials.post-list', ['posts' => $mostRead, 'emptyText' => 'Belum ada data pembaca.', 'showViews' => true])
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Author Paling Produktif</h5>
                    @forelse($topAuthors as $author)
                        <div class="d-flex justify-content-between py-3 border-bottom">
                            <span class="fw-semibold">{{ $author->name }}</span>
                            <span class="badge text-bg-danger">{{ number_format($author->published_posts_count, 0, ',', '.') }} artikel</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Belum ada author produktif pada periode ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        @foreach([
            'Trending Hari Ini' => $trendingToday,
            'Trending Minggu Ini' => $trendingWeek,
            'Trending Bulan Ini' => $trendingMonth,
        ] as $title => $posts)
            <div class="col-12 col-xl-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">{{ $title }}</h5>
                        @include('admin.partials.post-list', ['posts' => $posts, 'emptyText' => 'Belum ada data trending.', 'showPeriodViews' => true])
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Kategori dengan Berita Terbanyak</h5>
                    @forelse($topCategoriesByPosts as $category)
                        <div class="d-flex justify-content-between py-3 border-bottom">
                            <span class="fw-semibold">{{ $category->name }}</span>
                            <span class="badge text-bg-secondary">{{ number_format($category->posts_count, 0, ',', '.') }} berita</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Belum ada kategori.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Kategori dengan View Terbanyak</h5>
                    @forelse($topCategoriesByViews as $category)
                        <div class="d-flex justify-content-between py-3 border-bottom">
                            <span class="fw-semibold">{{ $category->name }}</span>
                            <span class="badge text-bg-success">{{ number_format($category->total_views, 0, ',', '.') }} view</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Belum ada data view kategori.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Recent Activity</h5>
                    @forelse($recentActivities as $activity)
                        <div class="py-3 border-bottom">
                            <div class="fw-semibold">{{ $activity->description }}</div>
                            <div class="small text-muted">
                                {{ $activity->created_at->format('d/m/Y H:i') }}
                                <span class="mx-1">|</span>
                                {{ $activity->user?->role ?? '-' }}
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Belum ada activity pada periode ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
