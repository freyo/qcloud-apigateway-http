<?php

namespace Freyo\ApiGateway\API;

use Freyo\ApiGateway\Kernel\TencentCloudClient;

class Client extends TencentCloudClient
{
    /**
     * @return string
     */
    protected function getBaseUri()
    {
        return 'https://apigateway.api.qcloud.com/v2/';
    }

    /**
     * @param $apiId
     * @param $serviceId
     *
     * @return array|\Freyo\ApiGateway\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \Freyo\ApiGateway\Kernel\Exceptions\InvalidConfigException
     */
    public function get($apiId, $serviceId)
    {
        $params = [
            'Action' => 'DescribeApi',
            'serviceId' => $apiId,
            'apiId' => $serviceId,
        ];

        return $this->httpPost('index.php', $params);
    }
}