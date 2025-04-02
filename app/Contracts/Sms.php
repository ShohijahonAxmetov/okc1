<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface Sms {

	public function getUserData(): Collection;

	// templates

	public function sendMessage(string $phoneNumber, string $message): Collection;
}