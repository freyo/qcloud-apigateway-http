<?php

namespace Freyo\ApiGateway\UsagePlan;

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
     * @param $usagePlanId
     *
     * @return array|\Freyo\ApiGateway\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \Freyo\ApiGateway\Kernel\Exceptions\InvalidConfigException
     */
    public function get($usagePlanId)
    {
        $params = [
            'Action' => 'DescribeUsagePlan',
            'usagePlanId' => $usagePlanId,
        ];

        return $this->httpPost('index.php', $params);
    }

    /**
     * @param $usagePlanId
     *
     * @return array|\Freyo\ApiGateway\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \Freyo\ApiGateway\Kernel\Exceptions\InvalidConfigException
     */
    public function delete($usagePlanId)
    {
        $params = [
            'Action' => 'DeleteUsagePlan',
            'usagePlanId' => $usagePlanId,
        ];

        return $this->httpPost('index.php', $params);
    }
}