<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231018211433 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE prefix_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) NOT NULL, slug VARCHAR(125) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prefix_post (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, author_id INT DEFAULT NULL, title VARCHAR(80) NOT NULL, description VARCHAR(150) NOT NULL, image VARCHAR(255) DEFAULT NULL, content LONGTEXT DEFAULT NULL, pinned TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_5FBBB67C12469DE2 (category_id), INDEX IDX_5FBBB67CF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prefix_post_tag (post_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_523D08E94B89032C (post_id), INDEX IDX_523D08E9BAD26311 (tag_id), PRIMARY KEY(post_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prefix_tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) NOT NULL, slug VARCHAR(143) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `prefix_user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, name VARCHAR(80) NOT NULL, registred_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_88A20CB8E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE prefix_post ADD CONSTRAINT FK_5FBBB67C12469DE2 FOREIGN KEY (category_id) REFERENCES prefix_category (id)');
        $this->addSql('ALTER TABLE prefix_post ADD CONSTRAINT FK_5FBBB67CF675F31B FOREIGN KEY (author_id) REFERENCES `prefix_user` (id)');
        $this->addSql('ALTER TABLE prefix_post_tag ADD CONSTRAINT FK_523D08E94B89032C FOREIGN KEY (post_id) REFERENCES prefix_post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prefix_post_tag ADD CONSTRAINT FK_523D08E9BAD26311 FOREIGN KEY (tag_id) REFERENCES prefix_tag (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prefix_post DROP FOREIGN KEY FK_5FBBB67C12469DE2');
        $this->addSql('ALTER TABLE prefix_post DROP FOREIGN KEY FK_5FBBB67CF675F31B');
        $this->addSql('ALTER TABLE prefix_post_tag DROP FOREIGN KEY FK_523D08E94B89032C');
        $this->addSql('ALTER TABLE prefix_post_tag DROP FOREIGN KEY FK_523D08E9BAD26311');
        $this->addSql('DROP TABLE prefix_category');
        $this->addSql('DROP TABLE prefix_post');
        $this->addSql('DROP TABLE prefix_post_tag');
        $this->addSql('DROP TABLE prefix_tag');
        $this->addSql('DROP TABLE `prefix_user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
