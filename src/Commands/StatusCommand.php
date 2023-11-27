<?php

namespace Arrilot\BitrixMigrations\Commands;

use Arrilot\BitrixMigrations\Migrator;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'status',
    description: 'Show status about last migrations',
)]
class StatusCommand extends AbstractCommand
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
     */
    protected function fire(): ?int
    {
        $this->showOldMigrations();

        $this->output->write("\r\n");

        $this->showNewMigrations();

        return 0;
    }

    /**
     * Show old migrations.
     *
     * @return void
     */
    protected function showOldMigrations(): void
    {
        $old = collect($this->migrator->getRanMigrations());

        $this->output->writeln("<fg=yellow>Old migrations:\r\n</>");

        $max = 5;

        if ($old->count() > $max) {
            $this->output->writeln('<fg=yellow>...</>');

            $old = $old->take(-$max);
        }

        foreach ($old as $migration) {
            $this->output->writeln("<fg=yellow>{$migration}.php</>");
        }
    }

    /**
     * Show new migrations.
     *
     * @return void
     */
    protected function showNewMigrations(): void
    {
        $new = collect($this->migrator->getMigrationsToRun());

        $this->output->writeln("<fg=green>New migrations:\r\n</>");

        foreach ($new as $migration) {
            $this->output->writeln("<fg=green>{$migration}.php</>");
        }
    }
}
