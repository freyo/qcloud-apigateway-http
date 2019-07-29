<?php

namespace Freyo\ApiGateway\Kernel\Traits;

trait WithFingerprint
{
    /**
     * @param array $params
     *
     * @return string
     */
    protected function fingerprint(array $params = [])
    {
        ksort($params);

        return md5(http_build_query(array_map(function ($item) {
            return md5($item);
        }, $params)));
    }
}