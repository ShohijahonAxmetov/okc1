<?php

namespace App\Services\Yandex\Classes\Eats;

class Measure {

	protected string $unit;
	protected $value;

	public function __construct (string $unit, $value)
	{
		$this->unit = $unit;
		$this->value = $value;
	}

	public function toArray(): array
	{
		return [
			'unit' => $this->unit,
			'value' => $this->value,
		];
	}
}