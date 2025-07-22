<?php

namespace App\Services\Yandex\Classes\Eats;

class BrandItem {

	protected Barcode $barcode;
	protected ItemDescription $description;
	protected string $id;
	protected array $brandImages;
	protected bool $isCatchWeight;
	protected Measure $measure;
	protected string $name;
	protected string $vendorCode;
	protected string $categoryId;

	public function __construct (
		Barcode $barcode,
		ItemDescription $description,
		string $id,
		array $brandImages,
		bool $isCatchWeight,
		Measure $measure,
		string $name,
		string $vendorCode,
		string $categoryId
	) {
		$this->barcode = $barcode;
		$this->description = $description;
		$this->id = $id;
		$this->brandImages = $brandImages;
		$this->isCatchWeight = $isCatchWeight;
		$this->measure = $measure;
		$this->name = $name;
		$this->vendorCode = $vendorCode;
		$this->categoryId = $categoryId;
	}

	public function toArray(): array
	{
		return [
			'barcode' => $this->barcode->toArray(),
			'description' => $this->description->toArray(),
			'id' => $this->id,
			'brandImages' => $this->brandImages,
			'isCatchWeight' => $this->isCatchWeight,
			'measure' => $this->measure->toArray(),
			'name' => $this->name,
			'vendorCode' => $this->vendorCode,
			'categoryId' => $this->categoryId,
		];
	}
}