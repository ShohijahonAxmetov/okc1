<?php

namespace App\Http\Middleware;

use App\Services\Yandex\Classes\Eats\ErrorItem;
use Closure, DB;
use Illuminate\Http\Request;

class CheckYandexEatsTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $authHeader = $request->header('Authorization');

        // Проверка формата: Authorization: Bearer <token>
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            $error = new ErrorItem(401, 'Unauthorized');
            return response($error->toArray(), $error->getCode());
        }

        $token = substr($authHeader, 7); // Удалить 'Bearer '

        if (!in_array($token, $this->getValidTokens())) {
            return response()->json(['error' => 'Invalid token'], 403);
        }

        return $next($request);
    }

    private function getValidTokens(): array
    {
        $validTokens = DB::table('oauth_access_tokens')
            ->where('expires_at', '>', now())
            ->get()
            ->pluck('access_token')
            ->toArray();

        return $validTokens;
    }
}
