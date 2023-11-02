<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ArtikelsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'title' => 'Judul Artikel 1',
                'content' => 'Isi artikel 1',
                'slug' => 'judul-artikel-1',
                'img' => 'gambar1.jpg',
                'views' => 100,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Judul Artikel 2',
                'content' => 'Isi artikel 2',
                'slug' => 'judul-artikel-2',
                'img' => 'gambar2.jpg',
                'views' => 200,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('artikels')->insertBatch($data);
    }
}
