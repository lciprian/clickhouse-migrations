<?php

namespace Serkarn\ClickhouseMigrations;

class Clickhouse implements ClickhouseInterface
{
    
    /**
     *
     * @var \ClickHouseDB\Client
     */
    private static $client;

    /**
     * 
     * @return \ClickHouseDB\Client
     */
    public static function client(): \ClickHouseDB\Client
    {
        if (is_null(self::$client)) {
            $config = app()->make('config')->get('database.clickhouse', []);
            $options = [];
            if (isset($config['options'])) {
                $options = $config['options'];
                unset($config['options']);
            }
            self::$client = new Client($config);
            foreach ($options as $option => $value) {
                if (method_exists(self::$client, $option)) {
                    $method = $option;
                } elseif (method_exists(self::$client, 'set' . ucwords($option))) {
                    $method = 'set' . ucwords($option);
                } else {
                    throw new \RuntimeException('Unknown clickhouse db option "' . $option. '" ');
                }
                self::$client->$method($value);
            }
        }
        return self::$client;
    }
    
    /**
     * 
     * Close constructor
     */
    public function __construct()
    {
        
    }
    
    /**
     * 
     * Close clone
     */
    public function __clone()
    {
        
    }
    
}
