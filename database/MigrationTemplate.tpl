<?php declare (strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version<version> extends AbstractMigration
{
    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     * @return void
     */
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('table');

        $table->addColumn('id', 'integer', ['autoincrement' => true]);

        $table->addColumn('created_at', 'datetime', [
            'columnDefinition' => 'timestamp default current_timestamp',
        ]);

        $table->addColumn('updated_at', 'datetime', [
            'columnDefinition' => 'timestamp on update current_timestamp',
        ])->setDefault(null);

        $table->setPrimaryKey(['id']);
    }

    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     * @return void
     */
    public function down(Schema $schema): void
    {
        $table->dropTable('table');
    }
}