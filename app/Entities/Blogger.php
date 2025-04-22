<?php

namespace App\Entities;

use App\EntityRepositories\BloggerRepository;
use App\Enums\BloggerRole;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BloggerRepository::class)
 */
class Blogger extends BaseEntity
{
    /**
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private string $email;

    /**
     * @ORM\Column(type="blogger_role")
     */
    private BloggerRole $role;

    /**
     * @var Collection<int, ArticleCategory>
     * @ORM\ManyToMany(targetEntity="ArticleCategory", inversedBy="bloggers")
     * @ORM\JoinTable(name="blogger_article_category",
     *     joinColumns={@ORM\JoinColumn(name="user_uuid", referencedColumnName="uuid")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="category_uuid", referencedColumnName="uuid")}
     * )
     */
    private Collection $categories;

    /**
     * @var Collection<int, Article>
     * @ORM\OneToMany(mappedBy="author", targetEntity="Article")
     */
    private Collection $articles;

    public function __construct(string $name, string $email, BloggerRole $role)
    {
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
        $this->categories = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->created = new DateTime();
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setRole(BloggerRole $role): void
    {
        $this->role = $role;
    }

    public function getRole(): BloggerRole
    {
        return $this->role;
    }

    public function addCategory(ArticleCategory $category): void
    {
        if (! $this->categories->contains($category)) {
            $this->categories->add($category);
        }
    }

    /**
     * @return Collection<int, ArticleCategory>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function containsCategory(ArticleCategory $category): bool
    {
        return $this->categories->contains($category);
    }

    public function addArticle(Article $article): void
    {
        if (! $this->articles->contains($article)) {
            $this->articles->add($article);
        }
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }
}
