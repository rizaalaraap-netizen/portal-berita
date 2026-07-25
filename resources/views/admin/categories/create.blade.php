@extends('layouts.admin')

@section('title', 'Tambah Kategori | Portal Berita Admin')
@section('page_title', 'Tambah Kategori')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @include('admin.categories._form', ['button' => 'Simpan Kategori'])
            </form>
        </div>
    </div>
@endsection
