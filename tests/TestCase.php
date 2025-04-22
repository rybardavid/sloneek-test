<?php

namespace Tests;

use Doctrine\ORM\EntityManager;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use LaravelDoctrine\ORM\Testing\Factory;

abstract class TestCase extends BaseTestCase
{
    protected EntityManager $entityManager;

    /**
     * @throws BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->entityManager = $this->app->make(EntityManager::class);
    }

    protected function beginTransaction(): void
    {
        $this->entityManager->getConnection()->beginTransaction();
    }

    protected function rollbackTransaction(): void
    {
        if ($this->entityManager->getConnection()->isTransactionActive()) {
            $this->entityManager->getConnection()->rollBack();
        }
    }

    /**
     * @throws BindingResolutionException
     */
    protected function makeFactory(): Factory
    {
        /** @var Factory $factory */
        $factory = $this->app->make(Factory::class);
        $factory->load(database_path('factories'));

        return $factory;
    }
}
