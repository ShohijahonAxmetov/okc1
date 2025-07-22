<?php

namespace App\Services\Yandex\Classes\Eats;

class ItemDescription {

	protected $expiresIn;
	protected string $general;
	protected string $vendorName;

	public function __construct ($expiresIn, string $general, string $vendorName)
	{
		$this->expiresIn = $expiresIn;
		$this->general = $general;
		$this->vendorName = $vendorName;
	}

	public function toArray(): array
	{
		return [
			'expiresIn' => $this->expiresIn,
			'general' => $this->general,
			'vendorName' => $this->vendorName,
		];
	}
}