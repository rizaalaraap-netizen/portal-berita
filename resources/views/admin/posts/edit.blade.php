@extends('layouts.admin')

@section('title', 'Edit Berita | Portal Berita Admin')
@section('page_title', 'Edit Berita')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @include('admin.posts._form', ['button' => 'Perbarui Berita'])
            </form>
        </div>
    </div>
@endsection
