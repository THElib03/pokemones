<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250213160712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokedex ADD evolution_id INT DEFAULT NULL, ADD evolution_level INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pokedex ADD CONSTRAINT FK_6336F6A7CDFF215A FOREIGN KEY (evolution_id) REFERENCES pokedex (id) ON DELETE SET NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6336F6A7CDFF215A ON pokedex (evolution_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokedex DROP FOREIGN KEY FK_6336F6A7CDFF215A');
        $this->addSql('DROP INDEX UNIQ_6336F6A7CDFF215A ON pokedex');
        $this->addSql('ALTER TABLE pokedex DROP evolution_id, DROP evolution_level');
    }
}
