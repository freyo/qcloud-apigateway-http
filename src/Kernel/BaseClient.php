<?php

namespace Freyo\ApiGateway\Kernel;

use Freyo\ApiGateway\Kernel\Http\Response;
use Freyo\ApiGateway\Kernel\ServiceContainer;
use Freyo\ApiGateway\Kernel\Traits\HasHttpRequests;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Psr\Http\Message\ResponseInterface;
use function Freyo\ApiGateway\Kernel\Support\generate_sign;

class BaseClient
{
    use HasHttpRequests {
        request as performRequest;
    }

    /**
     * @var \Freyo\ApiGateway\Kernel\ServiceContainer
     */
    protected $app;

    /**
     * @var string
     */
    protected $baseUri;

    /**
     * BaseClient constructor.
     *
     * @param \Freyo\ApiGateway\Kernel\ServiceContainer $app
     */
    public function __construct(ServiceContainer $app)
    {
        $this->app     = $app;
        $this->baseUri = $this->getBaseUri();

        $this->setHttpClient($this->app['http_client']);
    }

    /**
     * GET request.
     *
     * @param string $url
     * @param array  $query
     *
     * @return \Psr\Http\Message\ResponseInterface|\Freyo\ApiGateway\Kernel\Support\Collection|array|object|string
     *
     * @throws \Freyo\ApiGateway\Kernel\Exceptions\InvalidConfigException
     */
    public function httpGet($url, array $query = [])
    {
        return $this->request($url, 'GET', ['query' => $query]);
    }

    /**
     * POST request.
     *
     * @param string $url
     * @param array  $data
     *
     * @return \Psr\Http\Message\ResponseInterface|\Freyo\ApiGateway\Kernel\Support\Collection|array|object|string
     *
     * @throws \Freyo\ApiGateway\Kernel\Exceptions\InvalidConfigException
     */
    public function httpPost($url, array $data = [])
    {
        return $this->request($url, 'POST', ['form_params' => $data]);
    }

    /**
     * JSON request.
     *
     * @param string       $url
     * @param string|array $data
     * @param array        $query
     *
     * @return \Psr\Http\Message\ResponseInterface|\Freyo\ApiGateway\Kernel\Support\Collection|array|object|string
     *
     * @throws \Freyo\ApiGateway\Kernel\Exceptions\InvalidConfigException
     */
    public function httpPostJson($url, array $data = [], array $query = [])
    {
        return $this->request($url, 'POST', ['query' => $query, 'json' => $data]);
    }

    /**
     * Upload file.
     *
     * @param string $url
     * @param array  $files
     * @param array  $form
     * @param array  $query
     *
     * @return \Psr\Http\Message\ResponseInterface|\Freyo\ApiGateway\Kernel\Support\Collection|array|object|string
     *
     * @throws \Freyo\ApiGateway\Kernel\Exceptions\InvalidConfigException
     */
    public function httpUpload($url, array $files = [], array $form = [], array $query = [])
    {
        $multipart = [];

        foreach ($files as $name => $path) {
            $multipart[] = [
                'name' => $name,
                'contents' => fopen($path, 'r'),
            ];
        }

        foreach ($form as $name => $contents) {
            $multipart[] = compact('name', 'contents');
        }

        return $this->request($url, 'POST', ['query' => $query, 'multipart' => $multipart, 'connect_timeout' => 30, 'timeout' => 30, 'read_timeout' => 30]);
    }

    /**
     * Make a request and return raw response.
     *
     * @param string $url
     * @param string $method
     * @param array  $options
     *
     * @return ResponseInterface
     *
     * @throws \Freyo\ApiGateway\Kernel\Exceptions\InvalidConfigException
     */
    public function requestRaw($url, $method = 'GET', array $options = [])
    {
        return Response::buildFromPsrResponse($this->request($url, $method, $options, true));
    }

    /**
     * Make a API request.
     *
     * @param string $url
     * @param string $method
     * @param array  $options
     * @param bool   $returnRaw
     *
     * @return \Psr\Http\Message\ResponseInterface|\Freyo\ApiGateway\Kernel\Support\Collection|array|object|string
     *
     * @throws \Freyo\ApiGateway\Kernel\Exceptions\InvalidConfigException
     */
    public function request($url, $method = 'GET', array $options = [], $returnRaw = false)
    {
        if (empty($this->middlewares)) {
            $this->registerHttpMiddlewares();
        }

        $headers = isset($options['headers']) ? $options['headers'] : [];

        if ($this->app->needAuth()) {

            $headers = array_merge(
                ['Date' => gmdate("D, d M Y H:i:s T"), 'Source' => ''],
                $headers
            );

            $signature = generate_sign($headers, $this->app->getSecretKey());

            $authorization = sprintf(
                'hmac id="%s", algorithm="hmac-sha1", headers="%s", signature="%s"',
                $this->app->getSecretId(),
                implode(' ', array_map('strtolower', array_keys($headers))),
                $signature
            );

            $headers['Authorization'] = $authorization;

        }

        $options = array_merge($options, [
            'headers' => $headers
        ]);

        $response = $this->performRequest($url, $method, $options);

        return $returnRaw ? $response : $this->castResponseToType($response, $this->app->config->get('response_type'));
    }

    /**
     * Register Guzzle middlewares.
     */
    protected function registerHttpMiddlewares()
    {
        // log
        $this->pushMiddleware($this->logMiddleware(), 'log');
    }

    /**
     * Log the request.
     *
     * @return \Closure
     */
    protected function logMiddleware()
    {
        $formatter = new MessageFormatter($this->app['config']->get('http.log_template', MessageFormatter::DEBUG));

        return Middleware::log($this->app['logger'], $formatter);
    }

    /**
     * Extra request params.
     *
     * @return array
     */
    protected function prepends()
    {
        return [];
    }

    /**
     * @return string
     */
    protected function getBaseUri()
    {
        return $this->app['config']->get('http.base_uri');
    }

    /**
     * Wrapping an API endpoint.
     *
     * @param string $endpoint
     * @param string $prefix
     *
     * @return string
     */
    protected function wrap($endpoint, $prefix = '')
    {
        return implode('/', [$prefix, $endpoint]);
    }
}
