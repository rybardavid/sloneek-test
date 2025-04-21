<?php

namespace App\Console\Commands;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Console\Command;

class TruncateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:truncate-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for deleting all Entities data in DB';

    protected EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle(): void
    {
        $connection = $this->em->getConnection();
        $platform = $connection->getDatabasePlatform();

        // Disable foreign key checks (PostgreSQL-specific handling)
        $connection->executeStatement('SET session_replication_role = \'replica\'');

        // Truncate all tables
        foreach ($this->em->getMetadataFactory()->getAllMetadata() as $meta) {
            $table = $meta->getTableName();
            $sql = $platform->getTruncateTableSQL($table, true);  // Truncate with CASCADE (if needed)
            $connection->executeStatement($sql);
            $this->info("Truncated: $table");
        }

        // Re-enable foreign key checks
        $connection->executeStatement('SET session_replication_role = \'origin\'');

        $this->info('Doctrine tables truncated successfully.');

    }
}
