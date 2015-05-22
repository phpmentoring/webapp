<?php

use Phinx\Migration\AbstractMigration;

class MentorApprenticeLinkTableCreate extends AbstractMigration
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
        $mentorApprenticeXref = $this->table('mentorApprenticeXref');
        $mentorApprenticeXref
            ->addColumn('mentorId', 'integer')
            ->addColumn('apprenticeId', 'integer')
            ->create()
        ;
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('mentorApprenticeXref');
    }
}