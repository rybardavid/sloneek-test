<?php

namespace App\Policies;

use App\Entities\Article;
use App\Entities\ArticleCategory;
use App\Entities\Blogger;
use App\Exceptions\SloneekExceptions\SloneekForbiddenException;

class ArticlePolicy
{
    public function isAbleToShow(?Blogger $author, Article $article): void
    {
        if ($author?->getUuid() === $article->getAuthor()->getUuid() || $article->isPublished()) {
            return;
        }
        throw new SloneekForbiddenException('You do not have permission to view this article.');
    }

    /**
     * @throws SloneekForbiddenException
     */
    public function isAbleToCreate(Blogger $author, ArticleCategory $category): void
    {
        if ($author->containsCategory($category)) {
            return;
        }
        throw new SloneekForbiddenException("Author is not associated with the article category {$category->getName()}.");
    }

    public function isAbleToEdit(Blogger $author, Article $article): void
    {
        if ($author->getUuid() !== $article->getAuthor()->getUuid()) {
            throw new SloneekForbiddenException('You do not have permission to edit this article.');
        }

        if ($article->isPublished()) {
            throw new SloneekForbiddenException('Article is published.');
        }
    }
}
