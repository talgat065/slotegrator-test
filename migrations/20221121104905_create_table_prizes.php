<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTablePrizes extends AbstractMigration
{
    public function change()
    {
        $this->table('prizes', ['id' => FALSE, 'primary_key' => 'id'])
            ->addColumn('id', 'uuid', [
                'null' => false,
            ])
            ->addIndex('id', [
                'unique' => true,
            ])
            ->addColumn('user_id', 'uuid')
            ->addForeignKey('user_id', 'users', 'id')
            ->addColumn('item_id', 'uuid', [
                'null' => true,
            ])
            ->addForeignKey('item_id', 'items', 'id')
            ->addColumn('type', 'string')
            ->addColumn('money', 'integer', [
                'null' => true,
            ])
            ->addColumn('bonus', 'integer', [
                'null' => true,
            ])
            ->addColumn('processed', 'boolean', [
                'default' => false,
            ])
            ->addTimestamps()
            ->create();
    }
}
