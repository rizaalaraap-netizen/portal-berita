<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard | Portal Berita Admin')</title>
    @vite(['resources/js/app.js'])
    <style>
        body { background: #f5f6f8; }
        .sidebar { min-height: 100vh; background: #171923; }
        .sidebar a { color: #d8dee9; text-decoration: none; display: block; padding: .75rem 1rem; border-radius: .5rem; }
        .sidebar a:hover, .sidebar a.active { background: #d60000; color: #fff; }
        .card-stat { border: 0; border-radius: 1rem; box-shadow: 0 8px 24px rgba(0,0,0,.08); }
        .thumbnail-preview { max-width: 180px; border-radius: .75rem; }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <aside class="col-lg-2 sidebar p-3">
                <h4 class="text-white mb-4">PortalBerita</h4>
                <a href="{{ route('admin.dashboard') }}" @class(['active' => request()->routeIs('admin.dashboard')])>Dashboard</a>
                @if(auth()->user()?->hasAnyRole([\App\Models\User::ROLE_SUPER_ADMIN, \App\Models\User::ROLE_ADMIN]))
                    <a href="{{ route('admin.contact-messages.index') }}" @class(['active' => request()->routeIs('admin.contact-messages.*')])>Pesan Masuk</a>
                    <a href="{{ route('admin.activity-logs.index') }}" @class(['active' => request()->routeIs('admin.activity-logs.*')])>Activity Log</a>
                @endif
                @can('viewAny', \App\Models\Post::class)
                    <a href="{{ route('admin.posts.index') }}" @class(['active' => request()->routeIs('admin.posts.*')])>Berita</a>
                @endcan
                @can('viewAny', \App\Models\Media::class)
                    <a href="{{ route('admin.media.index') }}" @class(['active' => request()->routeIs('admin.media.*')])>Media</a>
                @endcan
                @can('viewAny', \App\Models\Category::class)
                    <a href="{{ route('admin.categories.index') }}" @class(['active' => request()->routeIs('admin.categories.*')])>Kategori</a>
                @endcan
                @can('viewAny', \App\Models\User::class)
                    <a href="{{ route('admin.users.index') }}" @class(['active' => request()->routeIs('admin.users.*')])>Admin/User</a>
                @endcan
                <a href="{{ route('home') }}" target="_blank">Lihat Website</a>
                <form action="{{ route('logout') }}" method="POST" class="mt-4">
                    @csrf
                    <button class="btn btn-outline-light w-100" type="submit">Logout</button>
                </form>
            </aside>
            <main class="col-lg-10 p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h1 class="h3 mb-1">@yield('page_title', 'Dashboard')</h1>
                        <p class="text-muted mb-0">Halo, {{ auth()->user()->name ?? 'Admin' }}</p>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @yield('content')
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
    @stack('scripts')
</body>
</html>
