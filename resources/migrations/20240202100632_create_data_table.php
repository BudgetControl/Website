<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateDataTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $this->table('data', ['id' => true, 'primary_key' => ['id']])
            ->addColumn('email', 'string', ['null' => false])
            ->addColumn("marketing", 'boolean', ['null' => false])
            ->addColumn('created_at', 'string', ['default' => date("Y-m-d H:i:s")])
            ->addColumn('updated_at', 'string', ['null' => true])
            ->save();
    }

    public function down()
    {
        if ($this->hasTable('data')) {
            $this->table('data')->drop()->save();
        }
    }
}
