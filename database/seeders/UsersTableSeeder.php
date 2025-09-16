<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear users table
        User::truncate();

        User::create([
            'id' => 1,
            'name' => 'GorchPortley',
            'email' => 'admin@sdlabs.cc',
            'username' => 'GorchPortley',
            'avatar' => 'demo/default.png',
            'password' => '$2y$10$L8MjmjVVOCbyLHbp7pq/9.1ZEEa5AqE67ZXLd2M4.res05a3Rz/G2',
            'remember_token' => '4oXDVo48Lm1pc4j7NkWI9cMO4hv5OIEJFMrqjSCKQsIwWMGRFYDvNpdioBfo',
            'created_at' => '2017-11-21 16:07:22',
            'updated_at' => '2018-09-22 23:34:02',
            'trial_ends_at' => null,
            'verification_code' => null,
            'verified' => 1,
        ]);
    }
}
