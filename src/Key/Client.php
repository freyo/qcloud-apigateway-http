<?php

namespace Freyo\ApiGateway\Key;

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
     * @param $secretId
     *
     * @return array|\Freyo\ApiGateway\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \Freyo\ApiGateway\Kernel\Exceptions\InvalidConfigException
     */
    public function get($secretId)
    {
        $params = [
            'Action' => 'DescribeApiKey',
            'secretId' => $secretId,
        ];

        return $this->httpPost('index.php', $params);
    }

    /**
     * @param $secretName
     * @param $secretId
     * @param $secretKey
     *
     * @return array|\Freyo\ApiGateway\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \Freyo\ApiGateway\Kernel\Exceptions\InvalidConfigException
     */
    public function create($secretName, $secretId = null, $secretKey = null)
    {
        $params = [
            'Action' => 'CreateApiKey',
            'secretName' => $secretName,
            'secretId' => $secretId,
            'secretKey' => $secretKey,
            'type' => isset($secretId, $secretKey) ? 'manunal' : 'auto',
        ];

        return $this->httpPost('index.php', $params);
    }

    /**
     * @param $secretId
     *
     * @return array|\Freyo\ApiGateway\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \Freyo\ApiGateway\Kernel\Exceptions\InvalidConfigException
     */
    public function delete($secretId)
    {
        $params = [
            'Action' => 'DeleteApiKey',
            'secretId' => $secretId,
        ];

        return $this->httpPost('index.php', $params);
    }

    /**
     * @param $secretId
     *
     * @return array|\Freyo\ApiGateway\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \Freyo\ApiGateway\Kernel\Exceptions\InvalidConfigException
     */
    public function enable($secretId)
    {
        $params = [
            'Action' => 'EnableApiKey',
            'secretId' => $secretId,
        ];

        return $this->httpPost('index.php', $params);
    }

    /**
     * @param $secretId
     *
     * @return array|\Freyo\ApiGateway\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \Freyo\ApiGateway\Kernel\Exceptions\InvalidConfigException
     */
    public function disable($secretId)
    {
        $params = [
            'Action' => 'DisableApiKey',
            'secretId' => $secretId,
        ];

        return $this->httpPost('index.php', $params);
    }

    /**
     * @param $secretId
     * @param $secretKey
     *
     * @return array|\Freyo\ApiGateway\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \Freyo\ApiGateway\Kernel\Exceptions\InvalidConfigException
     */
    public function update($secretId, $secretKey = null)
    {
        $params = [
            'Action' => 'UpdateApiKey',
            'secretId' => $secretId,
            'secretKey' => $secretKey,
        ];

        return $this->httpPost('index.php', $params);
    }
}