<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('roles')->delete();

        \DB::table('roles')->insert(array (
            0 =>
            array (
                'id' => 1,
                'guard_name' => 'web',
                'name' => 'admin',
                'description' => 'The admin user has full access to all features including the ability to access the admin panel.',
                'created_at' => '2017-11-21 16:23:22',
                'updated_at' => '2017-11-21 16:23:22',
            ),
            1 =>
            array (
                'id' => 2,
                'guard_name' => 'web',
                'name' => 'registered',
                'description' => 'This is the default user role. If a user has this role they have created an account; however, they have are not a subscriber.',
                'created_at' => '2017-11-21 16:23:22',
                'updated_at' => '2017-11-21 16:23:22',
            ),
            2 =>
            array (
                'id' => 3,
                'guard_name' => 'web',
                'name' => 'manufacturer',
                'description' => 'Assigned only to speaker manufacturers to manage official driverdb and design entries.',
                'created_at' => '2017-11-21 16:23:22',
                'updated_at' => '2017-11-21 16:23:22',
            )
        ));


    }
}
