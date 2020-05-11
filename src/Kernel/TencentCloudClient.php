<?php

namespace Freyo\ApiGateway\Kernel;

class TencentCloudClient extends BaseClient
{
    /**
     * Make a API request.
     *
     * @param string $url
     * @param string $method
     * @param array $options
     * @param bool $returnRaw
     *
     * @return \Psr\Http\Message\ResponseInterface|\Freyo\ApiGateway\Kernel\Support\Collection|array|object|string
     * @throws Exceptions\InvalidConfigException
     */
    public function request($url, $method = 'GET', array $options = [], $returnRaw = false)
    {
        if (empty($this->middlewares)) {
            $this->registerHttpMiddlewares();
        }

        $response = $this->performRequest($url, $method, $options);

        return $returnRaw ? $response : $this->castResponseToType($response, $this->app->config->get('response_type'));
    }

    /**
     * GET request.
     *
     * @param string $url
     * @param array $query
     *
     * @return \Psr\Http\Message\ResponseInterface|\Freyo\ApiGateway\Kernel\Support\Collection|array|object|string
     * @throws Exceptions\InvalidConfigException
     */
    public function httpGet($url, array $query = [])
    {
        $query = array_merge($this->getCommonParameters(), $query);

        $query['Signature'] = $this->signature(
            'GET', $this->baseUri . $url, $query
        );

        return $this->request($url, 'GET', ['query' => $query]);
    }

    /**
     * POST request.
     *
     * @param string $url
     * @param array $data
     *
     * @return \Psr\Http\Message\ResponseInterface|\Freyo\ApiGateway\Kernel\Support\Collection|array|object|string
     * @throws Exceptions\InvalidConfigException
     */
    public function httpPost($url, array $data = [])
    {
        $data = array_merge($this->getCommonParameters(), $data);

        $data['Signature'] = $this->signature(
            'POST', $this->baseUri . $url, $data
        );

        return $this->request($url, 'POST', ['form_params' => $data]);
    }

    /**
     * @param $method
     * @param $url
     * @param array $params
     *
     * @return string
     */
    protected function signature($method, $url, array $params = [])
    {
        ksort($params);

        $srcStr = sprintf('%s%s%s?%s',
            $method,
            parse_url($url, PHP_URL_HOST),
            parse_url($url, PHP_URL_PATH),
            $this->canonicalize($params)
        );

        return base64_encode(
            hash_hmac('sha1', $srcStr, $this->app->getSecretKey(), true)
        );
    }

    /**
     * @return array
     */
    protected function getCommonParameters()
    {
        return [
            'Timestamp' => time(),
            'Nonce'     => rand(1, 65535),
            'SecretId'  => $this->app->getSecretId(),
            'Region'    => $this->app->getRegion(),
        ];
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

    /**
     * @param array $input
     * @param string $keyPrefix
     *
     * @return array
     */
    protected function canonicalizeParameters(array $input, $keyPrefix = '')
    {
        $resource = [];

        foreach ($input as $key => $value) {

            $key = $keyPrefix ? $keyPrefix . '.' . $key : $key;

            $resource = array_merge($resource, is_array($value)
                ? $this->canonicalizeParameters($value, $key)
                : [$key => $value]);
        }

        return $resource;
    }
}