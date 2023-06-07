<?php

namespace Database\Seeders;

use App\Models\Info;
use Illuminate\Database\Seeder;

class InfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Info::create([
            'title' => 'OKC.uz',
            'meta_title' => null,
            'meta_desc' => null,
            'meta_keywords' => null,
            'facebook' => null,
            'telegram' => null,
            'instagram' => null,
            'youtube' => null,
            'phone_number' => null,
            'dop_phone_number' => null,
        ]);
    }
}
