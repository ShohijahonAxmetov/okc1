<?php

namespace App\Services\Yandex\Classes;

use App\Services\Yandex\Classes\Size;

class ClaimItem {

	public string $extraId;
	public int $pickupPoint;
	public int $dropoffPoint;
	public string $title;
	public Size $size;
	public float $weight;
	public string $costCurrency = 'UZS';
	public string $costValue;
	public int $quantity;

	public function __construct (
		string $extraId,
		int $pickupPoint,
		int $dropoffPoint,
		string $title,
		Size $size,
		float $weight,
		string $costValue,
		int $quantity
	) {
		$this->extraId = $extraId;
		$this->pickupPoint = $pickupPoint;
		$this->dropoffPoint = $dropoffPoint;
		$this->title = $title;
		$this->size = $size;
		$this->weight = $weight;
		$this->costValue = $costValue;
		$this->quantity = $quantity;
	}
	
}