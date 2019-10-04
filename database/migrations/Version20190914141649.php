<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version20190914141649 extends AbstractMigration
{
    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     * @return void
     */
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('map_objects');

        $table->addColumn('id', 'string');
        $table->addColumn('user_id', 'string');
        $table->addColumn('map_id', 'string');
        $table->addColumn('field_id', 'string');
        $table->addColumn('unit_id', 'string');
        $table->addColumn('unit_type', 'string');

        $table->addColumn('earned_at', 'datetime', [
            'columnDefinition' => 'timestamp default current_timestamp',
        ]);

        $table->addColumn('created_at', 'datetime', [
            'columnDefinition' => 'timestamp default current_timestamp',
        ]);

        $table->addColumn('updated_at', 'datetime', [
            'columnDefinition' => 'timestamp null default null on update current_timestamp',
        ]);

        $table->setPrimaryKey(['id']);
    }

    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     * @return void
     */
    public function down(Schema $schema): void
    {
        $schema->dropTable('map_objects');
    }
}
