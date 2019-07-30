<?php

namespace Freyo\ApiGateway\UsagePlan;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}.
     */
    public function register(Container $app)
    {
        $app['usage_plan'] = function ($app) {
            return new Client($app);
        };
    }
}