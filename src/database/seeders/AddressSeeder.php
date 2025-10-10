<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contents = [
            [
                'user_id'       => 1,
                'postal_code'   => '123-4567',
                'address_line1' => '東京都東西区南北１－２',
                'address_line2' => '',
                'is_default'    => true,
            ],
        ];

        foreach ($contents as $content) {
            Address::create($content);
        }
    }
}
