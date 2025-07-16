<?php

namespace App\Services\Yandex\Classes;

class Claim {

	public string $id;
	public string $status;
	public int $version;
	public string $userRequestRevision;
	public string $createdTs;
	public string $updatedTs;
	public integer $revision;

	public function __construct (
		string $id,
		string $status,
		int $version,
		string $userRequestRevision,
		string $createdTs,
		string $updatedTs,
		integer $revision
	) {
		$this->id = $id;
		$this->status = $status;
		$this->version = $version;
		$this->userRequestRevision = $userRequestRevision;
		$this->createdTs = $createdTs;
		$this->updatedTs = $updatedTs;
		$this->revision = $revision;
	}
	
}