<?php

namespace Database\Seeders;

use App\Enums\RedisEnum;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Redis;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehoueses = [
            [
                'name' => 'Abuja Warehouse',
                'code' => 'ABJWA1',
                'address' => '10 Bagada street, Abuja, Nigeria',
            ],
            [
                'name' => 'Lagos Warehouse',
                'code' => 'LAGWA1',
                'address' => '121 Ikoyi, Lagos, Nigeria',
            ],
            [
                'name' => 'Log Angeles Warehouse',
                'code' => 'LOSWA1',
                'address' => 'Km 10 wilson, United State',
            ],
        ];

        foreach ($warehoueses as $warehouese) {
            Warehouse::firstOrCreate([
                'name' => $warehouese['name'],
                'code' => $warehouese['code'],
            ], [
                'address' => $warehouese['address'],
            ]);
        }

        $dbWarehoueses = Warehouse::get()->toArray();
        Redis::set(RedisEnum::WAREHOUSES->value, json_encode($dbWarehoueses));
    }
}
