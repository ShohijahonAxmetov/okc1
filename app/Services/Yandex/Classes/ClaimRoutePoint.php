<?php

namespace App\Services\Yandex\Classes;

use App\Services\Yandex\Classes\Contact;
use App\Services\Yandex\Classes\Address;

class ClaimRoutePoint {

	public int $pointId;
	public int $visitOrder = 1;
	public Contact $contact;
	public Address $address;
	public bool $skipConfirmation = false;
	public bool $leaveUnderDoor = false;
	public bool $meetOutside = false;
	public bool $noDoorCall = false;
	public string $type; // source, destination
	public ?int $externalOrderId = null;
	public bool $shouldNotifyOnOrderReadiness = false;

	public function __construct (
		int $pointId,
		int $visitOrder = 1,
		Contact $contact,
		Address $address,
		string $type, // source, destination
		?int $externalOrderId = null
	) {
		$this->pointId = $pointId;
		$this->visitOrder = $visitOrder;
		$this->contact = $contact;
		$this->address = $address;
		$this->type = $type;
		$this->externalOrderId = $externalOrderId;
	}
	
}