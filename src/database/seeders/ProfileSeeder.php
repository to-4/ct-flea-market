<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $content = [
            'user_id'      => 1,
            'display_name' => '管理者',
            'image_url'    => '',
            'address_id'   => 1,
        ];

        Profile::create($content);
    }
}
