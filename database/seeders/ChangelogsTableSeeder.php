<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ChangelogsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('changelogs')->delete();

        \DB::table('changelogs')->insert(array (
            0 =>
            array (
                'id' => 1,
                'title' => 'SDLabs Beta 1.0 Released',
                'description' => 'We have just released the first beta version of SDLabs.cc. Click here to learn more!',
                'body' => '<p>It\'s been a fun Journey creating this beta and i\'m super excited for you to be here.</p>
<p>Make sure to stay up-to-date on our latest releases as we will be releasing many more features down the road :)</p>
<p>Thanks! Talk to you soon.</p>',
            'created_at' => '2018-05-20 23:19:00',
            'updated_at' => '2018-05-21 00:38:02',
        )));
    }
}
