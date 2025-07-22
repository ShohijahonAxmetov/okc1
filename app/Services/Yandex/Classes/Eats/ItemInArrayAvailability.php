<?php

namespace App\Services\Yandex\Classes\Eats;

class ItemInArrayAvailability {

	protected string $id;
	protected float $stock;

	public function __construct (string $id, float $stock)
	{
		$this->id = $id;
		$this->stock = $stock;
	}

	public function toArray(): array
	{
		return [
			'id' => $this->id,
			'stock' => $this->stock,
		];
	}
}