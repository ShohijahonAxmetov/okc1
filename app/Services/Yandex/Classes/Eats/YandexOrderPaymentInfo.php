<?php

namespace App\Services\Yandex\Classes\Eats;

class YandexOrderPaymentInfo {

	protected float $itemsCost;
	protected string $paymentType;

	public function __construct (float $itemsCost, string $paymentType)
	{
		$this->itemsCost = $itemsCost;
		$this->paymentType = $paymentType;
	}

	public function toArray(): array
	{
		return [
			'itemsCost' => $this->itemsCost,
			'paymentType' => $this->paymentType,
		];
	}
}