<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Prima migration: crea le entity "PoiCategory" e "Poi"
 */
final class V00PoiAndCategory extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE poi_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE poi_category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE poi_category (id INT NOT NULL, title VARCHAR(255) NOT NULL, active BOOLEAN NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE poi (id INT NOT NULL, category_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, coords geography(GEOMETRY, 4326) NOT NULL, city VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, zip VARCHAR(16) DEFAULT NULL, province VARCHAR(64) DEFAULT NULL, region VARCHAR(64) DEFAULT NULL, country VARCHAR(3) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7DBB1FD612469DE2 ON poi (category_id)');
        $this->addSql('ALTER TABLE poi ADD CONSTRAINT FK_7DBB1FD612469DE2 FOREIGN KEY (category_id) REFERENCES poi_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SCHEMA topology');
        $this->addSql('CREATE SCHEMA tiger');
        $this->addSql('CREATE SCHEMA tiger_data');
        $this->addSql('ALTER TABLE poi DROP CONSTRAINT FK_7DBB1FD612469DE2');
        $this->addSql('DROP SEQUENCE poi_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE poi_category_id_seq CASCADE');
        $this->addSql('DROP TABLE poi');
        $this->addSql('DROP TABLE poi_category');
    }
}
