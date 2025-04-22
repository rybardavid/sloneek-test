<?php

namespace App\Providers;

use App\Entities\Article;
use App\Entities\ArticleCategory;
use App\Entities\Blogger;
use App\EntityRepositories\ArticleCategoryRepository;
use App\EntityRepositories\ArticleRepository;
use App\EntityRepositories\BloggerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class DoctrineRepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(BloggerRepository::class, function (Application $app) {
            $entityManager = $app->make(EntityManagerInterface::class);

            return new BloggerRepository(
                $entityManager,
                $entityManager->getClassMetaData(Blogger::class)
            );
        });

        $this->app->bind(ArticleCategoryRepository::class, function (Application $app) {
            $entityManager = $app->make(EntityManagerInterface::class);

            return new ArticleCategoryRepository(
                $entityManager,
                $entityManager->getClassMetaData(ArticleCategory::class)
            );
        });

        $this->app->bind(ArticleRepository::class, function (Application $app) {
            $entityManager = $app->make(EntityManagerInterface::class);

            return new ArticleRepository(
                $entityManager,
                $entityManager->getClassMetaData(Article::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
