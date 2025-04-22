<?php

namespace App\Http\Controllers;

use App\Entities\Article;
use App\Http\Requests\ArticleCreateRequest;
use App\Http\Requests\ArticleUpdateRequest;
use App\Services\ArticleService;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    private EntityManagerInterface $entityManager;

    private ArticleService $articleService;

    public function __construct(
        EntityManagerInterface $entityManager,
        ArticleService $articleService,
    ) {
        $this->entityManager = $entityManager;
        $this->articleService = $articleService;
    }

    public function index(): JsonResponse
    {
        $articles = $this->entityManager->getRepository(Article::class)->findAll();

        return $this->successResponse($articles);
    }

    public function store(ArticleCreateRequest $request): JsonResponse
    {
        $article = $this->articleService->createArticle($request);

        return $this->successResponse($article->toArray());
    }

    public function show(string $uuid): JsonResponse
    {
        $article = $this->articleService->getSingleArticle($uuid, null);

        return $this->successResponse($article->toArray());
    }

    public function update(ArticleUpdateRequest $request, string $uuid): JsonResponse
    {
        $updatedArticle = $this->articleService->updateArticle($uuid, $request);

        return $this->successResponse($updatedArticle->toArray());
    }

    public function destroy(string $uuid): JsonResponse
    {
        $uuid = $this->articleService->deleteArticle($uuid, null);

        return $this->successResponse(
            [
                'message' => "Article deleted successfully {$uuid}.",
            ]
        );
    }

    public function publish(string $uuid): JsonResponse
    {
        $uuid = $this->articleService->publish($uuid, null);

        return $this->successResponse(
            [
                'message' => "Article published successfully {$uuid}.",
            ]
        );
    }
}
