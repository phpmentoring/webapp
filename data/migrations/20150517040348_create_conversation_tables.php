<?php

use Phinx\Migration\AbstractMigration;

class CreateConversationTables extends AbstractMigration
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
        $conversations = $this->table('conversations');
        $conversations->addColumn('from_user_id', 'integer');
        $conversations->addColumn('to_user_id', 'integer');
        $conversations->addColumn('subject', 'string');
        $conversations->addColumn('started_at', 'datetime');
        $conversations->addIndex(['id'], ['unique' => true]);
        $conversations->addIndex(['from_user_id']);
        $conversations->addIndex(['to_user_id']);
        $conversations->create();

        $messages = $this->table('messages');
        $messages->addColumn('conversation_id', 'integer');
        $messages->addColumn('from_user_id', 'integer');
        $messages->addColumn('body', 'text');
        $messages->addColumn('created_at', 'datetime');
        $messages->addIndex(['id']);
        $messages->addForeignKey('conversation_id', 'conversations', 'id', ['delete' => 'CASCADE']);
        $messages->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('messages');
        $this->dropTable('conversations');
    }
}