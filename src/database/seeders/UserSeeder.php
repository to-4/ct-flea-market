<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ダミーユーザー
        $user = [
            'name' => 'admin',
            'email' => 'aaa@example.com',
            'password' => 'pass',
            ];
        User::create($user);
    }
}
