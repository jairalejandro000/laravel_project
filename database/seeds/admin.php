<?php

use Illuminate\Database\Seeder;

class admin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'jair alejandro',
            'email' => 'jairalejandro32@outlook.com',
            'password' => Hash::make('12345678')
        ]);
    }
}
