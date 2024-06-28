<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class RssController extends Controller
{
    public function catalog()
    {
        if (!Cache::has('rss.shop')) Cache::put('rss.shop', $this->getShop(), 3600);
        $shop = Cache::get('rss.shop');

        if (!Cache::has('rss.date')) Cache::put('rss.date', date('c', strtotime(date('Y-m-d H:i:s'))), 3600);
        $date = Cache::get('rss.date');

        if (!Cache::has('rss.offers')) Cache::put('rss.offers', $this->getOffers(), 3600);
        $offers = Cache::get('rss.offers');

        if (!Cache::has('rss.categories')) Cache::put('rss.categories', $this->getCategories(), 3600);
        $categories = Cache::get('rss.categories');

        $content = view('rss.catalog', [
                'shop' => $shop,
                'categories' => $categories,
                'offers' => $offers,
                'date' => $date
            ]);
        Storage::disk('public')->put('catalog.xml', $content);

        return 1;
    }

    function getCategories(): array
    {
    	$allCategories = Category::where('is_active', 1)
    		->get();

		$formattedCategories = [];
		foreach ($allCategories as $value) {
			if (!$value->parent_id) $formattedCategories[] = [
				 'attributes' => [
				 	'id' => '1'.preg_replace('/\D/', '', $value->integration_id),
				 ],
				'name' => $value->title['ru'] ?? '-'

			];
			else $formattedCategories[] = [
				 'attributes' => [
				 	'id' => '1'.preg_replace('/\D/', '', $value->integration_id),
				 	'parentId' => '1'.preg_replace('/\D/', '', $value->parent_id),
				 ],
				'name' => $value->title['ru'] ?? '-'
			];
		}

    	return $formattedCategories;
    }

    function getOffers(): array
    {
    	$allProducts = ProductVariation::whereHas('product', function ($query) {
    		$query->where('is_active', 1);
    	})
	    	->where([
	    		['remainder', '>', 10],
	    		['is_available', 1],
	    		['is_active', 1],
	    	])
    		->get();

    	$formattedProducts = [];
    	foreach ($allProducts as $value) {
    		$formattedProducts[] = [
    			'enabled' => 1,
    			'sku' => '1'.preg_replace('/\D/', '', $value->integration_id),
    			'name' => $value->product->title['ru'] ?? '-',
    			'price' => $value->price,
    			'picture' => isset($value->productVariationImages[0]) ? $value->productVariationImages[0]->realImg : null,
    			'description' => $value->product->desc['ru'] ?? '-',
    			'brand' => $value->product->brand->title ?? '-',
    			'quantityStock' => $value->remainder - 10,
                'categoryId' => '1'.preg_replace('/\D/', '', $value->product->categories[0]->integration_id)
    		];
    	}

    	return $formattedProducts;
    }

    function getShop(): array
    {
        return [
            'name' => 'OKC (Original Korean Cosmetics)',
            'company' => 'BEAUTY PROF',
            'url' => 'https://www.okc.uz/',
        ];
    }
}
