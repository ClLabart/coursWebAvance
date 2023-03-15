<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230315141652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE habitat (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE pokemon_sprites');
        $this->addSql('ALTER TABLE pokemon ADD habitat_id INT DEFAULT NULL, ADD front_default VARCHAR(255) DEFAULT NULL, ADD front_shiny VARCHAR(255) DEFAULT NULL, ADD front_female VARCHAR(255) DEFAULT NULL, ADD front_shiny_female VARCHAR(255) DEFAULT NULL, ADD back_default VARCHAR(255) DEFAULT NULL, ADD back_shiny VARCHAR(255) DEFAULT NULL, ADD back_female VARCHAR(255) DEFAULT NULL, ADD back_shiny_female VARCHAR(255) DEFAULT NULL, ADD official_artwork_front_default VARCHAR(255) DEFAULT NULL, ADD official_artwork_front_shiny VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F3AFFE2D26 FOREIGN KEY (habitat_id) REFERENCES habitat (id)');
        $this->addSql('CREATE INDEX IDX_62DC90F3AFFE2D26 ON pokemon (habitat_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon DROP FOREIGN KEY FK_62DC90F3AFFE2D26');
        $this->addSql('CREATE TABLE pokemon_sprites (id INT AUTO_INCREMENT NOT NULL, front_default VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, front_shiny VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, front_female VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, front_shiny_female VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, back_default VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, back_shiny VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, back_female VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, back_shiny_female VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE habitat');
        $this->addSql('DROP INDEX IDX_62DC90F3AFFE2D26 ON pokemon');
        $this->addSql('ALTER TABLE pokemon DROP habitat_id, DROP front_default, DROP front_shiny, DROP front_female, DROP front_shiny_female, DROP back_default, DROP back_shiny, DROP back_female, DROP back_shiny_female, DROP official_artwork_front_default, DROP official_artwork_front_shiny');
    }
}
