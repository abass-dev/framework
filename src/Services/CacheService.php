<?php

namespace Bow\Services;

use Bow\Http\Cache;
use Bow\Config\Config;
use Bow\Application\Service as BowService;

class CacheService extends BowService
{
    /**
     * Configuration du service
     *
     * @param Config $config
     * @return void
     * @throws
     */
    public function make(Config $config)
    {
        $this->app->capsule(Cache::class, function () use ($config) {
            Cache::confirgure($config['resource.cache']);

            return Cache::class;
        });
    }

    /**
     * Démarrage du service
     *
     * @return void
     * @throws
     */
    public function start()
    {
        $this->app->capsule(Cache::class);
    }
}
