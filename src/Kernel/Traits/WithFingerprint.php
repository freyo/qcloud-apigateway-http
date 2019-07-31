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

        $srcStr = sprintf('%s%s%s?%s',
            $method,
            parse_url($url, PHP_URL_HOST),
            parse_url($url, PHP_URL_PATH),
            urldecode(http_build_query($params, '', '&', PHP_QUERY_RFC3986))
        );

        return hash('sha256', $srcStr);
    }
}