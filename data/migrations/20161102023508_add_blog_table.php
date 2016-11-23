<?php

use Phinx\Migration\AbstractMigration;

class AddBlogTable extends AbstractMigration
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
        $blog = $this->table('blog_entries');
        $blog
            ->addColumn('filename', 'string')
            ->addColumn('author', 'string')
            ->addColumn('email', 'string', ['null' => true])
            ->addColumn('post_date', 'date')
            ->addColumn('published', 'boolean')
            ->addColumn('slug', 'string')
            ->addColumn('title', 'text')
            ->addColumn('body', 'text')
            ->create()
        ;
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('blog_entries');
    }
}