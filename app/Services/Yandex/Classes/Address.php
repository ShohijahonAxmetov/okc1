<?php

namespace App\Services\Yandex\Classes;

class Address {

	public string $fullname;
	public string $shortname;
	public array $coordinates; // [dolgota, shirota]
	public string $country = 'Узбекистан';
	public string $city = 'Ташкент';
	public string $comment;

	public function __construct (
		string $fullname,
		string $shortname,
		array $coordinates, // [dolgota, shirota]
		string $comment
	) {
		$this->fullname = $fullname;
		$this->shortname = $shortname;
		$this->coordinates = $coordinates;
		$this->comment = $comment;
	}
}