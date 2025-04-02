<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramBot {

	protected string $token;
	protected string $baseUrl;
	protected string $testToken;
	protected string $testBaseUrl;

	function __construct()
	{
		$this->token = config('telegram_bot.token');
		$this->baseUrl = 'https://api.telegram.org/bot'.$this->token;
		$this->testToken = config('telegram_bot.test_bot');
		$this->testBaseUrl = 'https://api.telegram.org/bot'.$this->testToken;
	}

	public function send(string $userTelegramId, string $text, array $files = [], bool $isTest = true)
	{
		if (isset($files[0])) $res = $this->sendMediaGroup($userTelegramId, $files, $text, $isTest);
		else $res = $this->sendMessage($userTelegramId, $text, $isTest);

		return $res;
	}

	public function sendMessage(string $userTelegramId, string $text, bool $isTest = true)
	{
		$baseUrl = $isTest ? $this->testBaseUrl : $this->baseUrl;

		$res = Http::get($baseUrl.'/sendMessage', [
			'chat_id' => $userTelegramId, //5839440880, 449576810
			'text' => $text,
			'parse_mode' => 'Markdown'
		]);

		return $res->collect();
	}

	public function sendMediaGroup(string $userTelegramId, array $photos, string $caption, bool $isTest = true)
	{
		$media = [];
		$counter = 0;
		foreach($photos as $photo) {

            if ($counter === 0) $media[] = [
				'type' => 'photo',
				'media' => $photo,
				'caption' => $caption,
				'parse_mode' => 'Markdown',
			];
			else $media[] = [
				'type' => 'photo',
				'media' => $photo,
				'parse_mode' => 'Markdown',
			];

			$counter ++;
		}

		$baseUrl = $isTest ? $this->testBaseUrl : $this->baseUrl;

		$res = Http::get($baseUrl.'/sendMediaGroup', [
			'chat_id' => $userTelegramId,
			'media' => json_encode($media),
		]);

		return $res->collect();
	}

}