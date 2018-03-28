<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_user')->insert([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('q1w2e3r4'),
            'login' => 'admin',
            'remember_token' => uniqid(),
        ]);
    }
}
