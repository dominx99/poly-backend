<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version20190421165816 extends AbstractMigration
{
    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     * @return void
     */
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('social_providers');

        $table->addColumn('id', 'string');
        $table->addColumn('user_id', 'string');
        $table->addColumn('name', 'string');

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
        $schema->dropTable('social_providers');
    }
}
