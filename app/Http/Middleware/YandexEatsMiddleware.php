<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;

class YandexEatsMiddleware
{
    protected $whitelist = [
        '5.45.192.0/18',
        '5.255.192.0/18',
        '37.9.64.0/18',
        '37.140.128.0/18',
        '77.88.0.0/18',
        '84.252.160.0/19',
        '87.250.224.0/19',
        '90.156.176.0/22',
        '93.158.128.0/18',
        '95.108.128.0/17',
        '141.8.128.0/18',
        '178.154.128.0/18',
        '213.180.192.0/19',
        '185.32.187.0/24',
        '82.215.102.255', // my ip
    ];
    
    public function handle(Request $request, Closure $next)
    {
        $clientIp = $request->ip();

        if (!IpUtils::checkIp($clientIp, $this->whitelist)) {
            abort(403, 'Доступ запрещён для IP: ' . $clientIp);
        }

        return $next($request);
    }
}
