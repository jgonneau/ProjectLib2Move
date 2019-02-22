<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190221105340 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE type_of_vehicle (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sub_type_of_vehicle (id INT AUTO_INCREMENT NOT NULL, type_of_vehicle_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_E14DB33DCDEA6171 (type_of_vehicle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sub_type_of_vehicle ADD CONSTRAINT FK_E14DB33DCDEA6171 FOREIGN KEY (type_of_vehicle_id) REFERENCES type_of_vehicle (id)');
        $this->addSql('ALTER TABLE vehicle ADD type_of_vehicle_id INT DEFAULT NULL, DROP type_of_vehicle');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E486CDEA6171 FOREIGN KEY (type_of_vehicle_id) REFERENCES type_of_vehicle (id)');
        $this->addSql('CREATE INDEX IDX_1B80E486CDEA6171 ON vehicle (type_of_vehicle_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E486CDEA6171');
        $this->addSql('ALTER TABLE sub_type_of_vehicle DROP FOREIGN KEY FK_E14DB33DCDEA6171');
        $this->addSql('DROP TABLE type_of_vehicle');
        $this->addSql('DROP TABLE sub_type_of_vehicle');
        $this->addSql('DROP INDEX IDX_1B80E486CDEA6171 ON vehicle');
        $this->addSql('ALTER TABLE vehicle ADD type_of_vehicle VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP type_of_vehicle_id');
    }
}
