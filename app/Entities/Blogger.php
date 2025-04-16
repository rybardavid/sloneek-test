<?php

namespace App\Entities;

use App\EntityRepositories\BloggerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BloggerRepository::class)
 */
class Blogger extends BaseEntity
{
    //TODO: implement other fields
}