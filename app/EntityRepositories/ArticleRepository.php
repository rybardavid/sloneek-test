<?php

namespace App\EntityRepositories;

use App\Entities\Article;
use App\Exceptions\SloneekExceptions\SloneekNotFoundException;
use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<Article>
 */
class ArticleRepository extends EntityRepository
{
    /**
     * @throws SloneekNotFoundException
     */
    public function get(string $uuid): Article
    {
        $entity = $this->find($uuid);
        if (! $entity instanceof Article) {
            throw new SloneekNotFoundException(__('be.responses.notFound.article'));
        }

        return $entity;
    }
}
