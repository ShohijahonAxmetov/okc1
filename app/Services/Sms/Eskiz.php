<?php

namespace App\Services\Sms;

use App\Contracts\Sms;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class Eskiz implements Sms {

	protected string $email;
	protected string $password;
	protected string $from;
	protected string $callbackUrl;
	protected string $baseUrl;

	public function __construct()
	{
		$this->email = config('sms.eskiz.email');
		$this->password = config('sms.eskiz.password');
		$this->from = config('sms.eskiz.from');
		$this->callbackUrl = config('sms.eskiz.callback_url');
		$this->baseUrl = 'https://notify.eskiz.uz/api';
	}

	public function getToken()
	{
		$res = Http::post($this->baseUrl.'/auth/login', [
			'email' => $this->email,
			'password' => $this->password
		]);

		if (!$res->ok()) Log::channel('sms')->info($res->json());

		return $res->collect();
	}

	public function updateToken()
	{

	}

	public function getUserData(): Collection
	{

	}

	public function sendMessage(string $phoneNumber, string $message): Collection
	{
		$token = $this->getToken();
		// return $token;
		// Log::channel('sms')->info($token->toArray());
		$token = $token->toArray()['data']['token'];

		$res = Http::withToken($token)->post($this->baseUrl.'/message/sms/send', [
			'mobile_phone' => $phoneNumber,
			'message' => $message
		]);

		if (!$res->ok()) Log::channel('sms')->info($res->json());

		return $res->collect();
	}

}