<?php

namespace Freyo\ApiGateway;

use Closure;
use Freyo\ApiGateway\Kernel\ServiceContainer;

class Application extends ServiceContainer
{

    /**
     * @var array
     */
    protected $providers = [
        Key\ServiceProvider::class,
    ];

}