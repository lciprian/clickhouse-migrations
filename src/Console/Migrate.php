<?php

namespace Serkarn\ClickhouseMigrations\Console;

class Migrate extends \Illuminate\Console\Command
{
    
    /**
     *
     * @var string
     */
    protected $signature = 'clickhouse:migrate {--down}';
    
    /**
     *
     * @var string
     */
    protected $description = 'Clickhouse migrations';
    
    /**
     *
     * @var \Serkarn\ClickhouseMigrations\Migrations\MigrationService
     */
    protected $migrationService;

    /**
     * 
     * Class constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->migrationService = new \Serkarn\ClickhouseMigrations\Migrations\MigrationService();
    }

    /**
     * 
     * @return bool
     */
    public function handle(): bool
    {
        return $this->option('down') ? $this->down() : $this->up();
    }
    
    /**
     * 
     * @return bool
     */
    protected function up(): bool
    {
        $nonAppliedMigrations = $this->migrationService->getNonAppliedMigrations();
        if ($nonAppliedMigrations->isEmpty()) {
            $this->info('There are no new migrations');
            return true;
        }
        $this->info(
                'Migrations:' . "\n\t" .
                $nonAppliedMigrations->implode("\n\t")
        );
        if (!$this->confirm('Do you wish to apply?')) {
            return true;
        }
        foreach ($nonAppliedMigrations as $nonAppliedMigration) {
            if ($this->migrationService->up($nonAppliedMigration)) {
                $this->info('Migration ' . $nonAppliedMigration . ' applied');
            }
        }
        return true;
    }
    
    /**
     * 
     * @return bool
     */
    protected function down(): bool
    {
        $migration = $this->migrationService->getLastAppliedMigration();
        if (is_null($migration)) {
            $this->info('There are no applied migrations');
            return true;
        }
        $this->info(
                'Migration:' . "\n" .
                "\t" . $migration
        );
        if (!$this->confirm('Are you sure?')) {
            return true;
        }
        $this->migrationService->down($migration);
        return true;
    }
    
}
