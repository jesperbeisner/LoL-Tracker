<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220423052515 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE active_match (id INT AUTO_INCREMENT NOT NULL, summoner_id INT NOT NULL, game_id BIGINT NOT NULL, active TINYINT(1) NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, INDEX IDX_FC667743BC01C675 (summoner_id), INDEX game_id_index (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE summoner (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, notes VARCHAR(255) NOT NULL, server VARCHAR(255) NOT NULL, lol_id VARCHAR(255) NOT NULL, lol_account_id VARCHAR(255) NOT NULL, lol_puuid VARCHAR(255) NOT NULL, summoner_level INT NOT NULL, profile_icon_id INT NOT NULL, active TINYINT(1) NOT NULL, revision_date DATETIME NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, INDEX username_index (username), INDEX lol_id_index (lol_id), INDEX lol_account_id_index (lol_account_id), UNIQUE INDEX lol_puuid_index (lol_puuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE active_match ADD CONSTRAINT FK_FC667743BC01C675 FOREIGN KEY (summoner_id) REFERENCES summoner (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE active_match DROP FOREIGN KEY FK_FC667743BC01C675');
        $this->addSql('DROP TABLE active_match');
        $this->addSql('DROP TABLE summoner');
    }
}
