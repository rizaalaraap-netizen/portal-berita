@extends('layouts.frontend')

@section('title', 'Kontak | Portal Berita')

@section('content')
    <section class="container page-hero">
        <h1 class="page-title">Hubungi Kami</h1>
        <p class="page-description">Jika Anda memiliki pertanyaan, kritik, saran, atau ingin bekerja sama, silakan hubungi kami melalui formulir di bawah ini.</p>
    </section>

    <section class="container contact-section">
        <div class="contact-info">
            <h2>Informasi Kontak</h2>
            <p><strong><i class="fa-solid fa-location-dot"></i> Alamat</strong><br>Jl. Contoh No. 123, Jakarta, Indonesia</p>
            <p><strong><i class="fa-solid fa-phone"></i> Telepon</strong><br>+62 812-3456-7890</p>
            <p><strong><i class="fa-solid fa-envelope"></i> Email</strong><br>info@portalberita.com</p>
            <p><strong><i class="fa-solid fa-clock"></i> Jam Operasional</strong><br>Senin - Jumat<br>08.00 - 17.00 WIB</p>
        </div>

        <div class="contact-form">
            <h2>Kirim Pesan</h2>
            @if(session('success'))
                <div class="contact-alert">{{ session('success') }}</div>
            @endif
            <form method="POST" action="{{ route('contact.store') }}">
                @csrf
                <label for="nama">Nama Lengkap</label>
                <input id="nama" name="nama" type="text" value="{{ old('nama') }}" placeholder="Masukkan nama Anda" required>
                @error('nama')<span class="form-error">{{ $message }}</span>@enderror
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="Masukkan email Anda" required>
                @error('email')<span class="form-error">{{ $message }}</span>@enderror
                <label for="subjek">Subjek</label>
                <input id="subjek" name="subjek" type="text" value="{{ old('subjek') }}" placeholder="Masukkan subjek" required>
                @error('subjek')<span class="form-error">{{ $message }}</span>@enderror
                <label for="pesan">Pesan</label>
                <textarea id="pesan" name="pesan" rows="6" placeholder="Tulis pesan Anda..." required>{{ old('pesan') }}</textarea>
                @error('pesan')<span class="form-error">{{ $message }}</span>@enderror
                <button type="submit">Kirim Pesan</button>
            </form>
        </div>
    </section>
@endsection
