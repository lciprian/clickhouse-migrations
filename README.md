# Clickhouse DB migrations for Laravel

ClickHouse is an open source column-oriented database management system capable of real time generation of analytical data reports using SQL queries.
Library is suitable for Laravel.

## Installing

```
composer require serkarn/laravel-clickhouse-migrations
```

## Usage

### Configure /config/database.php

Example for clickhouse and migrations settings:

```
...
    'clickhouse' => [
        'host' => env('CLICKHOUSE_HOST', 'localhost'),
        'port' => env('CLICKHOUSE_PORT', 8123),
        'username' => env('CLICKHOUSE_USER', 'default'),
        'password' => env('CLICKHOUSE_PASSWORD', ''),
        'options' => [
            'database' => env('CLICKHOUSE_DATABASE', 'default'),
            'timeout' => 1,
            'connectTimeOut' => 2,
        ],
    ],
    'clickhouse-migrations' => [
        'dir' => env('CLICKHOUSE_MIGRATION_DIR', '/database/clickhouse-migrations/'),
        'table' => env('CLICKHOUSE_MIGRATION_TABLE_NAME', 'migrations'),
        'template' => env('CLICKHOUSE_MIGRATION_TEMPLATE', '/config/clickhouse-migration.php.example'),
    ],
...
```

### Register provider

```
'providers' => [
    ...
    \Serkarn\ClickhouseMigrations\ClickhouseProvider::class,
    ...
],
```

## Usage

### Create new migration

```
php artisan clickhouse:migration:create {name}
```

### Up migrations

```
php artisan clickhouse:migrate
```

### Down last migration

```
php artisan clickhouse:migrate --down
```

## Built With

* https://github.com/smi2/phpClickHouse - PHP ClickHouse wrapper
