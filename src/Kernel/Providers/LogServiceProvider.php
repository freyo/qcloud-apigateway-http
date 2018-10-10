<?php

namespace Freyo\ApiGateway\Kernel\Providers;

use Freyo\ApiGateway\Kernel\Log\LogManager;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class LogServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple['logger'] = $pimple['log'] = function ($app) {
            $config = $this->formatLogConfig($app);

            if (!empty($config)) {
                $app['config']->merge($config);
            }

            return new LogManager($app);
        };
    }

    public function formatLogConfig($app)
    {
        if (empty($app['config']->get('log'))) {
            return [
                'log' => [
                    'default' => 'errorlog',
                    'channels' => [
                        'errorlog' => [
                            'driver' => 'errorlog',
                            'level' => 'debug',
                        ],
                    ],
                ],
            ];
        }

        // 4.0 version
        if (empty($app['config']->get('log.driver'))) {
            return [
                'log' => [
                    'default' => 'single',
                    'channels' => [
                        'single' => [
                            'driver' => 'single',
                            'path' => $app['config']->get('log.file') ?: \sys_get_temp_dir().'/logs/midas.log',
                            'level' => $app['config']->get('log.level', 'debug'),
                        ],
                    ],
                ],
            ];
        }

        $name = $app['config']->get('log.driver');

        return [
            'log' => [
                'default' => $name,
                'channels' => [
                    $name => $app['config']->get('log'),
                ],
            ],
        ];
    }
}
