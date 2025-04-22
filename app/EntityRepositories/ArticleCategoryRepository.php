<?php

namespace App\EntityRepositories;

use App\Entities\ArticleCategory;
use App\Exceptions\SloneekExceptions\SloneekNotFoundException;
use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<ArticleCategory>
 */
class ArticleCategoryRepository extends EntityRepository
{
    /**
     * @throws SloneekNotFoundException
     */
    public function get(string $uuid): ArticleCategory
    {
        $entity = $this->find($uuid);
        if (! $entity instanceof ArticleCategory) {
            throw new SloneekNotFoundException(__('be.responses.notFound.articleCategory'));
        }

        return $entity;
    }
}
