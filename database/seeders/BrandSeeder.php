<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Brand::factory()
            ->has(\App\Models\Product::factory()
                ->has(\App\Models\ProductVariation::factory()
                    ->count(10))
                ->count(10))
            ->create();
    }
}
