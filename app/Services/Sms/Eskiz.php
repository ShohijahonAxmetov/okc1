<?php

namespace App\Services\Sms;

use App\Models\BlockedIpAddress;
use App\Contracts\Sms;
use Illuminate\Support\Facades\{Http, Cache};
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\{Log, RateLimiter};

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
		if (BlockedIpAddress::query()->where([['ip_address', '=', request()->ip()], ['status', '=', 1], ['blocked_at', '>', now()->subMonth()->format('Y-m-d H:i:s')]])->exists()) abort(429, 'Отправлен слишком много запросов! Обращайтесь администратору!');

		$limiter = RateLimiter::attempt(
			request()->ip(),
			$perMinute = 4,
			function() use ($phoneNumber, $message) {
				$token = $this->getToken();
				$token = $token->toArray()['data']['token'];

				$res = Http::withToken($token)->post($this->baseUrl.'/message/sms/send', [
					'mobile_phone' => $phoneNumber,
					'message' => $message
				]);

				if (!$res->ok()) Log::channel('sms')->info($res->json());
				else $this->telegramNotification('Отправлен смс на номер: <b>'.$phoneNumber.'</b> с текстом: <b>'.$message.'</b>');

				return $res;
			}
		);

		if ($limiter !== false) return $limiter->collect();
		else {
			if (Cache::has('potential_blocked_'.request()->ip())) {
				BlockedIpAddress::updateOrCreate([
					'ip_address' => request()->ip()
				], [
					'blocked_at' => date('Y-m-d H:i:s'),
					'status' => 1
				]);

				abort(429, 'Отправлен слишком много запросов! Обращайтесь администратору!');
			} else {
				Cache::put('potential_blocked_'.request()->ip(), 'potential_blocked_'.request()->ip(), now()->addDays(30));

				abort(429, 'Отправлен слишком много запросов!');
			}
		}
	}

	private function telegramNotification(string $text): void
	{
		Http::get('https://api.telegram.org/bot'.config('telegram_bot.dev_bot_token').'/sendMessage?parse_mode=HTML&chat_id=-1002617372536&text='.$text);
	}
}