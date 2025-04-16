<?php

namespace App\EntityRepositories;

use App\Entities\Blogger;
use App\Exceptions\SloneekExceptions\SloneekNotFoundException;
use Doctrine\ORM\EntityRepository;
use Illuminate\Support\Facades\App;

/**
 * @extends EntityRepository<Blogger>
 */
class BloggerRepository extends EntityRepository
{
    public static function make(): self
    {
        return App::make(self::class);
    }


    public function get(string $uuid): Blogger
    {
        $entity = $this->find($uuid);
        if (!$entity instanceof Blogger) {
            throw new SloneekNotFoundException(__('be.responses.notFound.blogger'));
        }

        return $entity;
    }

    //TODO: implement other queries
}