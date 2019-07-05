<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version20190316193219 extends AbstractMigration
{
    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     * @return void
     */
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('users');

        $table->addColumn('id', 'string');
        $table->addColumn('email', 'string');
        $table->addColumn('password', 'string')->setNotnull(false);

        $table->addColumn('created_at', 'datetime', [
            'columnDefinition' => 'timestamp default current_timestamp',
        ]);

        $table->addColumn('updated_at', 'datetime', [
            'columnDefinition' => 'timestamp on update current_timestamp default null',
        ]);

        $table->setPrimaryKey(['id']);
    }

    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     * @return void
     */
    public function down(Schema $schema): void
    {
        $schema->dropTable('users');
    }
}
