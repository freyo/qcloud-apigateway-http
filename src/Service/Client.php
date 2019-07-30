<?php

namespace Freyo\ApiGateway\Service;

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
     * @param $serviceId
     *
     * @return array|\Freyo\ApiGateway\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \Freyo\ApiGateway\Kernel\Exceptions\InvalidConfigException
     */
    public function get($serviceId)
    {
        $params = [
            'Action' => 'DescribeService',
            'serviceId' => $serviceId,
        ];

        return $this->httpPost('index.php', $params);
    }
}