<?php

namespace App\Services\Yandex\Classes\Eats;

class PriceItem {

	protected string $id;
	protected float $price;
	protected int $vat;
	protected $oldPrice;

	public function __construct (string $id, float $price, int $vat = -1, $oldPrice = null)
	{
		$this->id = $id;
		$this->price = $price;
		$this->vat = $vat;
		$this->oldPrice = $oldPrice;
	}

	public function toArray()
	{
		return $this->oldPrice ? [
			'id' => $this->id,
			'price' => $this->price,
			'vat' => $this->vat,
			'oldPrice' => $this->oldPrice,
		] : [
			'id' => $this->id,
			'price' => $this->price,
			'vat' => $this->vat,
		];
	}
}