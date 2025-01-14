<?php

use Phinx\Migration\AbstractMigration;

class CreateUsersTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        $this->table('users')
            ->addColumn('first_name', 'string', ['limit' => 191, 'collation' => 'utf8mb4_unicode_ci', 'null' => true])
            ->addColumn('last_name', 'string', ['limit' => 191, 'collation' => 'utf8mb4_unicode_ci', 'null' => true])
            ->addColumn('password', 'string', ['limit' => 191, 'collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('remember_token', 'string', ['limit' => 100, 'collation' => 'utf8mb4_unicode_ci', 'null' => true])
            ->addColumn('activated', 'boolean', ['default' => 0])
            ->addColumn('signup_ip_address', 'string', ['limit' => 45, 'collation' => 'utf8mb4_unicode_ci', 'null' => true])
            ->addColumn('signup_confirmation_ip_address', 'string', ['limit' => 45, 'collation' => 'utf8mb4_unicode_ci', 'null' => true])
            ->addColumn('signup_sm_ip_address', 'string', ['limit' => 45, 'collation' => 'utf8mb4_unicode_ci', 'null' => true])
            ->addColumn('admin_ip_address', 'string', ['limit' => 45, 'collation' => 'utf8mb4_unicode_ci', 'null' => true])
            ->addColumn('updated_ip_address', 'string', ['limit' => 45, 'collation' => 'utf8mb4_unicode_ci', 'null' => true])
            ->addColumn('deleted_ip_address', 'string', ['limit' => 45, 'collation' => 'utf8mb4_unicode_ci', 'null' => true])
            ->addColumn('last_login', 'text', ['collation' => 'utf8mb4_unicode_ci', 'null' => true])
            ->addColumn('activ_code', 'text', ['collation' => 'utf8mb4_unicode_ci', 'null' => true])
            ->addColumn('group_id', 'integer', ['null' => true])
            ->addColumn('activ', 'integer', ['null' => true])
            ->addColumn('cvadded', 'boolean', ['null' => true])
            ->addColumn('current_role', 'string')
            ->addColumn('unique_id', 'string')
            ->addColumn('name', 'string', ['limit' => 191, 'collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('email', 'string', ['limit' => 191, 'collation' => 'utf8mb4_unicode_ci', 'null' => true])
            ->addTimestamps()
            ->create();
    }
}
