<?php

namespace App\DTOs;

use App\Entities\Article;
use DateTime;

readonly class ArticleDTO
{
    private function __construct(
        public string $uuid,
        public string $title,
        public string $content,
        public ?DateTime $publishedAt,
        public string $category,
    ) {}

    public static function fromEntity(Article $article): self
    {
        return new self(
            $article->getUuid(),
            $article->getTitle(),
            $article->getContent(),
            $article->getPublishedAt(),
            $article->getCategory()->getName(),
        );
    }

    /**
     * @return array{uuid: string, title: string, article_content: string, published_at: non-falsy-string|null, category: string}
     */
    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'title' => $this->title,
            'article_content' => $this->content,
            'published_at' => $this->publishedAt?->format(DATE_W3C),
            'category' => $this->category,
        ];
    }
}
