<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
class apiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedIps = [
            //'192.168.43.213',//the admin
            '192.168.43.62'//the esp32
        ];
        $clientIp = $request->ip();
        if (!in_array($clientIp, $allowedIps)) {
            return response()->json(['error' => 'Unauthorized IP address'], 402);
        }
        return $next($request);
    }
}
