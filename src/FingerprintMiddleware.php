<?php

namespace Freyo\ApiGateway;

use Closure;
use Freyo\ApiGateway\Kernel\Traits\WithFingerprint;

class FingerprintMiddleware
{
    use WithFingerprint;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->fingerprint($request->all()) !== $request->header('fingerprint')) {
            return response()->json([
                'message' => 'fingerprint cannot be verified, a validate fingerprint header is required',
            ], 401);
        }

        return $next($request);
    }
}