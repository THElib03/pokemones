<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250206213616 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE battle (id INT AUTO_INCREMENT NOT NULL, pokemon_id INT NOT NULL, wild_id INT NOT NULL, INDEX IDX_139917342FE71C3E (pokemon_id), INDEX IDX_13991734EADE8973 (wild_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokedex (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon (id INT AUTO_INCREMENT NOT NULL, pokedex_id INT NOT NULL, user_id INT NOT NULL, level INT NOT NULL, power INT NOT NULL, is_alive TINYINT(1) NOT NULL, INDEX IDX_62DC90F3372A5D14 (pokedex_id), INDEX IDX_62DC90F3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE battle ADD CONSTRAINT FK_139917342FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE battle ADD CONSTRAINT FK_13991734EADE8973 FOREIGN KEY (wild_id) REFERENCES pokedex (id)');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F3372A5D14 FOREIGN KEY (pokedex_id) REFERENCES pokedex (id)');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F3A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE battle DROP FOREIGN KEY FK_139917342FE71C3E');
        $this->addSql('ALTER TABLE battle DROP FOREIGN KEY FK_13991734EADE8973');
        $this->addSql('ALTER TABLE pokemon DROP FOREIGN KEY FK_62DC90F3372A5D14');
        $this->addSql('ALTER TABLE pokemon DROP FOREIGN KEY FK_62DC90F3A76ED395');
        $this->addSql('DROP TABLE battle');
        $this->addSql('DROP TABLE pokedex');
        $this->addSql('DROP TABLE pokemon');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
