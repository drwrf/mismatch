<?php

namespace Mismatch\ORM;

use Doctrine\DBAL\DriverManager;

class Connector
{
    /**
     * @var  array  A pool of connections, shared across all models.
     */
    private static $pool = [];

    /**
     * Returns a connection based on the configuration passed.
     *
     * If a connection has already been made using the configuration
     * then that same instance will be returned.
     *
     * @param  Metadata
     */
    public static function connect(array $config)
    {
        // We use the configuration to determine if a connection is unique,
        // which allows sharing connections across models.
        ksort($config);
        $key = json_encode($config);

        if (empty(self::$pool[$key])) {
            self::$pool[$key] = DriverManager::getConnection($config);
        }

        return self::$pool[$key];
    }

    /**
     * Resets the connection pool.
     */
    public static function reset()
    {
        self::$pool = [];
    }
}
