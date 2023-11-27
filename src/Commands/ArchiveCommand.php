<?php

namespace Arrilot\BitrixMigrations\Commands;

use Arrilot\BitrixMigrations\Migrator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(
    name: 'archive',
    description: 'Move migration into archive',
)]
class ArchiveCommand extends AbstractCommand
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
     * Configures the current command.
     */
    protected function configure(): void
    {
        $this->addOption(
            'without',
            'w',
            InputOption::VALUE_REQUIRED,
            'Archive without last N migration'
        );
    }

    /**
     * Execute the console command.
     *
     * @return null|int
     */
    protected function fire(): ?int
    {
        $files = $this->migrator->getAllMigrations();
        $without = $this->input->getOption('without') ?: 0;

        if ($without > 0) {
            $files = array_slice($files, 0, $without * -1);
        }

        $count = $this->migrator->moveMigrationFiles($files);

        if ($count) {
            $this->message("<info>Moved to archive:</info> {$count}");
        } else {
            $this->info('Nothing to move');
        }

        return 0;
    }
}
