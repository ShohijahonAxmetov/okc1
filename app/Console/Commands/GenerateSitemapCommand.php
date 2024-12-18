<?php

namespace App\Console\Commands;

use App\Models\ProductVariation;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Console\Command;

class GenerateSitemapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap file in /okc.uz/public/sitemap.xml';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->generateSitemap();

        return $this->info('Successfully generated');
    }

    private function generateSitemap()
    {
        $data = array_merge($this->generateOtherPagesArr4Sitemap(), $this->generateCategoriesArr4Sitemap(), $this->generateProductsArr4Sitemap(), $this->generateNewsArr4Sitemap());

        // Создаем объект SimpleXMLElement
        $xml = new \SimpleXMLElement('<urlset/>');
        $xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $xml->addAttribute('xmlns:xhtml', 'http://www.w3.org/TR/xhtml11/xhtml11_schema.html');

        foreach ($data as $key => $item) {
            $itemNode = $xml->addChild('url');
            $itemNode->addChild('loc', $item['loc']);
            $itemNode->addChild('lastmod', $item['lastmod']);
            $itemNode->addChild('changefreq', $item['changefreq']);
            $itemNode->addChild('priority', $item['priority']);
        }

        // Определяем путь для сохранения файла
        $directoryPath = config('sitemap.full_path_to_sitemap');
        $filePath = $directoryPath . '/sitemap.xml';

        // Сохраняем XML в файл
        $xml->asXML($filePath);
    }

    private function generateNewsArr4Sitemap(): array
    {
        $items = Post::get(['slug']);

        $itemsArr= [];
        foreach ($items as $item) {
            $itemsArr[] = [
                'loc' => env('APP_URL').'/blog/'.$item->slug,
                'lastmod' => gmdate(\DateTime::W3C, strtotime(date('Y-m-d H:i:s'))),
                'changefreq' => 'daily',
                'priority' => '0.7',
            ];
        }

        return $itemsArr;
    }

    private function generateCategoriesArr4Sitemap(): array
    {
        $items = Category::where(function ($q) {
            $q->where('is_active', 1)
                ->whereNotNull('integration_id')
                ->whereHas('products', function ($pQ) {
                    $pQ->where('is_active', 1)
                        ->whereNotNull('brand_id')
                        ->whereHas('productVariations', function ($vQ) {
                            $vQ->where('is_active', 1)
                                ->whereNotNull('integration_id')
                                ->where('remainder', '>', 10);
                        });
                });
        })
            ->get(['slug']);

        $itemsArr= [];
        foreach ($items as $item) {
            $itemsArr[] = [
                'loc' => env('APP_URL').'/category/'.$item->slug,
                'lastmod' => gmdate(\DateTime::W3C, strtotime(date('Y-m-d H:i:s'))),
                'changefreq' => 'daily',
                'priority' => '0.9',
            ];
        }

        return $itemsArr;
    }

    private function generateProductsArr4Sitemap(): array
    {
        $items = ProductVariation::where(function ($q) {
            $q->where('is_active', 1)
                ->whereNotNull('integration_id')
                ->where('remainder', '>', 10)
                ->whereHas('product', function ($pQ) {
                    $pQ->where('is_active', 1)
                        ->whereNotNull('brand_id');
                });
        })
            ->get(['slug']);

        $itemsArr= [];
        foreach ($items as $item) {
            $itemsArr[] = [
                'loc' => env('APP_URL').'/products/'.$item->slug,
                'lastmod' => gmdate(\DateTime::W3C, strtotime(date('Y-m-d H:i:s'))),
                'changefreq' => 'daily',
                'priority' => '0.8',
            ];
        }

        return $itemsArr;
    }

    private function generateOtherPagesArr4Sitemap(): array
    {
        return [
            [
                'loc' => env('APP_URL'),
                'lastmod' => gmdate(\DateTime::W3C, strtotime(date('Y-m-d H:i:s'))),
                'changefreq' => 'daily',
                'priority' => '1',
            ],
            [
                'loc' => env('APP_URL').'/products',
                'lastmod' => gmdate(\DateTime::W3C, strtotime(date('Y-m-d H:i:s'))),
                'changefreq' => 'daily',
                'priority' => '0.9',
            ],
            [
                'loc' => env('APP_URL').'/about',
                'lastmod' => gmdate(\DateTime::W3C, strtotime(date('Y-m-d H:i:s'))),
                'changefreq' => 'daily',
                'priority' => '0.7',
            ],
            [
                'loc' => env('APP_URL').'/contacts',
                'lastmod' => gmdate(\DateTime::W3C, strtotime(date('Y-m-d H:i:s'))),
                'changefreq' => 'daily',
                'priority' => '0.7',
            ],
            [
                'loc' => env('APP_URL').'/blog',
                'lastmod' => gmdate(\DateTime::W3C, strtotime(date('Y-m-d H:i:s'))),
                'changefreq' => 'daily',
                'priority' => '0.7',
            ],
        ];
    }
}
