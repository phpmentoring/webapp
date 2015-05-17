<?php

use Phinx\Migration\AbstractMigration;

class AddTagsTable extends AbstractMigration
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
        $taxonomyVocabulary = $this->table('taxonomyVocabulary');
        $taxonomyVocabulary
            ->addColumn('name', 'string')
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('enabled', 'boolean', ['default' => 1])
            ->addIndex(['name'], ['unique' => true])
            ->create()
        ;

        $this->execute('INSERT INTO taxonomyVocabulary (`name`, `description`) VALUES ("mentor", "Mentor Terms")');
        $this->execute('INSERT INTO taxonomyVocabulary (`name`, `description`) VALUES ("apprentice", "Apprentice Terms")');

        $taxonomyTerms = $this->table('taxonomyTerms');
        $taxonomyTerms
            ->addColumn('name', 'string')
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('enabled', 'boolean', ['default' => 1])
            ->addColumn('vocabulary_id', 'boolean')
            ->addIndex(['name', 'vocabulary_id'], ['unique' => true])
            ->addIndex(['name'])
            ->addIndex(['vocabulary_id'])
            ->create()
        ;

        $userTags = $this->table('userTags');
        $userTags
            ->addColumn('user_id', 'integer')
            ->addColumn('term_id', 'integer')
            ->addIndex(['user_id', 'term_id'], ['unique' => true])
            ->addIndex(['user_id'])
            ->addIndex(['term_id'])
            ->create()
        ;
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('taxonomyVocabulary');
        $this->dropTable('taxonomyTerms');
        $this->dropTable('userTags');
    }
}