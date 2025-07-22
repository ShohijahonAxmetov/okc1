<?php

namespace App\Services\Yandex\Classes\Eats;

class YandexOrderCreate {

	protected string $comment;
	protected YandexDeliveryInfo $deliveryInfo;
	protected string $discriminator;
	protected string $eatsId;
	protected array $items;
	protected YandexOrderPaymentInfo $paymentInfo;
	protected string $brand;
	protected string $platform;
	protected string $restaurantId;

	public function __construct (
		string $comment,
		YandexDeliveryInfo $deliveryInfo,
		string $discriminator,
		string $eatsId,
		array $items,
		YandexOrderPaymentInfo $paymentInfo,
		string $brand,
		string $platform,
		string $restaurantId
	) {
		$this->comment = $comment;
		$this->deliveryInfo = $deliveryInfo;
		$this->discriminator = $discriminator;
		$this->eatsId = $eatsId;
		$this->items = $items;
		$this->paymentInfo = $paymentInfo;
		$this->brand = $brand;
		$this->platform = $platform;
		$this->restaurantId = $restaurantId;
	}

	public function toArray(): array
	{
		return [
			'comment' => $this->comment,
			'delivery_info' => $this->deliveryInfo->toArray(),
			'discriminator' => $this->discriminator,
			'eats_id' => $this->eatsId,
			'items' => $this->items,
			'payment_info' => $this->paymentInfo->toArray(),
			'brand' => $this->brand,
			'platform' => $this->platform,
			'restaurant_id' => $this->restaurantId,
		];
	}
}