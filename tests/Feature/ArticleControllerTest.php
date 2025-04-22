<?php

namespace Tests\Feature;

use App\Entities\Article;
use App\Entities\ArticleCategory;
use App\Entities\Blogger;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use LaravelDoctrine\ORM\Testing\Factory;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    use WithFaker;

    private Factory $factory;

    /**
     * @var Collection<int, Article>
     */
    private Collection $articleCategories;

    /**
     * @throws BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = $this->makeFactory();
        $this->artisan('app:truncate-database');
        // @phpstan-ignore-next-line
        $this->articleCategories = $this->factory->of(ArticleCategory::class)->times(20)->create();
    }

    public function test_create_article(): void
    {
        /** @var Blogger $blogger */
        $blogger = $this->factory->of(Blogger::class)->times(1)->create();

        /** @var ArticleCategory $articleCategory */
        $articleCategory = $this->articleCategories->random();
        $blogger->addCategory($articleCategory);

        $this->entityManager->persist($blogger);
        $this->entityManager->flush();
        $this->entityManager->clear();

        $articleContent = $this->faker->realText;
        $response = $this->postJson('/api/articles', [
            'title' => 'New Article',
            'article_content' => $articleContent,
            'author_uuid' => $blogger->getUuid(),
            'category_uuid' => $articleCategory->getUuid(),
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'uuid',
                    'title',
                    'article_content',
                    'published_at',
                    'category',
                ],
            ])
            ->assertJsonFragment([
                'title' => 'New Article',
                'article_content' => $articleContent,
                'published_at' => null,
                'category' => $articleCategory->getName(),
            ]);
    }

    public function test_missing_category_create_article(): void
    {
        /** @var Blogger $blogger */
        $blogger = $this->factory->of(Blogger::class)->times(1)->create();
        /** @var ArticleCategory $articleCategory */
        $articleCategory = $this->articleCategories->random();

        $this->entityManager->persist($blogger);
        $this->entityManager->flush();
        $this->entityManager->clear();

        $response = $this->postJson('/api/articles', [
            'title' => 'New Article',
            'article_content' => $this->faker->realText,
            'author_uuid' => $blogger->getUuid(),
            'category_uuid' => $articleCategory->getUuid(),
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonFragment([
                'message' => "Author is not associated with the article category {$articleCategory->getName()}.",
            ]);
    }

    public function test_show_article(): void
    {
        /** @var ArticleCategory $category */
        $category = $this->articleCategories->random();
        /** @var Article $article */
        $article = $this->factory->of(Article::class, 'published')->times(1)->create(['category' => $category]);

        $response = $this->getJson('/api/articles/'.$article->getUuid());
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'title' => $article->getTitle(),
                'article_content' => $article->getContent(),
                'published_at' => $article->getPublishedAt()?->format(DATE_W3C),
                'category' => $article->getCategory()->getName(),
            ]);
    }

    public function test_update_article(): void
    {
        /** @var ArticleCategory $category */
        $category = $this->articleCategories->random();
        /** @var Article $article */
        $article = $this->factory->of(Article::class)->create(['category' => $category]);

        $response = $this->putJson('/api/articles/'.$article->getUuid(), [
            'title' => 'Updated Title',
            'article_content' => 'Updated Content',
            'author_uuid' => $article->getAuthor()->getUuid(), /** @TODO implement auth and replace it with that user in controller REMOVE this */
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'title' => 'Updated Title',
                'article_content' => 'Updated Content',
            ]);
    }

    public function test_invalid_update_article_is_published(): void
    {
        /** @var ArticleCategory $category */
        $category = $this->articleCategories->random();
        /** @var Article $article */
        $article = $this->factory->of(Article::class, 'published')->create(['category' => $category]);

        $response = $this->putJson('/api/articles/'.$article->getUuid(), [
            'title' => 'Updated Title',
            'article_content' => 'Updated Content',
            'author_uuid' => $article->getAuthor()->getUuid(), /** @TODO implement auth and replace it with that user in controller REMOVE this */
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonFragment([
                'message' => 'Article is published.',
            ]);
    }

    public function test_delete_article(): void
    {
        /** @var ArticleCategory $category */
        $category = $this->articleCategories->random();
        /** @var Article $article */
        $article = $this->factory->of(Article::class)->create(['category' => $category]);
        $this->assertNull($article->getRemoved());

        $response = $this->deleteJson('/api/articles/'.$article->getUuid());
        $response->assertStatus(Response::HTTP_OK);

        $this->assertNotNull($article->getRemoved());
    }

    public function test_publish_article(): void
    {
        /** @var ArticleCategory $category */
        $category = $this->articleCategories->random();
        /** @var Article $article */
        $article = $this->factory->of(Article::class)->create(['category' => $category]);
        $this->assertNull($article->getPublishedAt());

        $response = $this->postJson('/api/articles/'.$article->getUuid().'/publish');
        $response->assertStatus(Response::HTTP_OK);

        $this->assertNotNull($article->getPublishedAt());
    }
}
