<?php

namespace Serkarn\ClickhouseMigrations\Migrations;

abstract class BaseMigration implements MigrationInterface
{
    
    /**
     * 
     * @return \ClickHouseDB\Client
     */
    protected function getClient(): \ClickHouseDB\Client
    {
        return \Serkarn\ClickhouseMigrations\Clickhouse::client();
    }
    
}
