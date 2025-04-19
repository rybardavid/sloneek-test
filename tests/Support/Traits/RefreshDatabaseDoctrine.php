<?php

namespace Tests\Support\Traits;

use Doctrine\ORM\EntityManagerInterface;
use Tests\Support\Attributes\AfterTest;
use Tests\Support\Attributes\BeforeTest;

trait RefreshDatabaseDoctrine
{
    #[BeforeTest]
    public function beginTransaction(): void
    {
        app(EntityManagerInterface::class)
            ->getConnection()
            ->beginTransaction();
    }

    #[AfterTest]
    public function rollbackTransaction(): void
    {
        $em = app(EntityManagerInterface::class);
        $conn = $em->getConnection();

        if ($conn->isTransactionActive()) {
            $conn->rollBack();
        }

        $em->clear();
    }
}
