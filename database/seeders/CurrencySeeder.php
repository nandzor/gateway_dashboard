<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            [
                'name' => 'Rupiah',
                'is_active' => 1,
            ],
            [
                'name' => 'Dollar',
                'is_active' => 1,
            ],
            [
                'name' => 'Euro',
                'is_active' => 1,
            ],
        ];

        foreach ($currencies as $currency) {
            \App\Models\Currency::updateOrCreate(
                ['name' => $currency['name']],
                $currency
            );
        }
    }
}
