<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrganizationAddressType;

class OrganizationAddressTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrganizationAddressType::firstOrCreate([
            'name' => 'Billing'
        ]);
        OrganizationAddressType::firstOrCreate([
            'name' => 'Shipping'
        ]);
    }
}
