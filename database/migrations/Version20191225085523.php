<?php

declare(strict_types=1);

namespace App\database\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191225085523 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $sql = <<<SQL
CREATE TABLE products (
    id INT UNSIGNED AUTO_INCREMENT NOT NUlL,
    name VARCHAR (60) NOT NULL ,
    price DECIMAL (10, 2) NOT NULL,
    PRIMARY KEY (id)
)
SQL;

        $this->addSql($sql);

    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE products');
    }
}
