<?php

use Phinx\Migration\AbstractMigration;

class CreateUsersTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     *
    public function change()
    {
    }
    */
    
    /**
     * Migrate Up.
     */
    public function up()
    {
        $users = $this->table('users');
        $users
            ->addColumn('email', 'string', ['limit' => 100])
            ->addColumn('password', 'string')
            ->addColumn('salt', 'string')
            ->addColumn('roles', 'string')
            ->addColumn('name', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('timeCreated', 'datetime')
            ->addColumn('username', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('isEnabled', 'boolean')
            ->addColumn('confirmationToken', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('timePasswordResetRequested', 'datetime', ['null' => true])
            ->addColumn('githubUid', 'string')
            ->addColumn('isMentor', 'boolean')
            ->addColumn('isMentee', 'boolean')
            ->addIndex(['email'], ['unique' => true])
            ->addIndex(['username'], ['unique' => true])
            ->create()
        ;
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('users');
    }
}