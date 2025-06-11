<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250610165436 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE guide (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, photo VARCHAR(255) DEFAULT NULL, statut VARCHAR(50) NOT NULL, pays VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE visite (id INT AUTO_INCREMENT NOT NULL, guide_id INT NOT NULL, photo VARCHAR(255) DEFAULT NULL, pays VARCHAR(100) NOT NULL, lieu VARCHAR(255) NOT NULL, date DATE NOT NULL, heure_debut TIME NOT NULL, duree INT NOT NULL, heure_fin TIME NOT NULL, commentaire LONGTEXT DEFAULT NULL, INDEX IDX_B09C8CBBD7ED1D4B (guide_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE visiteur (id INT AUTO_INCREMENT NOT NULL, visite_id INT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, present TINYINT(1) NOT NULL, commentaire LONGTEXT DEFAULT NULL, INDEX IDX_4EA587B8C1C5DC59 (visite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE visite ADD CONSTRAINT FK_B09C8CBBD7ED1D4B FOREIGN KEY (guide_id) REFERENCES guide (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE visiteur ADD CONSTRAINT FK_4EA587B8C1C5DC59 FOREIGN KEY (visite_id) REFERENCES visite (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE visite DROP FOREIGN KEY FK_B09C8CBBD7ED1D4B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE visiteur DROP FOREIGN KEY FK_4EA587B8C1C5DC59
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE guide
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE visite
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE visiteur
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
