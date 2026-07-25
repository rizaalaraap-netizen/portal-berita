@extends('layouts.admin')

@section('title', 'Tambah Berita | Portal Berita Admin')
@section('page_title', 'Tambah Berita')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
                @include('admin.posts._form', ['button' => 'Simpan Berita'])
            </form>
        </div>
    </div>
@endsection
