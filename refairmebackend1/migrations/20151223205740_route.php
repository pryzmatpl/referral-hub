<?php

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Model\Route as Routes;
class CreateRoutesTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        Capsule::schema()->create('routes', function($table)
        {
            $table->increments('id');
            $table->string('route');
            $table->string('page');
            $table->string('action');
            $table->string('address');
            $table->timestamps();
        });
        $array = array(
            array(
                'route'  => 'admin',
                'page'      => 'user',
                'action'    => 'index',
                'address'   => 'App\Action\Admin:index'
            ),
            array(
                'route'  => 'user',
                'page'      => 'user',
                'action'    => 'index',
                'address'   => 'App\Action\Admin:users'
            ),
            array(
                'route'  => 'useredit',
                'page'      => 'user',
                'action'    => 'edit',
                'address'   => 'App\Action\Admin:userEdit'
            ),
            array(
                'route'  => 'userdelete',
                'page'      => 'user',
                'action'    => 'delete',
                'address'   => 'App\Action\Admin:userDelete'
            ),
            array(
                'route'  => 'group',
                'page'      => 'group',
                'action'    => 'index',
                'address'   => 'App\Action\Admin:groups'
            ),
            array(
                'route'  => 'groupedit',
                'page'      => 'group',
                'action'    => 'edit',
                'address'   => 'App\Action\Admin:groupEdit'
            ),
            array(
                'route'  => 'groupdelete',
                'page'      => 'group',
                'action'    => 'delete',
                'address'   => 'App\Action\Admin:groupDelete'
            ),
            array(
                'route'  => 'permission',
                'page'      => 'permission',
                'action'    => 'index',
                'address'   => 'App\Action\Admin:permissions'
            ),
            array(
                'route'  => 'permissionedit',
                'page'      => 'permission',
                'action'    => 'edit',
                'address'   => 'App\Action\Admin:permissionEdit'
            ),
            array(
                'route'  => 'permissiondelete',
                'page'      => 'permission',
                'action'    => 'delete',
                'address'   => 'App\Action\Admin:permissionDelete'
            ));
        Routes::insert($array);
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->drop('routes');
    }
}