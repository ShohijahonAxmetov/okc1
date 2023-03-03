<?php

namespace App\Console;

use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $products = \App\Models\ProductVariation::with('product', 'product.brand', 'productVariationImages')
                ->where('remainder', '!=', 0)
                ->get();

            $data = [];
            $counter = 0;
            foreach ($products as $product) {
                $data[$counter]['id'] = $product->venkon_id;
                $data[$counter]['title'] = $product->product->title['ru'] ?? null;
                $data[$counter]['desc'] = $product->product->desc['ru'] ?? null;
                $data[$counter]['how_to_use'] = $product->product->how_to_use['ru'] ?? null;
                $data[$counter]['brand'] = $product->product->brand->title ?? null;
                $data[$counter]['remainder'] = $product->remainder ?? null;

                $image_counter = 0;
                if (isset($product->productVariationImages[0])) {
                    foreach ($product->productVariationImages as $img) {
                        $data[$counter]['images'][$image_counter] = $img->img;
                        $image_counter++;
                    }
                    $image_counter = 0;
                    $data[$counter]['images'] = implode('|', $data[$counter]['images']);
                }
                $counter++;
            }

            $fp = fopen(public_path('allin.csv'), 'w');

            fputcsv($fp, ['ID', 'Title', 'Description', 'How to use', 'Brand', 'Remainder', 'Images']);

            foreach ($data as $fields) {
                fputcsv($fp, $fields);
            }

            fclose($fp);




            // // for test
            // Mail::raw('message', function ($message) {
            //     $message->to('shohijahonaxmetov@gmail.com')
            //         ->subject('subject');
            // });
        })->everyMinute();
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
