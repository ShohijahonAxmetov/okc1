<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

trait Express24 {

	public function getToken()
	{
		return config('express24.token');
	}

	public function getBaeUrl()
	{
		return config('express24.base_url');
	}

    public function getAllCategories(bool $onlyActive = false, bool $addSubs = false, bool $onlyWithExternalId = false): array
    {
		$categories = $this->getCategories();

		if ($addSubs) {
			foreach ($categories as $category) {
				$categories = array_merge($categories, $this->getSubs($category['id']));
			}
		}

		if ($onlyActive) $categories = array_filter($categories, function ($item) {
				return $item['isActive'];
			});

		if ($onlyWithExternalId) $categories = array_filter($categories, function ($item) {
			return $item['externalID'];
		});

		return array_values($categories);
    }

    function getCategories(): array
    {
    	$res = Http::withToken($this->getToken())
				->get($this->getBaeUrl().'/categories');

		return $res->json();
    }

    function getSubs(int $categoryId): array
    {
    	$res = Http::withToken($this->getToken())
				->get($this->getBaeUrl().'/categories/'.$categoryId.'/subs');

		return $res->json();
    }

    function updateCategory(Request $request): bool
    {
    	$data = [
					'name' => $request->input('name'),
    				'sort' => $request->input('sort'),
    				'isActive' => intval($request->input('is_active')) ? true : false
				];

    	$res = Http::withToken($this->getToken())
				->patch($this->getBaeUrl().'/categories/'.intval($request->input('id')), $data);

		return $res->ok();
    }

    function getBranches(): array
    {
    	$res = Http::withToken($this->getToken())
				->get($this->getBaeUrl().'/branches');

		return $res->json();
    }

    function updateBranch(Request $request): bool
    {
    	$data = [
					'name' => $request->input('name'),
    				'isActive' => intval($request->input('is_active')) ? true : false
				];

    	$res = Http::withToken($this->getToken())
				->patch($this->getBaeUrl().'/branches/'.intval($request->input('id')), $data);

		return $res->ok();
    }

    function getProducts(int $categoryId): array
    {
    	$res = Http::withToken($this->getToken())
				->get($this->getBaeUrl().'/categories/'.$categoryId.'/products?isSub=1');

		return $res->json();
    }

    function updateProduct(Request $request): bool
    {
    	$data = json_decode($request->input('info'), true);
    	foreach ($data['attachedBranches'] as $key => $value) {
    		$data['attachedBranches'][$key]['isActive'] = intval($request->input('is_active')) ? true : false;
    	}
    	$data['fiscalization']['spicID'] = '0'.$data['fiscalization']['spicID'];

    	$res = Http::withToken($this->getToken())
				->patch($this->getBaeUrl().'/products/'.intval($request->input('id')), $data);

		return $res->ok();
    }
}