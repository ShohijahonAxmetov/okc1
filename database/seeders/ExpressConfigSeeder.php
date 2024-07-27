<?php

namespace Database\Seeders;

use App\Models\ExpressConfig;
use Illuminate\Database\Seeder;

class ExpressConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
        	'id' => 1,
        	'price_up' => 0,
        	'vat' => 12
        ];

        ExpressConfig::create($data);
    }
}
