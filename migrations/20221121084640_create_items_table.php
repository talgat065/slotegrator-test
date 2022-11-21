<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateItemsTable extends AbstractMigration
{
    public function change()
    {
        $this->table('items', ['id' => FALSE, 'primary_key' => 'id'])
            ->addColumn('id', 'uuid', [
                'null' => false,
            ])
//            ->changePrimaryKey('id')
            ->addIndex('id', [
                'unique' => true,
            ])
            ->addColumn('name', 'string')
            ->addIndex('name', [
                'unique' => true,
            ])
            ->addTimestamps()
            ->create();
    }
}
