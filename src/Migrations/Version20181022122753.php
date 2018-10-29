<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181022122753 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE booking (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, guest_id INTEGER NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__guest AS SELECT id, name, phone, email, address, notes FROM guest');
        $this->addSql('DROP TABLE guest');
        $this->addSql('CREATE TABLE guest (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, phone VARCHAR(255) DEFAULT NULL COLLATE BINARY, email VARCHAR(255) DEFAULT NULL COLLATE BINARY, address CLOB DEFAULT NULL, notes CLOB DEFAULT NULL)');
        $this->addSql('INSERT INTO guest (id, name, phone, email, address, notes) SELECT id, name, phone, email, address, notes FROM __temp__guest');
        $this->addSql('DROP TABLE __temp__guest');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE booking');
        $this->addSql('CREATE TEMPORARY TABLE __temp__guest AS SELECT id, name, phone, email, address, notes FROM guest');
        $this->addSql('DROP TABLE guest');
        $this->addSql('CREATE TABLE guest (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, address CLOB DEFAULT NULL COLLATE BINARY, notes CLOB DEFAULT NULL COLLATE BINARY)');
        $this->addSql('INSERT INTO guest (id, name, phone, email, address, notes) SELECT id, name, phone, email, address, notes FROM __temp__guest');
        $this->addSql('DROP TABLE __temp__guest');
    }
}
