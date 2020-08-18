<?php

namespace Freyo\ApiGateway\Kernel;

use Freyo\ApiGateway\Kernel\Http\Response;
use Freyo\ApiGateway\Kernel\Traits\HasHttpRequests;
use Freyo\ApiGateway\Kernel\Traits\WithFingerprint;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use function Freyo\ApiGateway\Kernel\Support\generate_sign;

class BaseClient
{
    use WithFingerprint, HasHttpRequests {
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
     * @param array $query
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
     * @param array $data
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
     * PUT request.
     *
     * @param string $url
     * @param array $data
     *
     * @return \Psr\Http\Message\ResponseInterface|\Freyo\ApiGateway\Kernel\Support\Collection|array|object|string
     *
     * @throws \Freyo\ApiGateway\Kernel\Exceptions\InvalidConfigException
     */
    public function httpPut($url, array $data = [])
    {
        return $this->request($url, 'PUT', ['form_params' => $data]);
    }

    /**
     * JSON request.
     *
     * @param string $url
     * @param string|array $data
     * @param array $query
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
     * JSON request.
     *
     * @param string $url
     * @param string|array $data
     * @param array $query
     *
     * @return \Psr\Http\Message\ResponseInterface|\Freyo\ApiGateway\Kernel\Support\Collection|array|object|string
     *
     * @throws \Freyo\ApiGateway\Kernel\Exceptions\InvalidConfigException
     */
    public function httpPutJson($url, array $data = [], array $query = [])
    {
        return $this->request($url, 'PUT', ['query' => $query, 'json' => $data]);
    }

    /**
     * Upload file.
     *
     * @param string $url
     * @param array $files
     * @param array $form
     * @param array $query
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

        return $this->request($url, 'POST', [
            'query' => $query,
            'multipart' => $multipart,
            'connect_timeout' => 30,
            'timeout' => 30,
            'read_timeout' => 30
        ]);
    }

    /**
     * Make a request and return raw response.
     *
     * @param string $url
     * @param string $method
     * @param array $options
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
     * @param array $options
     * @param bool $returnRaw
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

        $headers = array_merge(
            ['Date' => gmdate("D, d M Y H:i:s T"), 'Source' => $this->app->getSource()],
            $options['headers'] ?? []
        );

        if ($this->app->withFingerprint()) {
            $headers['Fingerprint'] = $this->fingerprint(
                $method,
                $this->baseUri . $url,
                ($options['json'] ?? []) + ($options['form_params'] ?? []) + ($options['query'] ?? [])
            );
        }

        if ($this->app->needAuth()) {
            $headers['Authorization'] = $this->authorization($headers);
        }

        $options = array_merge($options, ['headers' => $headers]);

        $response = $this->performRequest($url, $method, $options);

        return $returnRaw ? $response : $this->castResponseToType($response, $this->app->config->get('response_type'));
    }

    /**
     * Register Guzzle middlewares.
     */
    protected function registerHttpMiddlewares()
    {
        // jaeger
        if ($this->app['config']->has('jaeger')) {
            $this->pushMiddleware($this->jaegerMiddleware(), 'jaeger');
        }

        // tap
        if ($this->app['config']->has('tap')) {
            $this->pushMiddleware($this->tapMiddleware(), 'tap');
        }

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
     * Jaeger.
     *
     * @return \Closure
     */
    protected function jaegerMiddleware()
    {
        return Middleware::mapRequest(function (RequestInterface $request) {

            if ($headers = $this->app['config']->get('jaeger.headers', [])) {

                $headers = is_callable($headers) ? call_user_func($headers, $request) : $headers;

                foreach ($headers as $name => $value) {
                    $request = $request->withHeader($name, $value);
                }
            }

            return $request;
        });
    }

    /**
     * Middleware that invokes a callback before and after sending a request.
     *
     * @return \Closure
     */
    protected function tapMiddleware()
    {
        return Middleware::tap(
            is_callable($before = $this->app['config']->get('tap.before')) ? $before : null,
            is_callable($after = $this->app['config']->get('tap.after')) ? $after : null
        );
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

    /**
     * @param array $headers
     *
     * @return string
     */
    protected function authorization(array $headers)
    {
        $signature = generate_sign($headers, $this->app->getSecretKey());

        $authorization = sprintf(
            'hmac id="%s", algorithm="hmac-sha1", headers="%s", signature="%s"',
            $this->app->getSecretId(),
            implode(' ', array_map('strtolower', array_keys($headers))),
            $signature
        );

        return $authorization;
    }
}
