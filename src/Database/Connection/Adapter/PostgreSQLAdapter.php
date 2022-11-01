<?php

namespace Bow\Database\Connection\Adapter;

use Bow\Database\Connection\AbstractConnection;
use Bow\Support\Str;
use PDO;

class PostgreSQLAdapter extends AbstractConnection
{
    /**
     * The connexion nane
     *
     * @var string
     */
    protected ?string $name = 'postgresql';

    /**
     * Default PORT
     *
     * @var int
     */
    const PORT = 5432;

    /**
     * PostgreSQLAdapter constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        $this->connection();
    }

    /**
     * Make connexion
     *
     * @return void
     */
    public function connection()
    {
        // TODO...
    }
}
