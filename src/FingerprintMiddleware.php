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
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $authorizations = $this->authorizations($request->header('authorization'));
        $headers = preg_split('/\s+/', $authorizations['headers'] ?? []);
        if (!in_array('fingerprint', $headers)) {
            return response()->json([
                'message' => 'HMAC signature missing fingerprint header',
            ], 401);
        }

        $fingerprint = $this->fingerprint($request->method(), $request->url(), $request->all());
        if ($fingerprint !== $request->header('fingerprint')) {
            return response()->json([
                'message' => 'fingerprint does not match',
            ], 401);
        }

        return $next($request);
    }

    /**
     * @param string $authorization
     *
     * @return array
     */
    protected function authorizations($authorization)
    {
        $authorization = preg_replace('/^hmac/i', '', $authorization);
        $params = array_map('trim', explode(',', $authorization));

        $params = array_map(function ($item) {
            parse_str($item, $parsed);
            return array_map(function ($value) {
                return trim($value, '"\'');
            }, $parsed);
        }, $params);

        $result = [];
        foreach ($params as $param) {
            $result = array_merge($result, $param);
        }

        return $result;
    }
}