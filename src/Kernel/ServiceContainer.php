<?php

namespace Freyo\ApiGateway\Kernel;

use Freyo\ApiGateway\Kernel\Providers\BaseClientServiceProvider;
use Freyo\ApiGateway\Kernel\Providers\ConfigServiceProvider;
use Freyo\ApiGateway\Kernel\Providers\HttpClientServiceProvider;
use Freyo\ApiGateway\Kernel\Providers\LogServiceProvider;
use Freyo\ApiGateway\Kernel\Providers\RequestServiceProvider;
use Pimple\Container;

class ServiceContainer extends Container
{
     /**
     * @var array
     */
    protected $providers = [];

    /**
     * @var array
     */
    protected $defaultConfig = [];

    /**
     * @var array
     */
    protected $userConfig = [];

    /**
     * Constructor.
     *
     * @param array       $config
     * @param array       $prepends
     */
    public function __construct(array $config = [], array $prepends = [])
    {
        $this->registerProviders($this->getProviders());
        
        parent::__construct($prepends);
        
        $this->userConfig = $config;
    }

    /**
     * Return all providers.
     *
     * @return array
     */
    public function getProviders()
    {
        return array_merge([
            ConfigServiceProvider::class,
            LogServiceProvider::class,
            RequestServiceProvider::class,
            HttpClientServiceProvider::class,
            BaseClientServiceProvider::class,
        ], $this->providers);
    }
    
    /**
     * @param array $providers
     */
    public function registerProviders(array $providers)
    {
        foreach ($providers as $provider) {
            parent::register(new $provider());
        }
    }

    /**
     * Magic get access.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function __get($id)
    {
        return $this->offsetGet($id);
    }

    /**
     * Magic set access.
     *
     * @param string $id
     * @param mixed  $value
     */
    public function __set($id, $value)
    {
        $this->offsetSet($id, $value);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id ?: ($this->id = md5(json_encode($this->userConfig)));
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $base = [
            // http://docs.guzzlephp.org/en/stable/request-options.html
            'http' => [
                'timeout' => 5.0,
            ],
        ];

        return array_replace_recursive($base, $this->defaultConfig, $this->userConfig);
    }

    /**
     * @return string
     */
    public function getSecretKey()
    {
        return $this->config->get('secret_key');
    }

    /**
     * @return string
     */
    public function getSecretId()
    {
        return $this->config->get('secret_id');
    }

    /**
     * @return bool
     */
    public function needAuth()
    {
        return $this->getSecretId() && $this->getSecretKey();
    }
}
