<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AlterTablePrizesAddAcceptedColumn extends AbstractMigration
{
    public function up()
    {
        $this->table('prizes')
            ->addColumn('accepted', 'boolean', [
                'default' => false,
            ])->save();
    }

    public function down()
    {
        $this->table('prizes')
            ->removeColumn('accepted')
            ->save();
    }
}
