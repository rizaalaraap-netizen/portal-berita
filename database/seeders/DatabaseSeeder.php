<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->updateOrCreate([
            'email' => 'admin@portalberita.test',
        ], [
            'name' => 'Administrator',
            'role' => 'super-admin',
            'password' => Hash::make('password'),
        ]);

        $categories = collect([
            'Nasional',
            'Internasional',
            'Politik',
            'Ekonomi',
            'Bisnis',
            'Teknologi',
            'Otomotif',
            'Olahraga',
            'Lifestyle',
            'Hiburan',
            'Kesehatan',
        ])->map(fn (string $name) => Category::query()->updateOrCreate([
            'slug' => Str::slug($name),
        ], [
            'name' => $name,
            'description' => "Berita terbaru kategori {$name}.",
            'is_active' => true,
        ]));

        $posts = [
            ['Indonesia Resmi Luncurkan Teknologi AI Generasi Terbaru', 'Teknologi', 'images/headline.svg'],
            ['AI Diprediksi Mengubah Dunia Pendidikan', 'Teknologi', 'images/berita1.svg'],
            ['Indonesia Siap Menjadi Pusat Digital ASEAN', 'Nasional', 'images/berita2.svg'],
            ['Nilai Rupiah Menguat Terhadap Dolar AS', 'Ekonomi', 'images/berita3.svg'],
            ['Timnas Indonesia Menang Dramatis', 'Olahraga', 'images/berita2.svg'],
            ['Startup Indonesia Tembus Pasar Internasional', 'Bisnis', 'images/headline.svg'],
            ['Harga BBM Mengalami Penyesuaian', 'Ekonomi', 'images/berita3.svg'],
            ['Pemerintah Bangun Infrastruktur Baru', 'Nasional', 'images/berita1.svg'],
            ['Transformasi Digital Terus Dipercepat', 'Teknologi', 'images/berita1.svg'],
        ];

        foreach ($posts as $index => [$title, $categoryName, $thumbnail]) {
            $category = $categories->firstWhere('name', $categoryName);

            Post::query()->updateOrCreate([
                'slug' => Str::slug($title),
            ], [
                'category_id' => $category->id,
                'author_id' => $admin->id,
                'title' => $title,
                'meta_title' => $title,
                'meta_description' => "Baca berita terbaru tentang {$title} hanya di PortalBerita.",
                'thumbnail' => $thumbnail,
                'og_image' => $thumbnail,
                'content' => $this->contentFor($title),
                'status' => Post::STATUS_PUBLISHED,
                'published_at' => now()->subDays($index),
                'views' => 2500 - ($index * 170),
            ]);
        }
    }

    private function contentFor(string $title): string
    {
        return <<<HTML
<p>{$title} menjadi perhatian publik karena dinilai membawa dampak besar bagi masyarakat dan perkembangan nasional.</p>
<p>Pemerintah dan berbagai pemangku kepentingan terus mendorong kolaborasi agar perubahan ini dapat memberi manfaat yang luas, aman, dan berkelanjutan.</p>
<p>Para pengamat menilai langkah ini perlu dibarengi kesiapan sumber daya manusia, tata kelola data, serta komunikasi publik yang jelas.</p>
<blockquote>Transformasi digital bukan lagi pilihan, melainkan kebutuhan untuk meningkatkan daya saing bangsa.</blockquote>
<p>Dengan dukungan ekosistem yang kuat, Indonesia diharapkan mampu bersaing di tingkat global sekaligus menjaga kepentingan masyarakat.</p>
HTML;
    }
}
