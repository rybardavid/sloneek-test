<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250417054733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article_categories (uuid UUID NOT NULL, name VARCHAR(255) NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, removed TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62A97E95E237E06 ON article_categories (name)');
        $this->addSql('CREATE TABLE articles (uuid UUID NOT NULL, author_uuid UUID NOT NULL, category_uuid UUID NOT NULL, title VARCHAR(255) NOT NULL, content TEXT NOT NULL, published_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, removed TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_BFDD31683590D879 ON articles (author_uuid)');
        $this->addSql('CREATE INDEX IDX_BFDD31685AE42AE1 ON articles (category_uuid)');

        $this->addSql('CREATE TYPE blogger_role AS ENUM (\'admin\', \'blogger\')');
        $this->addSql('CREATE TABLE bloggers (uuid UUID NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, role blogger_role NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, removed TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_55A2B56FE7927C74 ON bloggers (email)');
        $this->addSql('COMMENT ON COLUMN bloggers.role IS \'(DC2Type:blogger_role)\'');

        $this->addSql('CREATE TABLE blogger_article_category (user_uuid UUID NOT NULL, category_uuid UUID NOT NULL, PRIMARY KEY(user_uuid, category_uuid))');
        $this->addSql('CREATE INDEX IDX_55CF65A8ABFE1C6F ON blogger_article_category (user_uuid)');
        $this->addSql('CREATE INDEX IDX_55CF65A85AE42AE1 ON blogger_article_category (category_uuid)');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_BFDD31683590D879 FOREIGN KEY (author_uuid) REFERENCES bloggers (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_BFDD31685AE42AE1 FOREIGN KEY (category_uuid) REFERENCES article_categories (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE blogger_article_category ADD CONSTRAINT FK_55CF65A8ABFE1C6F FOREIGN KEY (user_uuid) REFERENCES bloggers (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE blogger_article_category ADD CONSTRAINT FK_55CF65A85AE42AE1 FOREIGN KEY (category_uuid) REFERENCES article_categories (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE articles DROP CONSTRAINT FK_BFDD31683590D879');
        $this->addSql('ALTER TABLE articles DROP CONSTRAINT FK_BFDD31685AE42AE1');
        $this->addSql('ALTER TABLE blogger_article_category DROP CONSTRAINT FK_55CF65A8ABFE1C6F');
        $this->addSql('ALTER TABLE blogger_article_category DROP CONSTRAINT FK_55CF65A85AE42AE1');

        $this->addSql('DROP TABLE article_categories');
        $this->addSql('DROP TABLE articles');
        $this->addSql('DROP TABLE bloggers');
        $this->addSql('DROP TABLE blogger_article_category');

        $this->addSql('DROP TYPE IF EXISTS blogger_role;');
    }
}
