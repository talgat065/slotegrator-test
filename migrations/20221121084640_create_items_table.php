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
            ->addIndex('id', [
                'unique' => true,
            ])
            ->addColumn('name', 'string')
            ->addColumn('given', 'boolean', [
                'default' => false,
            ])
            ->addTimestamps()
            ->create();
    }
}
