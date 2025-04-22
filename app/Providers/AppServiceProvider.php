<?php

namespace App\Providers;

use Doctrine\Persistence\ManagerRegistry;
use Faker\Generator as FakerGenerator;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use LaravelDoctrine\ORM\Testing\Factory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(Factory::class, function (Application $app) {
            return new Factory(
                $app->make(FakerGenerator::class),
                $app->make(ManagerRegistry::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
