<?php

namespace App\Entities;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class ArticleCategory extends BaseEntity
{
    /**
     * @ORM\Column(type="string", unique=true)
     */
    private string $name;

    /**
     * @var Collection<int, Blogger>
     * @ORM\ManyToMany(targetEntity="Blogger", mappedBy="categories")
     * @ORM\JoinTable(name="blogger_category",
     *     joinColumns={@ORM\JoinColumn(name="category_uuid", referencedColumnName="uuid")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="Blogger_uuid", referencedColumnName="uuid")}
     * )
     */
    private Collection $bloggers;

    /**
     * @var Collection<int, Article>
     * @ORM\OneToMany(mappedBy="category", targetEntity="Article")
     */
    private Collection $articles;

    public function __construct()
    {
        $this->created = new DateTime();
        $this->bloggers = new ArrayCollection();
        $this->articles = new ArrayCollection();
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Collection<int, Blogger>
     */
    public function getBloggers(): Collection
    {
        return $this->bloggers;
    }

    public function addBlogger(Blogger $blogger): self
    {
        if (! $this->bloggers->contains($blogger)) {
            $this->bloggers->add($blogger);
        }

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (! $this->articles->contains($article)) {
            $this->articles->add($article);
        }

        return $this;
    }
}
