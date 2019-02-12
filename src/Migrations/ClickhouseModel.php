<?php

namespace Serkarn\ClickhouseMigrations\Migrations;

class ClickhouseModel
{
    
    /**
     *
     * @var string
     */
    protected $tableName = 'migrations';
    
    /**
     * 
     * @param string $tableName
     */
    public function __construct(string $tableName = null)
    {
        !is_null($tableName) && $this->tableName = $tableName;
    }
    
    /**
     * 
     * Creating migration table if not exists
     */
    public function createMigrationTable()
    {
        $this->getClient()->write('
            CREATE TABLE IF NOT EXISTS ' . $this->tableName . ' (
                version String,
                apply_time DateTime DEFAULT NOW()
            ) ENGINE = ReplacingMergeTree()
                ORDER BY
                    (version)
        ');
    }
    
    /**
     * 
     * @return string|null
     */
    public function getLastAppliedMigration()
    {
        return $this->getClient()->select('
            SELECT
                version
            FROM
                ' . $this->tableName . ' m
            ORDER BY
                apply_time DESC
            LIMIT
                1
        ')->fetchOne('version');
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function getAppliedMigrations(): \Illuminate\Support\Collection
    {
        return $this->getClient()->selectAll('
            SELECT
                m.*
            FROM
                ' . $this->tableName . ' m
        ');
    }
    
    /**
     * 
     * @param string $version
     * @return \ClickHouseDB\Statement
     */
    public function addMigration(string $version): \ClickHouseDB\Statement
    {
        return $this->getClient()->insert($this->tableName, [
            [
                $version,
            ],
        ], [
            'version',
        ]);
    }
    
    /**
     * 
     * @param string $version
     * @return \ClickHouseDB\Statement
     */
    public function removeMigration(string $version): \ClickHouseDB\Statement
    {
        return $this->getClient()->delete($this->tableName, [
            [
                'version',
                '=' ,
                $version,
            ],
        ]);
    }

    /**
     * 
     * @return \ClickHouseDB\Client
     */
    protected function getClient(): \ClickHouseDB\Client
    {
        return \Serkarn\ClickhouseMigrations\Clickhouse::client();
    }
    
}
