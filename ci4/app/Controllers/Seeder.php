<?php

namespace App\Controllers;

class Seeder extends BaseController
{
    public function run()
    {
        $seeder = \Config\Database::seeder();
        $seeder->call('UserSeeder');
        return "Seeder berhasil dijalankan!";
    }
}