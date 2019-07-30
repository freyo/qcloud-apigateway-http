<?php

namespace Freyo\ApiGateway\Kernel\Traits;

trait WithFingerprint
{
    /**
     * @param $method
     * @param $url
     * @param array $params
     *
     * @return string
     */
    protected function fingerprint($method, $url, array $params = [])
    {
        ksort($params);

        $srcStr = implode("\n", [
            $method,
            parse_url($url, PHP_URL_HOST) . parse_url($url, PHP_URL_PATH),
            json_encode($params, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        ]);

        return hash('sha256', $srcStr);
    }
}