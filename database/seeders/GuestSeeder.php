<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuestSeeder extends Seeder
{
    public function run(): void
    {
        DB::insert(
            'INSERT INTO guests (first_name, last_name, email, phone, country, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)',
            ['Van', 'Ivanov', 'van.ivanov@example.ru', '+79624567890', 'Russia', now(), now()]
        );
    }
}