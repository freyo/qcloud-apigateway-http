<?php

namespace Freyo\ApiGateway;

use Freyo\ApiGateway\Kernel\ServiceContainer;

/**
 * @property Key\Client key
 * @property API\Client api
 * @property Service\Client service
 * @property UsagePlan\Client usagePlan
 *
 * Class Application
 * @package Freyo\ApiGateway
 */
class Application extends ServiceContainer
{

    /**
     * @var array
     */
    protected $providers = [
        Key\ServiceProvider::class,
        API\ServiceProvider::class,
        Service\ServiceProvider::class,
        UsagePlan\ServiceProvider::class,
    ];

}