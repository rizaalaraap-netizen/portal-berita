@extends('layouts.admin')

@section('title', 'Edit Kategori | Portal Berita Admin')
@section('page_title', 'Edit Kategori')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                @method('PUT')
                @include('admin.categories._form', ['button' => 'Perbarui Kategori'])
            </form>
        </div>
    </div>
@endsection
