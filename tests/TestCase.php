<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use ReflectionClass;
use ReflectionMethod;
use RuntimeException;
use Tests\Support\Attributes\AfterTest;
use Tests\Support\Attributes\BeforeTest;
use Tests\Support\Attributes\BeforeTestClass;
use Throwable;

abstract class TestCase extends BaseTestCase
{
    protected static bool $beforeClassRun = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->runBeforeClassMethodsOnce();
        $this->runAttributeMethods(BeforeTest::class);
    }

    protected function tearDown(): void
    {
        $this->runAttributeMethods(AfterTest::class);
        parent::tearDown();
    }

    protected function runBeforeClassMethodsOnce(): void
    {
        if (! static::$beforeClassRun) {
            $this->runAttributeMethods(BeforeTestClass::class);
            static::$beforeClassRun = true;
        }

    }

    protected function runAttributeMethods(string $attributeClass): void
    {
        $ref = new ReflectionClass($this);
        foreach ($ref->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if (! empty($method->getAttributes($attributeClass))) {
                try {
                    $method->invoke($this);
                } catch (Throwable $e) {
                    throw new RuntimeException("Error running [{$attributeClass}] on method [{$method->getName()}]: {$e->getMessage()}", $e->getCode(), $e);
                }
            }
        }
    }
}
