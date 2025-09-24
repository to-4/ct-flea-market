<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;

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
            'user_id' => 1,
            'display_name' => '管理者',
            'image_url' => '',
            'address_id' => 1,
        ];

        Profile::create($content);
    }
}
