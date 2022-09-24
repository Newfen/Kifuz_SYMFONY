<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220629185313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE atelier (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, category_name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE creneau (id INT AUTO_INCREMENT NOT NULL, horaire DATETIME NOT NULL, nb_place INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, atelier_id INT NOT NULL, img_name VARCHAR(255) NOT NULL, INDEX IDX_C53D045F82E2CF35 (atelier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE programmation (id INT AUTO_INCREMENT NOT NULL, creneau_id INT NOT NULL, atelier_id INT NOT NULL, INDEX IDX_5E9F80E37D0729A9 (creneau_id), INDEX IDX_5E9F80E382E2CF35 (atelier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, programmation_id INT NOT NULL, nb_participant INT NOT NULL, reserved_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_42C8495519EB6921 (client_id), INDEX IDX_42C8495596D6BD09 (programmation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F82E2CF35 FOREIGN KEY (atelier_id) REFERENCES atelier (id)');
        $this->addSql('ALTER TABLE programmation ADD CONSTRAINT FK_5E9F80E37D0729A9 FOREIGN KEY (creneau_id) REFERENCES creneau (id)');
        $this->addSql('ALTER TABLE programmation ADD CONSTRAINT FK_5E9F80E382E2CF35 FOREIGN KEY (atelier_id) REFERENCES atelier (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495519EB6921 FOREIGN KEY (client_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495596D6BD09 FOREIGN KEY (programmation_id) REFERENCES programmation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F82E2CF35');
        $this->addSql('ALTER TABLE programmation DROP FOREIGN KEY FK_5E9F80E382E2CF35');
        $this->addSql('ALTER TABLE programmation DROP FOREIGN KEY FK_5E9F80E37D0729A9');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495596D6BD09');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495519EB6921');
        $this->addSql('DROP TABLE atelier');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE creneau');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE programmation');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
