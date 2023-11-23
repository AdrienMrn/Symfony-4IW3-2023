<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231123140108 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE watch_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE user_category (user_id INT NOT NULL, category_id INT NOT NULL, PRIMARY KEY(user_id, category_id))');
        $this->addSql('CREATE INDEX IDX_E6C1FDC1A76ED395 ON user_category (user_id)');
        $this->addSql('CREATE INDEX IDX_E6C1FDC112469DE2 ON user_category (category_id)');
        $this->addSql('CREATE TABLE user_movie (user_id INT NOT NULL, movie_id INT NOT NULL, PRIMARY KEY(user_id, movie_id))');
        $this->addSql('CREATE INDEX IDX_FF9C0937A76ED395 ON user_movie (user_id)');
        $this->addSql('CREATE INDEX IDX_FF9C09378F93B6FC ON user_movie (movie_id)');
        $this->addSql('CREATE TABLE watch (id INT NOT NULL, movie_id INT NOT NULL, owner_id INT NOT NULL, watch_time INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_500B4A268F93B6FC ON watch (movie_id)');
        $this->addSql('CREATE INDEX IDX_500B4A267E3C61F9 ON watch (owner_id)');
        $this->addSql('ALTER TABLE user_category ADD CONSTRAINT FK_E6C1FDC1A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_category ADD CONSTRAINT FK_E6C1FDC112469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_movie ADD CONSTRAINT FK_FF9C0937A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_movie ADD CONSTRAINT FK_FF9C09378F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE watch ADD CONSTRAINT FK_500B4A268F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE watch ADD CONSTRAINT FK_500B4A267E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C67E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_794381C67E3C61F9 ON review (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE watch_id_seq CASCADE');
        $this->addSql('ALTER TABLE user_category DROP CONSTRAINT FK_E6C1FDC1A76ED395');
        $this->addSql('ALTER TABLE user_category DROP CONSTRAINT FK_E6C1FDC112469DE2');
        $this->addSql('ALTER TABLE user_movie DROP CONSTRAINT FK_FF9C0937A76ED395');
        $this->addSql('ALTER TABLE user_movie DROP CONSTRAINT FK_FF9C09378F93B6FC');
        $this->addSql('ALTER TABLE watch DROP CONSTRAINT FK_500B4A268F93B6FC');
        $this->addSql('ALTER TABLE watch DROP CONSTRAINT FK_500B4A267E3C61F9');
        $this->addSql('DROP TABLE user_category');
        $this->addSql('DROP TABLE user_movie');
        $this->addSql('DROP TABLE watch');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C67E3C61F9');
        $this->addSql('DROP INDEX IDX_794381C67E3C61F9');
        $this->addSql('ALTER TABLE review DROP owner_id');
    }
}
