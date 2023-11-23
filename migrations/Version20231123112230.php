<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231123112230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'DB V1';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE review_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE movie_category (movie_id INT NOT NULL, category_id INT NOT NULL, PRIMARY KEY(movie_id, category_id))');
        $this->addSql('CREATE INDEX IDX_DABA824C8F93B6FC ON movie_category (movie_id)');
        $this->addSql('CREATE INDEX IDX_DABA824C12469DE2 ON movie_category (category_id)');
        $this->addSql('CREATE TABLE review (id INT NOT NULL, movie_id INT NOT NULL, content TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_794381C68F93B6FC ON review (movie_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('ALTER TABLE movie_category ADD CONSTRAINT FK_DABA824C8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE movie_category ADD CONSTRAINT FK_DABA824C12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C68F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE review_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE movie_category DROP CONSTRAINT FK_DABA824C8F93B6FC');
        $this->addSql('ALTER TABLE movie_category DROP CONSTRAINT FK_DABA824C12469DE2');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C68F93B6FC');
        $this->addSql('DROP TABLE movie_category');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE "user"');
    }
}
