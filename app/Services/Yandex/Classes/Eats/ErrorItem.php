<?php

namespace App\Services\Yandex\Classes\Eats;

class ErrorItem {

	protected int $code;
	protected string $description;

	public function __construct (int $code, string $description)
	{
		$this->code = $code;
		$this->description = $description;
	}

	public function toArray(): array
	{
		return [
			'code' => $this->code,
			'description' => $this->description
		];
	}

	public function getCode(): int
	{
		return $this->code;
	}
}