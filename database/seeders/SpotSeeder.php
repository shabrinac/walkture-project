<?php

namespace Database\Seeders;

use App\Models\Spot;
use Illuminate\Database\Seeder;

class SpotSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate first so re-running is safe
        Spot::truncate();

        $spots = [

            // ── 4 SPONSORED SPOTS — Premium cafes / B2B clients ──────────────────
            [
                'name'         => 'Kopi Arabika Siradj Salman',
                'category'     => 'Street',
                'latitude'     => -0.4943,
                'longitude'    => 117.1479,
                'description'  => 'Kedai kopi specialty bergaya industrial yang menghadap gang bersejarah di Jl. Siradj Salman. Cahaya sore yang masuk lewat jendela kaca besar sangat sempurna untuk foto analog.',
                'image_url'    => 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?w=800',
                'is_sponsored' => true,
                'promo_detail' => 'Diskon 15% untuk semua minuman bagi pengguna Walkture yang check-in di sini. Berlaku setiap hari pukul 14.00–18.00.',
            ],
            [
                'name'         => 'Lensa & Latte – Jl. Juanda',
                'category'     => 'Portrait',
                'latitude'     => -0.5031,
                'longitude'    => 117.1501,
                'description'  => 'Kafe estetik ber-tema photography di Jl. Juanda. Dinding bata ekspos, rak film 35mm, dan pencahayaan tungsten hangat. Spot wajib bagi fotografer street.',
                'image_url'    => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=800',
                'is_sponsored' => true,
                'promo_detail' => 'Free satu roll film Kodak Gold 200 untuk pembelian paket foto studio di atas Rp 150.000.',
            ],
            [
                'name'         => 'Warung Analog Samarinda',
                'category'     => 'Analog',
                'latitude'     => -0.4971,
                'longitude'    => 117.1444,
                'description'  => 'Warung kopi unik bertema kamera analog era 70-an. Koleksi kamera vintage terpajang di dinding. Sediakan waktu ekstra untuk berbincang dengan owner yang juga fotografer analog.',
                'image_url'    => 'https://images.unsplash.com/photo-1495707902641-75cac588d2e9?w=800',
                'is_sponsored' => true,
                'promo_detail' => 'Beli 2 kopi dapat 1 gratis setiap Sabtu. Tunjukkan profil Walkture kamu kepada barista.',
            ],
            [
                'name'         => 'Ruang Seni Tepian – Riverside',
                'category'     => 'Landscape',
                'latitude'     => -0.5012,
                'longitude'    => 117.1612,
                'description'  => 'Galeri seni modern yang duduk tepat di tepi Sungai Mahakam. Arsitektur kontemporer dengan view sungai yang dramatis — ideal untuk foto arsitektur dan portrait outdoor.',
                'image_url'    => 'https://images.unsplash.com/photo-1470252649378-9c29740c9fa8?w=800',
                'is_sponsored' => true,
                'promo_detail' => 'Akses rooftop gratis untuk pengunjung yang menunjukkan aplikasi Walkture. Buka setiap hari 09.00–21.00.',
            ],

            // ── 6 NON-SPONSORED SPOTS — Heritage & Cultural ──────────────────────
            [
                'name'         => 'Kompleks Citra Niaga',
                'category'     => 'Architecture',
                'latitude'     => -0.4979,
                'longitude'    => 117.1445,
                'description'  => 'Kompleks perbelanjaan terbuka bersejarah yang pernah meraih Aga Khan Award for Architecture 1989. Lorong-lorong antara ruko bergaya etnis Banjar sangat fotogenik terutama saat senja.',
                'image_url'    => 'https://images.unsplash.com/photo-1449157291145-7efd050a4d0e?w=800',
                'is_sponsored' => false,
                'promo_detail' => null,
            ],
            [
                'name'         => 'Tepian Mahakam – Promenade',
                'category'     => 'Landscape',
                'latitude'     => -0.5038,
                'longitude'    => 117.1534,
                'description'  => 'Taman tepi sungai yang memanjang sepanjang Sungai Mahakam. Saat golden hour, pantulan cahaya di permukaan sungai menciptakan siluet perahu kayu yang menakjubkan.',
                'image_url'    => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800',
                'is_sponsored' => false,
                'promo_detail' => null,
            ],
            [
                'name'         => 'Masjid Islamic Center Samarinda',
                'category'     => 'Architecture',
                'latitude'     => -0.4681,
                'longitude'    => 117.1476,
                'description'  => 'Masjid megah berkapasitas 40.000 jamaah yang berdiri di tepi Sungai Mahakam. Kubah besar keemasan sangat cocok untuk foto arsitektur dan long-exposure saat malam hari.',
                'image_url'    => 'https://images.unsplash.com/photo-1519681393784-d120267933ba?w=800',
                'is_sponsored' => false,
                'promo_detail' => null,
            ],
            [
                'name'         => 'Taman Samarendah',
                'category'     => 'Landscape',
                'latitude'     => -0.4922,
                'longitude'    => 117.1456,
                'description'  => 'Taman kota yang tenang dengan patung ikonik dan area hijau. Spot favorit untuk foto candid keluarga, portrait natural, dan foto dengan latar belakang landmark kota.',
                'image_url'    => 'https://images.unsplash.com/photo-1444703686981-a3abbc4d4fe3?w=800',
                'is_sponsored' => false,
                'promo_detail' => null,
            ],
            [
                'name'         => 'Gang Muralis Antasari',
                'category'     => 'Street',
                'latitude'     => -0.5087,
                'longitude'    => 117.1389,
                'description'  => 'Deretan gang sempit di kawasan Antasari yang dipenuhi mural warna-warni karya seniman lokal Samarinda. Cahaya pagi dari ujung gang menciptakan kondisi pencahayaan yang dramatis.',
                'image_url'    => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=800',
                'is_sponsored' => false,
                'promo_detail' => null,
            ],
            [
                'name'         => 'Museum Samarinda – Ruang Pameran',
                'category'     => 'Architecture',
                'latitude'     => -0.4896,
                'longitude'    => 117.1510,
                'description'  => 'Ruang pameran utama Museum Samarinda dengan pencahayaan spot yang elegan. Koleksi batik Dayak dan artefak keramaan menciptakan latar belakang bertekstur yang kaya untuk foto still life dan portrait.',
                'image_url'    => 'https://images.unsplash.com/photo-1502920917128-1aa500764bfe?w=800',
                'is_sponsored' => false,
                'promo_detail' => null,
            ],
        ];

        foreach ($spots as $spot) {
            Spot::create($spot);
        }
    }
}
