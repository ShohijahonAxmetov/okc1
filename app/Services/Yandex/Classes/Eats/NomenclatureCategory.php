<?php

namespace App\Services\Yandex\Classes\Eats;

class NomenclatureCategory {

	protected string $id;
	protected string $name;
	protected $parentId;

	public function __construct (string $id, string $name, $parentId)
	{
		$this->id = $id;
		$this->name = $name;
		$this->parentId = $parentId;
	}

	public function toArray(): array
	{
		return [
			'id' => $this->id,
			'name' => $this->name,
			'parentId' => $this->parentId,
		];
	}
}