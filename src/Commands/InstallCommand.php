<?php

namespace Arrilot\BitrixMigrations\Commands;

use Arrilot\BitrixMigrations\Interfaces\DatabaseStorageInterface;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'install',
    description: 'Create the migration database table',
)]
class InstallCommand extends AbstractCommand
{
    /**
     * Interface that gives us access to the database.
     *
     * @var DatabaseStorageInterface
     */
    protected DatabaseStorageInterface $database;

    /**
     * Table in DB to store migrations that have been already run.
     *
     * @var string
     */
    protected string $table;

    /**
     * Constructor.
     *
     * @param string                   $table
     * @param DatabaseStorageInterface $database
     * @param string|null              $name
     */
    public function __construct($table, DatabaseStorageInterface $database, $name = null)
    {
        $this->table = $table;
        $this->database = $database;

        parent::__construct($name);
    }

    /**
     * Execute the console command.
     *
     * @return null|int
     */
    protected function fire(): ?int
    {
        if ($this->database->checkMigrationTableExistence()) {
            $this->abort("Table \"{$this->table}\" already exists");
        }

        $this->database->createMigrationTable();

        $this->info('Migration table has been successfully created!');

        return 0;
    }
}
