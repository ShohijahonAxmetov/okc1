<?php

namespace App\Services\Yandex\Classes;

class Size {

	public float $length;
	public float $width;
	public float $height;

	public function __construct (
		float $length,
		float $width,
		float $height
	) {
		$this->length = $length;
		$this->width = $width;
		$this->height = $height;
	}
}