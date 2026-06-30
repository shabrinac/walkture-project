<?php

namespace Database\Seeders;

use App\Models\SpatialArea;
use Illuminate\Database\Seeder;

class SpatialAreaSeeder extends Seeder
{
    public function run(): void
    {
        SpatialArea::truncate();

        $areas = [

            // ══════════════════════════════════════════════════════════════════════
            // 5 ROUTES — Polylines (walking/photography trails in Samarinda)
            // ══════════════════════════════════════════════════════════════════════

            [
                'name'             => 'Susur Sungai Mahakam Walk',
                'type'             => 'route',
                'distance_or_area' => 3.20,
                'geo_data'         => [
                    'type'     => 'Feature',
                    'geometry' => [
                        'type'        => 'LineString',
                        'coordinates' => [
                            [117.1399, -0.5038],
                            [117.1445, -0.5034],
                            [117.1490, -0.5035],
                            [117.1534, -0.5038],
                            [117.1580, -0.5040],
                            [117.1620, -0.5044],
                        ],
                    ],
                    'properties' => ['name' => 'Susur Sungai Mahakam Walk', 'type' => 'route'],
                ],
            ],

            [
                'name'             => 'Pecinan Heritage Trail',
                'type'             => 'route',
                'distance_or_area' => 1.80,
                'geo_data'         => [
                    'type'     => 'Feature',
                    'geometry' => [
                        'type'        => 'LineString',
                        'coordinates' => [
                            [117.1420, -0.4965],
                            [117.1435, -0.4970],
                            [117.1445, -0.4979],
                            [117.1455, -0.4988],
                            [117.1462, -0.4998],
                        ],
                    ],
                    'properties' => ['name' => 'Pecinan Heritage Trail', 'type' => 'route'],
                ],
            ],

            [
                'name'             => 'Jl. Siradj Salman Café Crawl Route',
                'type'             => 'route',
                'distance_or_area' => 1.10,
                'geo_data'         => [
                    'type'     => 'Feature',
                    'geometry' => [
                        'type'        => 'LineString',
                        'coordinates' => [
                            [117.1440, -0.4930],
                            [117.1455, -0.4935],
                            [117.1469, -0.4940],
                            [117.1479, -0.4943],
                            [117.1490, -0.4950],
                        ],
                    ],
                    'properties' => ['name' => 'Siradj Salman Café Crawl', 'type' => 'route'],
                ],
            ],

            [
                'name'             => 'Antasari Mural Street Walk',
                'type'             => 'route',
                'distance_or_area' => 0.95,
                'geo_data'         => [
                    'type'     => 'Feature',
                    'geometry' => [
                        'type'        => 'LineString',
                        'coordinates' => [
                            [117.1360, -0.5082],
                            [117.1370, -0.5085],
                            [117.1380, -0.5087],
                            [117.1389, -0.5087],
                            [117.1400, -0.5090],
                        ],
                    ],
                    'properties' => ['name' => 'Antasari Mural Street Walk', 'type' => 'route'],
                ],
            ],

            [
                'name'             => 'Islamic Center – Tepian Riverfront Loop',
                'type'             => 'route',
                'distance_or_area' => 4.50,
                'geo_data'         => [
                    'type'     => 'Feature',
                    'geometry' => [
                        'type'        => 'LineString',
                        'coordinates' => [
                            [117.1476, -0.4681],
                            [117.1490, -0.4750],
                            [117.1500, -0.4830],
                            [117.1510, -0.4896],
                            [117.1510, -0.4960],
                            [117.1500, -0.5020],
                            [117.1490, -0.5038],
                        ],
                    ],
                    'properties' => ['name' => 'Islamic Center – Tepian Loop', 'type' => 'route'],
                ],
            ],

            // ══════════════════════════════════════════════════════════════════════
            // 5 ZONES — Polygons (photography-friendly districts/areas)
            // ══════════════════════════════════════════════════════════════════════

            [
                'name'             => 'Kawasan Kreatif Citra Niaga',
                'type'             => 'zone',
                'distance_or_area' => 0.08,
                'geo_data'         => [
                    'type'     => 'Feature',
                    'geometry' => [
                        'type'        => 'Polygon',
                        'coordinates' => [[
                            [117.1435, -0.4965],
                            [117.1460, -0.4965],
                            [117.1460, -0.4990],
                            [117.1435, -0.4990],
                            [117.1435, -0.4965],
                        ]],
                    ],
                    'properties' => ['name' => 'Kawasan Kreatif Citra Niaga', 'type' => 'zone'],
                ],
            ],

            [
                'name'             => 'Taman Samarendah Aesthetic Zone',
                'type'             => 'zone',
                'distance_or_area' => 0.12,
                'geo_data'         => [
                    'type'     => 'Feature',
                    'geometry' => [
                        'type'        => 'Polygon',
                        'coordinates' => [[
                            [117.1440, -0.4910],
                            [117.1470, -0.4910],
                            [117.1470, -0.4935],
                            [117.1440, -0.4935],
                            [117.1440, -0.4910],
                        ]],
                    ],
                    'properties' => ['name' => 'Taman Samarendah Zone', 'type' => 'zone'],
                ],
            ],

            [
                'name'             => 'Tepian Mahakam Promenade Zone',
                'type'             => 'zone',
                'distance_or_area' => 0.25,
                'geo_data'         => [
                    'type'     => 'Feature',
                    'geometry' => [
                        'type'        => 'Polygon',
                        'coordinates' => [[
                            [117.1390, -0.5025],
                            [117.1640, -0.5025],
                            [117.1640, -0.5055],
                            [117.1390, -0.5055],
                            [117.1390, -0.5025],
                        ]],
                    ],
                    'properties' => ['name' => 'Tepian Mahakam Zone', 'type' => 'zone'],
                ],
            ],

            [
                'name'             => 'Zona Analog – Kawasan Juanda',
                'type'             => 'zone',
                'distance_or_area' => 0.09,
                'geo_data'         => [
                    'type'     => 'Feature',
                    'geometry' => [
                        'type'        => 'Polygon',
                        'coordinates' => [[
                            [117.1485, -0.5020],
                            [117.1520, -0.5020],
                            [117.1520, -0.5045],
                            [117.1485, -0.5045],
                            [117.1485, -0.5020],
                        ]],
                    ],
                    'properties' => ['name' => 'Zona Analog Kawasan Juanda', 'type' => 'zone'],
                ],
            ],

            [
                'name'             => 'Distrik Seni Islamic Center',
                'type'             => 'zone',
                'distance_or_area' => 0.18,
                'geo_data'         => [
                    'type'     => 'Feature',
                    'geometry' => [
                        'type'        => 'Polygon',
                        'coordinates' => [[
                            [117.1455, -0.4660],
                            [117.1510, -0.4660],
                            [117.1510, -0.4710],
                            [117.1455, -0.4710],
                            [117.1455, -0.4660],
                        ]],
                    ],
                    'properties' => ['name' => 'Distrik Seni Islamic Center', 'type' => 'zone'],
                ],
            ],
        ];

        foreach ($areas as $area) {
            SpatialArea::create($area);
        }
    }
}
