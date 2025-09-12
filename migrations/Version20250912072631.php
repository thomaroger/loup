<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250912072631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE child (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, first_name VARCHAR(100) NOT NULL, INDEX IDX_22B35429727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, slot_id INT NOT NULL, user_id INT NOT NULL, child_id INT DEFAULT NULL, justification LONGTEXT DEFAULT NULL, status VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_42C8495559E5119C (slot_id), INDEX IDX_42C84955A76ED395 (user_id), INDEX IDX_42C84955DD62C21B (child_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE slot (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, start_at DATETIME NOT NULL, end_at DATETIME NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(100) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE child ADD CONSTRAINT FK_22B35429727ACA70 FOREIGN KEY (parent_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495559E5119C FOREIGN KEY (slot_id) REFERENCES slot (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955DD62C21B FOREIGN KEY (child_id) REFERENCES child (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE child DROP FOREIGN KEY FK_22B35429727ACA70');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495559E5119C');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955DD62C21B');
        $this->addSql('DROP TABLE child');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE slot');
        $this->addSql('DROP TABLE user');
    }
}
