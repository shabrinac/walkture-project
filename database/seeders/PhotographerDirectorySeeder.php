<?php

namespace Database\Seeders;

use App\Models\PhotographerDirectory;
use Illuminate\Database\Seeder;

class PhotographerDirectorySeeder extends Seeder
{
    public function run(): void
    {
        PhotographerDirectory::truncate();

        // Default pricing packages template — each photographer can have customised prices
        $defaultPricing = [
            'basic' => [
                'label'       => 'Basic',
                'price'       => 'Rp150k – 300k',
                'duration'    => '1 jam',
                'photos'      => '30–50 foto edit',
                'locations'   => '1 lokasi',
                'features'    => ['Color correction & basic retouch', 'File via Google Drive'],
                'best_for'    => 'Individu, Personal branding, Graduation singkat',
            ],
            'standard' => [
                'label'       => 'Standard',
                'price'       => 'Rp350k – 700k',
                'duration'    => '2–3 jam',
                'photos'      => '80–120 foto edit',
                'locations'   => '1–2 lokasi',
                'features'    => ['Premium color grading', 'Arahan pose', '5 foto retouch detail (bonus)'],
                'best_for'    => 'Pre-grad, Foto organisasi, Event kecil',
            ],
            'premium' => [
                'label'       => 'Premium',
                'price'       => 'Rp800k – 1,5jt',
                'duration'    => '4–6 jam',
                'photos'      => '150–250 foto edit',
                'locations'   => 'Dokumentasi penuh',
                'features'    => ['Semua file original + edited', 'Premium grading + retouch pilihan', 'Highlight album online (bonus)'],
                'best_for'    => 'Seminar, Workshop, Event kampus',
            ],
            'fullday' => [
                'label'       => 'Full Day Event',
                'price'       => 'Rp2jt – 4jt',
                'duration'    => '8–12 jam',
                'photos'      => '300–500+ foto edit',
                'locations'   => 'Seluruh venue',
                'features'    => ['2 fotografer', 'Dokumentasi lengkap dari persiapan s/d selesai', 'Video slideshow highlight (bonus)'],
                'best_for'    => 'Pernikahan, Wisuda besar, Konferensi',
            ],
        ];

        $photographers = [

            // ── 8 ACTIVE, PAID / FEATURED PHOTOGRAPHERS ─────────────────────────
            [
                'full_name'        => 'Rizky Mahardika',
                'specialty'        => 'Analog',
                'avatar_url'       => 'https://i.pravatar.cc/300?img=11',
                'portfolio_url'    => 'https://rizkyfilm.cargo.site',
                'whatsapp_link'    => 'https://wa.me/6281255001001',
                'instagram_link'   => 'https://instagram.com/rizky.mahardika.film',
                'is_active'        => true,
                'paid_until'       => '2026-12-31',
                'pricing_packages' => array_merge($defaultPricing, [
                    'basic'    => array_merge($defaultPricing['basic'],    ['price' => 'Rp175k – 300k']),
                    'standard' => array_merge($defaultPricing['standard'], ['price' => 'Rp400k – 650k']),
                ]),
            ],
            [
                'full_name'        => 'Anisa Putri Wulandari',
                'specialty'        => 'Portrait',
                'avatar_url'       => 'https://i.pravatar.cc/300?img=47',
                'portfolio_url'    => 'https://anisaputri.format.com',
                'whatsapp_link'    => 'https://wa.me/6281255001002',
                'instagram_link'   => 'https://instagram.com/anisaputri.portrait',
                'is_active'        => true,
                'paid_until'       => '2026-10-15',
                'pricing_packages' => array_merge($defaultPricing, [
                    'basic'    => array_merge($defaultPricing['basic'],    ['price' => 'Rp200k – 350k']),
                    'premium'  => array_merge($defaultPricing['premium'],  ['price' => 'Rp900k – 1,8jt']),
                ]),
            ],
            [
                'full_name'        => 'Farhan Ardiansyah',
                'specialty'        => 'Street',
                'avatar_url'       => 'https://i.pravatar.cc/300?img=53',
                'portfolio_url'    => 'https://farhanstreet.myportfolio.com',
                'whatsapp_link'    => 'https://wa.me/6281255001003',
                'instagram_link'   => 'https://instagram.com/farhan.street.smr',
                'is_active'        => true,
                'paid_until'       => '2026-09-30',
                'pricing_packages' => $defaultPricing,
            ],
            [
                'full_name'        => 'Devi Rahmawati',
                'specialty'        => 'Cinematic',
                'avatar_url'       => 'https://i.pravatar.cc/300?img=23',
                'portfolio_url'    => 'https://devicinematics.com',
                'whatsapp_link'    => 'https://wa.me/6281255001004',
                'instagram_link'   => 'https://instagram.com/devirahma.cinematic',
                'is_active'        => true,
                'paid_until'       => '2026-11-20',
                'pricing_packages' => array_merge($defaultPricing, [
                    'standard' => array_merge($defaultPricing['standard'], ['price' => 'Rp500k – 900k', 'features' => ['Sinematik & color grading eksklusif', 'BTS footage', 'Arahan pose profesional']]),
                    'premium'  => array_merge($defaultPricing['premium'],  ['price' => 'Rp1,2jt – 2jt']),
                ]),
            ],
            [
                'full_name'        => 'Bagas Nugroho',
                'specialty'        => 'Landscape',
                'avatar_url'       => 'https://i.pravatar.cc/300?img=67',
                'portfolio_url'    => 'https://bagasnugroho.vsco.co',
                'whatsapp_link'    => 'https://wa.me/6281255001005',
                'instagram_link'   => 'https://instagram.com/bagas.landscape.kaltim',
                'is_active'        => true,
                'paid_until'       => '2026-08-01',
                'pricing_packages' => array_merge($defaultPricing, [
                    'basic'   => array_merge($defaultPricing['basic'],   ['price' => 'Rp150k – 250k', 'best_for' => 'Solo trip, Nature walk, Sunrise/Sunset']),
                    'fullday' => array_merge($defaultPricing['fullday'], ['price' => 'Rp1,8jt – 3,5jt', 'best_for' => 'Ekspedisi alam, Dokumentasi perjalanan']),
                ]),
            ],
            [
                'full_name'        => 'Siti Nuraini Hasanah',
                'specialty'        => 'Analog',
                'avatar_url'       => 'https://i.pravatar.cc/300?img=35',
                'portfolio_url'    => 'https://sitinuraini.exposure.co',
                'whatsapp_link'    => 'https://wa.me/6281255001006',
                'instagram_link'   => 'https://instagram.com/siti.analog.smr',
                'is_active'        => true,
                'paid_until'       => '2026-07-15',
                'pricing_packages' => $defaultPricing,
            ],
            [
                'full_name'        => 'Muhammad Iqbal Fauzan',
                'specialty'        => 'Architecture',
                'avatar_url'       => 'https://i.pravatar.cc/300?img=15',
                'portfolio_url'    => 'https://iqbalfauzan.behance.net',
                'whatsapp_link'    => 'https://wa.me/6281255001007',
                'instagram_link'   => 'https://instagram.com/iqbal.arch.photo',
                'is_active'        => true,
                'paid_until'       => '2026-12-01',
                'pricing_packages' => array_merge($defaultPricing, [
                    'basic'    => array_merge($defaultPricing['basic'],    ['price' => 'Rp250k – 400k', 'best_for' => 'Eksterior rumah, Ruko, Interior ruangan']),
                    'standard' => array_merge($defaultPricing['standard'], ['price' => 'Rp500k – 800k']),
                    'premium'  => array_merge($defaultPricing['premium'],  ['price' => 'Rp1jt – 2jt', 'best_for' => 'Properti komersial, Proyek arsitektur']),
                ]),
            ],
            [
                'full_name'        => 'Putri Aulia Zahra',
                'specialty'        => 'Street',
                'avatar_url'       => 'https://i.pravatar.cc/300?img=41',
                'portfolio_url'    => 'https://putriaulia.cargo.site',
                'whatsapp_link'    => 'https://wa.me/6281255001008',
                'instagram_link'   => 'https://instagram.com/putri.aulia.street',
                'is_active'        => true,
                'paid_until'       => '2026-06-30',
                'pricing_packages' => $defaultPricing,
            ],

            // ── 2 INACTIVE PHOTOGRAPHERS (free listings, past paid_until) ────────
            [
                'full_name'        => 'Hendro Kusuma',
                'specialty'        => 'Wildlife',
                'avatar_url'       => 'https://i.pravatar.cc/300?img=68',
                'portfolio_url'    => null,
                'whatsapp_link'    => 'https://wa.me/6281255001009',
                'instagram_link'   => 'https://instagram.com/hendro.wildlife.smr',
                'is_active'        => false,
                'paid_until'       => null,
                'pricing_packages' => null,
            ],
            [
                'full_name'        => 'Laila Miftahul Jannah',
                'specialty'        => 'Portrait',
                'avatar_url'       => 'https://i.pravatar.cc/300?img=49',
                'portfolio_url'    => null,
                'whatsapp_link'    => 'https://wa.me/6281255001010',
                'instagram_link'   => 'https://instagram.com/laila.portrait.smr',
                'is_active'        => false,
                'paid_until'       => null,
                'pricing_packages' => null,
            ],
        ];

        foreach ($photographers as $photographer) {
            PhotographerDirectory::create($photographer);
        }
    }
}
