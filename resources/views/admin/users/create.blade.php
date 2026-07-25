@extends('layouts.admin')

@section('title', 'Tambah Admin | Portal Berita Admin')
@section('page_title', 'Tambah Admin')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @include('admin.users._form', ['button' => 'Simpan Admin'])
            </form>
        </div>
    </div>
@endsection
