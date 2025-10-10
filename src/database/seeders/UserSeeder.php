<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            'name'     => 'admin',
            'email'    => 'aaa@example.com',
            'password' => Hash::make('pass'), // ←ここでハッシュ化
        ];
        User::create($user);
    }
}
