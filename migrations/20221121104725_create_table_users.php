<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableUsers extends AbstractMigration
{
    public function change()
    {
        $this->table('users', ['id' => FALSE, 'primary_key' => 'id'])
            ->addColumn('id', 'uuid', [
                'null' => false,
            ])
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
