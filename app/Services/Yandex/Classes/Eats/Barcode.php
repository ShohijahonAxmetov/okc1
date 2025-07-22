<?php

namespace App\Services\Yandex\Classes\Eats;

class Barcode {

	protected string $type;
	protected string $value;
	protected string $weightEncoding;

	public function __construct (string $type, string $value, string $weightEncoding)
	{
		$this->type = $type;
		$this->value = $value;
		$this->weightEncoding = $weightEncoding;
	}

	public function toArray(): array
	{
		return [
			'type' => $this->type,
			'value' => $this->value,
			'weightEncoding' => $this->weightEncoding,
		];
	}
}