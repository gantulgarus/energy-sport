<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sports = [
            ['name' => 'Сагсан бөмбөг', 'slug' => 'basketball', 'icon' => '🏀', 'gender_type' => 'male_female', 'sort_order' => 1],
            ['name' => 'Волейбол', 'slug' => 'volleyball', 'icon' => '🏐', 'gender_type' => 'male_female', 'sort_order' => 2],
            ['name' => 'Ширээний теннис', 'slug' => 'table-tennis', 'icon' => '🏓', 'gender_type' => 'male_female', 'sort_order' => 3],
            ['name' => 'Шатар', 'slug' => 'chess', 'icon' => '♟️', 'gender_type' => 'male_female', 'sort_order' => 4],
            ['name' => 'И-спорт (CS2)', 'slug' => 'esports', 'icon' => '🎮', 'gender_type' => 'mixed', 'sort_order' => 5],
            ['name' => 'Олс таталт', 'slug' => 'tug-of-war', 'icon' => '🪢', 'gender_type' => 'mixed', 'sort_order' => 6],
        ];

        foreach ($sports as $sport) {
            \App\Models\Sport::firstOrCreate(['slug' => $sport['slug']], $sport);
        }
    }
}
