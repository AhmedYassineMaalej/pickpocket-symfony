<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260528042002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recommendation (id INT AUTO_INCREMENT NOT NULL, weight INT NOT NULL, category_id INT NOT NULL, product1_id INT NOT NULL, product2_id INT NOT NULL, INDEX IDX_433224D212469DE2 (category_id), INDEX IDX_433224D25D97111F (product1_id), INDEX IDX_433224D24F22BEF1 (product2_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE recommendation ADD CONSTRAINT FK_433224D212469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE recommendation ADD CONSTRAINT FK_433224D25D97111F FOREIGN KEY (product1_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE recommendation ADD CONSTRAINT FK_433224D24F22BEF1 FOREIGN KEY (product2_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE offer CHANGE date date DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recommendation DROP FOREIGN KEY FK_433224D212469DE2');
        $this->addSql('ALTER TABLE recommendation DROP FOREIGN KEY FK_433224D25D97111F');
        $this->addSql('ALTER TABLE recommendation DROP FOREIGN KEY FK_433224D24F22BEF1');
        $this->addSql('DROP TABLE recommendation');
        $this->addSql('ALTER TABLE offer CHANGE date date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }
}
