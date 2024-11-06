<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Model\Group as Groups;
use Phinx\Migration\AbstractMigration;

class CreateGroupsTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        Capsule::schema()->create('groups', function($table)
        {
            $table->increments('id');
            $table->string('group_name');
            $table->string('description');
            $table->timestamps();
        });
        $array = array(
            array(
                'group_name'    => 'Admin',
                'description'   => 'Administrator',
            ),
            array(
                'group_name'    => 'Moderator',
                'description'   => 'Moderator',
            ),
            array(
                'group_name'    => 'User',
                'description'   => 'User',
            ));
        Groups::insert($array);
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->drop('groups');
    }
}