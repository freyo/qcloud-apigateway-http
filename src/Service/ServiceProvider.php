<?php

namespace Freyo\ApiGateway\Service;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}.
     */
    public function register(Container $app)
    {
        $app['service'] = function ($app) {
            return new Client($app);
        };
    }
}