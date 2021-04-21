<?php

namespace Bow\Container;

use Bow\Container\Action;
use Bow\Configuration\Configuration;
use Bow\Configuration\Loader;

class ContainerConfiguration extends Configuration
{
    /**
     * @var array
     */
    private $middlewares = [
        'trim' => \Bow\Middleware\TrimMiddleware::class,
    ];
    
    /**
     * @inheritdoc
     */
    public function create(Loader $config)
    {
        $this->container->bind('actionner', function () use ($config) {
            $middlewares = array_merge($config->getMiddlewares(), $this->middlewares);

            return Action::configure($config->namespaces(), $middlewares);
        });
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->container->make('actionner');
    }
}
