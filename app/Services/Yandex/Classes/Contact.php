<?php

namespace App\Services\Yandex\Classes;

class Contact {

	public string $name;
	public string $phone;

	public function __construct (
		string $name,
		string $phone
		// string $phoneAdditionalCode,
		// string $email
	) {
		$this->name = $name;
		$this->phone = $phone;
	}
}