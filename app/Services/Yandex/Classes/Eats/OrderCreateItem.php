<?php

namespace App\Services\Yandex\Classes\Eats;

class OrderCreateItem {

	protected string $id;
	protected float $price;
	protected float $quantity;
	protected $name;
	protected $originPrice;

	public function __construct (string $id, float $price, float $quantity, $name = null, $originPrice)
	{
		$this->id = $id;
		$this->price = $price;
		$this->quantity = $quantity;
		$this->name = $name;
		$this->originPrice = $originPrice;
	}

	public function toArray(): array
	{
		return $this->originPrice ? [
			'id' => $this->id,
			'price' => $this->price,
			'quantity' => $this->quantity,
			'name' => $this->name,
			'origin_price' => $this->originPrice,
		] : [
			'id' => $this->id,
			'price' => $this->price,
			'quantity' => $this->quantity,
			'name' => $this->name,
		];
	}
}