<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AddressType;

class AddressTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AddressType::firstOrCreate([
            'name' => 'Billing'
        ]);
        AddressType::firstOrCreate([
            'name' => 'Postal'
        ]);
    }
}
