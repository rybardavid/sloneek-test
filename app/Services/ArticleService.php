<?php

namespace App\Services;

use App\DTOs\ArticleDTO;
use App\Entities\Article;
use App\Entities\Blogger;
use App\EntityRepositories\ArticleCategoryRepository;
use App\EntityRepositories\ArticleRepository;
use App\EntityRepositories\BloggerRepository;
use App\Exceptions\SloneekExceptions\SloneekForbiddenException;
use App\Exceptions\SloneekExceptions\SloneekNotFoundException;
use App\Http\Requests\ArticleCreateRequest;
use App\Http\Requests\ArticleUpdateRequest;
use App\Policies\ArticlePolicy;
use Doctrine\ORM\EntityManagerInterface;

class ArticleService
{
    private EntityManagerInterface $entityManager;

    private BloggerRepository $bloggerRepository;

    private ArticleCategoryRepository $articleCategoryRepository;

    private ArticleRepository $articleRepository;

    private ArticlePolicy $articlePolicy;

    public function __construct(
        EntityManagerInterface $entityManager,
        BloggerRepository $bloggerRepository,
        ArticleCategoryRepository $articleCategoryRepository,
        ArticleRepository $articleRepository,
        ArticlePolicy $articlePolicy,
    ) {
        $this->entityManager = $entityManager;
        $this->bloggerRepository = $bloggerRepository;
        $this->articleCategoryRepository = $articleCategoryRepository;
        $this->articleRepository = $articleRepository;
        $this->articlePolicy = $articlePolicy;
    }

    /**
     * @throws SloneekForbiddenException
     * @throws SloneekNotFoundException
     */
    public function getSingleArticle(string $uuid, ?Blogger $author): ArticleDTO
    {
        $article = $this->articleRepository->get($uuid);
        $this->articlePolicy->isAbleToShow($author, $article);

        return ArticleDTO::fromEntity($article);
    }

    /**
     * @throws SloneekForbiddenException
     * @throws SloneekNotFoundException
     */
    public function createArticle(ArticleCreateRequest $request): ArticleDTO
    {
        $author = $this->bloggerRepository->get($request->authorUuid);
        $category = $this->articleCategoryRepository->get($request->categoryUuid);

        $this->articlePolicy->isAbleToCreate($author, $category);

        $article = new Article($author, $category);
        $article->setTitle($request->title);
        $article->setContent($request->articleContent);

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return ArticleDTO::fromEntity($article);
    }

    /**
     * @throws SloneekForbiddenException
     * @throws SloneekNotFoundException
     */
    public function updateArticle(string $articleUuid, ArticleUpdateRequest $request): ArticleDTO
    {
        $article = $this->articleRepository->get($articleUuid);
        $author = $this->bloggerRepository->get($request->authorUuid);
        $this->articlePolicy->isAbleToEdit($author, $article);

        $article->setTitle($request->title);
        $article->setContent($request->articleContent);

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return ArticleDTO::fromEntity($article);
    }

    /**
     * @throws SloneekForbiddenException
     * @throws SloneekNotFoundException
     */
    public function deleteArticle(string $articleUuid, ?Blogger $author): string
    {
        /** @var Article $article */
        $article = $this->articleRepository->find($articleUuid);

        $article->delete();

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return $article->getUuid();
    }

    /**
     * @throws SloneekForbiddenException
     * @throws SloneekNotFoundException
     */
    public function publish(string $articleUuid, ?Blogger $author): string
    {
        /** @var Article $article */
        $article = $this->articleRepository->find($articleUuid);

        $article->publish();

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return $article->getUuid();
    }
}
