<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version20190903204308 extends AbstractMigration
{
    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     * @return void
     */
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('units');

        $table->addColumn('id', 'string');
        $table->addColumn('map_id', 'string');
        $table->addColumn('name', 'string');
        $table->addColumn('display_name', 'string');
        $table->addColumn('cost', 'integer');
        $table->addColumn('power', 'integer')->setDefault(0);
        $table->addColumn('defense', 'integer')->setDefault(0);
        $table->addColumn('earning_points', 'integer')->setDefault(0);
        $table->addColumn('type', 'string');

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
        $schema->dropTable('units');
    }
}
