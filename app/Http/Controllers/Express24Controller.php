<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class Express24Controller extends Controller
{
	protected $token = null;
	protected $baseUrl = null;

	public function __construct()
	{
		$this->token = env('EXPRESS24_TOKEN');
		$this->baseUrl = env('EXPRESS24_BASE_URL');
	}

    public function categories()
    {
    	$successSentCategoryIds = $this->getCategories();

        $successSentSubCategoryIds = $this->getSubCategories($successSentCategoryIds);
        
        dd($this->getProducts($successSentSubCategoryIds));

    	return $successSentSubCategoryIds;
    }

    public function getCategories(): array
    {
    	$allMainCategories = Category::where([
    		['is_active', 1],
    		['parent_id', null]
		])
    		->get();
		$successSentCategoryIds = array();

		Log::channel('express24')->info('------------- SYNC MAIN CATEGORIES AT '.date('Y-m-d H:i').'--------------');
		foreach ($allMainCategories as $category) {

			if (!$category->express24_id) {
				$res = Http::withToken($this->token)
					->post($this->baseUrl.'/categories', [
						'externalID' => $category->integration_id,
						'name' => $category->title['ru'],
						'isActive' => 0,
						'sort' => 9998
					]);

				if (!$res->successful()) Log::channel('express24')->info($res.'. Category name: {category}', ['category' => $category->title['ru']]);
				else $successSentCategoryIds[] = $category->integration_id;

				if (isset($res->json()['id'])) $category->update([
					'express24_id' => $res->json()['id']
				]);
			} else {
				$res = Http::withToken($this->token)
					->patch($this->baseUrl.'/categories/'.$category->express24_id, [
						'externalID' => $category->integration_id,
						'name' => $category->title['ru'],
						'isActive' => 0,
						'sort' => 9997
					]);

				if (!$res->successful()) Log::channel('express24')->info($res.'. Category name: {category}', ['category' => $category->title['ru']]);
				else $successSentCategoryIds[] = $category->integration_id;
			}
		}

		return $successSentCategoryIds;
    }

    public function getSubCategories(array $mainCategoryIds): array
    {
    	$categories = Category::where(function ($query) use ($mainCategoryIds) {
    		$query->where('is_active', 1)
    			->whereIn('parent_id', $mainCategoryIds);
    	})
    		->get();

//		dd($categories[0]->parent->express24_id);

        $successSentCategoryIds = array();

        Log::channel('express24')->info('------------- SYNC SUB CATEGORIES AT '.date('Y-m-d H:i').'--------------');
        foreach ($categories as $category) {

            if (!$category->express24_id) {
                $res = Http::withToken($this->token)
                    ->post($this->baseUrl.'/categories/'.$category->parent->express24_id.'/subs', [
                        'externalID' => $category->integration_id,
                        'name' => $category->title['ru'],
                        'isActive' => 0,
                        'sort' => 9998
                    ]);

                if (!$res->successful()) Log::channel('express24')->info($res.'. Category name: {category}', ['category' => $category->title['ru']]);
                else $successSentCategoryIds[] = $category->integration_id;

                if (isset($res->json()['id'])) $category->update([
                    'express24_id' => $res->json()['id']
                ]);
            } else {
                $res = Http::withToken($this->token)
                    ->patch($this->baseUrl.'/categories/'.$category->express24_id, [
                        'externalID' => $category->integration_id,
                        'name' => $category->title['ru'],
                        'isActive' => 0,
                        'sort' => 9997
                    ]);

                if (!$res->successful()) Log::channel('express24')->info($res.'. Category name: {category}', ['category' => $category->title['ru']]);
                else $successSentCategoryIds[] = $category->integration_id;
            }
        }

        return $successSentCategoryIds;
    }

    public function getProducts(array $categories)
    {

    }
}
