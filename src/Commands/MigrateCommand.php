<?php

namespace Arrilot\BitrixMigrations\Commands;

use Arrilot\BitrixMigrations\Migrator;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'migrate',
    description: 'Run all outstanding migrations',
)]
class MigrateCommand extends AbstractCommand
{
    /**
     * Migrator instance.
     *
     * @var Migrator
     */
    protected Migrator $migrator;
    /**
     * Constructor.
     *
     * @param Migrator    $migrator
     * @param string|null $name
     */
    public function __construct(Migrator $migrator, ?string $name = null)
    {
        $this->migrator = $migrator;

        parent::__construct($name);
    }

    /**
     * Execute the console command.
     *
     * @return null|int
     * @throws \Exception
     */
    protected function fire(): ?int
    {
        $toRun = $this->migrator->getMigrationsToRun();

        if (!empty($toRun)) {
            foreach ($toRun as $migration) {
                $this->migrator->runMigration($migration);
                $this->message("<info>Migrated:</info> {$migration}.php");
            }
        } else {
            $this->info('Nothing to migrate');
        }

        return 0;
    }
}
