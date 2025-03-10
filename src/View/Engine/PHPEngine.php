<?php

namespace Bow\View\Engine;

use Bow\Configuration\Loader;
use Bow\View\EngineAbstract;

class PHPEngine extends EngineAbstract
{
    /**
     * The engine name
     *
     * @var string
     */
    protected string $name = 'php';

    /**
     * PHPEngine constructor.
     *
     * @param array $config
     *
     * @return void
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function render(string $filename, array $data = []): string
    {
        $hash_filename = $filename;

        $filename = $this->checkParseFile($filename);

        if ($this->config['path'] !== null) {
            $filename = $this->config['path'] . '/' . $filename;
        }

        $cache_hash_filename = '_PHP_'.md5($hash_filename).'.php';

        $cache_hash_filename = $this->config['cache'].'/'.$cache_hash_filename;

        extract($data);

        if (file_exists($cache_hash_filename)) {
            if (filemtime($cache_hash_filename) >= fileatime($filename)) {
                ob_start();

                require $cache_hash_filename;

                return ob_get_clean();
            }
        }

        ob_start();

        $content = file_get_contents($filename);

        // Save to cache
        file_put_contents(
            $cache_hash_filename,
            $content
        );

        require $cache_hash_filename;

        return ob_get_clean();
    }
}
