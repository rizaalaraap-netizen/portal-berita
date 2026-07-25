@extends('layouts.admin')

@section('title', 'Edit Admin | Portal Berita Admin')
@section('page_title', 'Edit Admin')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @method('PUT')
                @include('admin.users._form', ['button' => 'Perbarui Admin'])
            </form>
        </div>
    </div>
@endsection
