<?php

namespace Freyo\ApiGateway\Key;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}.
     */
    public function register(Container $app)
    {
        $app['key'] = function ($app) {
            return new Client($app);
        };
    }
}