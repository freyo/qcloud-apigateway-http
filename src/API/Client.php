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
            'serviceId' => $serviceId,
            'apiId' => $apiId,
        ];

        return $this->httpPost('index.php', $params);
    }

    /**
     * @param $apiId
     * @param $serviceId
     *
     * @return array|\Freyo\ApiGateway\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \Freyo\ApiGateway\Kernel\Exceptions\InvalidConfigException
     */
    public function delete($apiId, $serviceId)
    {
        $params = [
            'Action' => 'DeleteApi',
            'serviceId' => $serviceId,
            'apiId' => $apiId,
        ];

        return $this->httpPost('index.php', $params);
    }

    /**
     * @param $serviceId
     * @param $attributes
     *
     * @return array|\Freyo\ApiGateway\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \Freyo\ApiGateway\Kernel\Exceptions\InvalidConfigException
     */
    public function create($serviceId, array $attributes)
    {
        $params = [
            'Action' => 'CreateApi',
            'serviceId' => $serviceId,
        ];

        return $this->httpPost('index.php', $params + $attributes);
    }

    /**
     * @param $apiId
     * @param $serviceId
     * @param $attributes
     *
     * @return array|\Freyo\ApiGateway\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \Freyo\ApiGateway\Kernel\Exceptions\InvalidConfigException
     */
    public function update($apiId, $serviceId, array $attributes)
    {
        $params = [
            'Action' => 'ModifyApi',
            'serviceId' => $serviceId,
            'apiId' => $apiId,
        ];

        return $this->httpPost('index.php', $params + $attributes);
    }
}