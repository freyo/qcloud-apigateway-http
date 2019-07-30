<?php

namespace Freyo\ApiGateway\API;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}.
     */
    public function register(Container $app)
    {
        $app['api'] = function ($app) {
            return new Client($app);
        };
    }
}