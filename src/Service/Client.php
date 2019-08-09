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

    /**
     * @param $serviceId
     *
     * @return array|\Freyo\ApiGateway\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \Freyo\ApiGateway\Kernel\Exceptions\InvalidConfigException
     */
    public function delete($serviceId)
    {
        $params = [
            'Action' => 'DeleteService',
            'serviceId' => $serviceId,
        ];

        return $this->httpPost('index.php', $params);
    }

    /**
     * @param $serviceId
     * @param $environmentName
     * @param $releaseDesc
     *
     * @return array|\Freyo\ApiGateway\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \Freyo\ApiGateway\Kernel\Exceptions\InvalidConfigException
     */
    public function release($serviceId, $environmentName, $releaseDesc)
    {
        $params = [
            'Action' => 'ReleaseService',
            'serviceId' => $serviceId,
            'environmentName' => $environmentName,
            'releaseDesc' => $releaseDesc,
        ];

        return $this->httpPost('index.php', $params);
    }

    /**
     * @param $protocol
     * @param $serviceName
     * @param $serviceDesc
     *
     * @return array|\Freyo\ApiGateway\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \Freyo\ApiGateway\Kernel\Exceptions\InvalidConfigException
     */
    public function create($protocol, $serviceName = null, $serviceDesc = null)
    {
        $params = [
            'Action' => 'CreateService',
            'protocol' => $protocol,
            'serviceName' => $serviceName,
            'serviceDesc' => $serviceDesc,
        ];

        return $this->httpPost('index.php', $params);
    }

    /**
     * @param $serviceId
     * @param $protocol
     * @param $serviceName
     * @param $serviceDesc
     *
     * @return array|\Freyo\ApiGateway\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \Freyo\ApiGateway\Kernel\Exceptions\InvalidConfigException
     */
    public function update($serviceId, $protocol = null, $serviceName = null, $serviceDesc = null)
    {
        $params = [
            'Action' => 'ModifyService',
            'serviceId' => $serviceId,
            'protocol' => $protocol,
            'serviceName' => $serviceName,
            'serviceDesc' => $serviceDesc,
        ];

        return $this->httpPost('index.php', $params);
    }
}