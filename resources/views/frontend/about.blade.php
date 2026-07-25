@extends('layouts.frontend')

@section('title', 'Tentang Kami | Portal Berita')

@section('content')
    <section class="container page-hero">
        <h1 class="page-title">Tentang Kami</h1>
        <p class="page-description">Mengenal lebih dekat PortalBerita sebagai media informasi yang menyajikan berita terbaru, terpercaya, dan mudah diakses oleh masyarakat.</p>
    </section>

    <section class="container about-section">
        <div class="about-image">
            <img src="{{ asset('images/logo.svg') }}" alt="PortalBerita">
        </div>
        <div class="about-content">
            <h2>PortalBerita</h2>
            <p>PortalBerita merupakan website berita sederhana yang dibuat untuk menyajikan informasi terkini dari berbagai kategori seperti nasional, teknologi, olahraga, ekonomi, hiburan, pendidikan, dan kesehatan.</p>
            <p>Kami berkomitmen untuk menghadirkan berita yang informatif, terpercaya, dan mudah dipahami sehingga pembaca dapat memperoleh informasi secara cepat dan akurat.</p>
            <p>Website ini dirancang dengan tampilan yang responsif dan nyaman digunakan baik melalui komputer maupun perangkat mobile.</p>
        </div>
    </section>
@endsection
