<?php

namespace App\Services\Google;

use Illuminate\Support\Facades\Http;

class Translate {

	protected $apiKey;
	protected $baseUrl;

	public function __construct()
	{
		$this->apiKey = config('google_translate.api_key');
		$this->baseUrl = config('google_translate.base_url');
	}

	public function ru2uz(string $text): array
	{
		$url = $this->baseUrl.'?key='.$this->apiKey;
		$body = [
			'q' => $text,
			'source' => 'ru',
			'target' => 'uz',
			'format' => 'text'
		];

		$res = Http::post($url, $body);

		if (!$res->ok()) return ['success' => false, 'res' => $res->json()];
		else return [
			'success' => true,
			'res' => $res->json()['data']['translations'][0]['translatedText']
		];
	}

	public function uz2ru(string $text): array
	{
		$url = $this->baseUrl.'?key='.$this->apiKey;
		$body = [
			'q' => $text,
			'source' => 'uz',
			'target' => 'ru',
			'format' => 'text'
		];

		$res = Http::post($url, $body);

		if (!$res->ok()) return ['success' => false, 'res' => $res->json()];
		else return [
			'success' => true,
			'res' => $res->json()['data']['translations'][0]['translatedText']
		];
	}
}