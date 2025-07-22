<?php

namespace App\Services\Yandex\Classes\Eats;

class BrandImage {

	protected string $url;
	protected int $order;

	public function __construct (string $url, int $order)
	{
		$this->url = $url;
		$this->order = $order;
	}

	public function toArray(): array
	{
		return [
			'url' => $this->url,
			'order' => $this->order,
		];
	}
}