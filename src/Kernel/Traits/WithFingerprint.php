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
            $this->canonicalize($params)
        );

        return hash('sha256', $srcStr);
    }

    /**
     * @param array $input
     * @param string $keyPrefix
     *
     * @return string
     */
    protected function canonicalize(array $input, $keyPrefix = '')
    {
        $resource = [];

        foreach ($input as $key => $value) {

            $key = $keyPrefix ? $keyPrefix . '.' . $key : $key;

            $resource[] = is_array($value)
                ? $this->canonicalize($value, $key)
                : $key . '=' . $value;
        }

        return implode('&', $resource);
    }
}