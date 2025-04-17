<?php

namespace App\Entities;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Article extends BaseEntity
{
    /**
     * @ORM\Column(type="string")
     */
    private string $title;

    /**
     * @ORM\Column(type="text")
     */
    private string $content;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $publishedAt = null;

    /**
     * @ORM\ManyToOne(targetEntity="Blogger", inversedBy="articles")
     * @ORM\JoinColumn(name="author_uuid", referencedColumnName="uuid", nullable=false)
     */
    private Blogger $author;

    /**
     * @ORM\ManyToOne(targetEntity="ArticleCategory", inversedBy="articles")
     * @ORM\JoinColumn(name="category_uuid", referencedColumnName="uuid", nullable=false)
     */
    private ArticleCategory $category;

    public function __construct(Blogger $author, ArticleCategory $category)
    {
        $this->author = $author;
        $author->addArticle($this);
        $this->category = $category;
        $category->addArticle($this);
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function publish(): void
    {
        $this->publishedAt = new DateTime();
    }

    public function getPublishedAt(): ?DateTime
    {
        return $this->publishedAt;
    }

    public function isEditable(): bool
    {
        return $this->publishedAt === null;
    }

    public function getAuthor(): Blogger
    {
        return $this->author;
    }

    public function setCategory(ArticleCategory $category): void
    {
        $this->category = $category;
    }

    public function getCategory(): ArticleCategory
    {
        return $this->category;
    }
}