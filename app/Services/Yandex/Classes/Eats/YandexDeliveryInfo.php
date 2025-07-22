<?php

namespace App\Services\Yandex\Classes\Eats;

class YandexDeliveryInfo {

	protected string $courierArrivementDate;
	protected $clientName;
	protected $isSlotDelivery;
	protected $phoneNumber;
	protected $realPhoneNumber;

	public function __construct (string $courierArrivementDate, $clientName, $isSlotDelivery, $phoneNumber, $realPhoneNumber)
	{
		$this->courierArrivementDate = $courierArrivementDate;
		$this->clientName = $clientName;
		$this->isSlotDelivery = $isSlotDelivery;
		$this->phoneNumber = $phoneNumber;
		$this->realPhoneNumber = $realPhoneNumber;
	}

	public function toArray(): array
	{
		return [
			'courierArrivementDate' => $this->courierArrivementDate,
			'clientName' => $this->clientName,
			'isSlotDelivery' => $this->isSlotDelivery,
			'phoneNumber' => $this->phoneNumber,
			'realPhoneNumber' => $this->realPhoneNumber,
		];
	}
}