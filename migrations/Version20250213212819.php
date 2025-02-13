<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250213212819 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE battle DROP FOREIGN KEY FK_139917342FE71C3E');
        $this->addSql('ALTER TABLE battle DROP FOREIGN KEY FK_13991734EADE8973');
        $this->addSql('DROP INDEX IDX_139917342FE71C3E ON battle');
        $this->addSql('DROP INDEX IDX_13991734EADE8973 ON battle');
        $this->addSql('ALTER TABLE battle ADD user1_id INT NOT NULL, ADD user2_id INT DEFAULT NULL, ADD pokemon1 LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', ADD pokemon2 LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', ADD state INT NOT NULL, ADD result TINYINT(1) DEFAULT NULL, ADD confirm LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', DROP pokemon_id, DROP wild_id');
        $this->addSql('ALTER TABLE battle ADD CONSTRAINT FK_1399173456AE248B FOREIGN KEY (user1_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE battle ADD CONSTRAINT FK_13991734441B8B65 FOREIGN KEY (user2_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_1399173456AE248B ON battle (user1_id)');
        $this->addSql('CREATE INDEX IDX_13991734441B8B65 ON battle (user2_id)');
        $this->addSql('ALTER TABLE pokedex DROP FOREIGN KEY FK_6336F6A7CDFF215A');
        $this->addSql('ALTER TABLE pokedex ADD CONSTRAINT FK_6336F6A7CDFF215A FOREIGN KEY (evolution_id) REFERENCES pokedex (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE pokemon CHANGE user_id user_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE battle DROP FOREIGN KEY FK_1399173456AE248B');
        $this->addSql('ALTER TABLE battle DROP FOREIGN KEY FK_13991734441B8B65');
        $this->addSql('DROP INDEX IDX_1399173456AE248B ON battle');
        $this->addSql('DROP INDEX IDX_13991734441B8B65 ON battle');
        $this->addSql('ALTER TABLE battle ADD pokemon_id INT NOT NULL, ADD wild_id INT NOT NULL, DROP user1_id, DROP user2_id, DROP pokemon1, DROP pokemon2, DROP state, DROP result, DROP confirm');
        $this->addSql('ALTER TABLE battle ADD CONSTRAINT FK_139917342FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE battle ADD CONSTRAINT FK_13991734EADE8973 FOREIGN KEY (wild_id) REFERENCES pokedex (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_139917342FE71C3E ON battle (pokemon_id)');
        $this->addSql('CREATE INDEX IDX_13991734EADE8973 ON battle (wild_id)');
        $this->addSql('ALTER TABLE pokedex DROP FOREIGN KEY FK_6336F6A7CDFF215A');
        $this->addSql('ALTER TABLE pokedex ADD CONSTRAINT FK_6336F6A7CDFF215A FOREIGN KEY (evolution_id) REFERENCES pokedex (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE pokemon CHANGE user_id user_id INT NOT NULL');
    }
}
