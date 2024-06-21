<?php
namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use Illuminate\Support\Facades\Hash;
class users extends Seeder
{
    public function run(): void
    {
        $password = Hash::make("123456");
       DB::table('users')->insert([
        'name'=> 'ahmed',
        'email'=> 'ahmed@gmail.com',
        'password'=> $password
       ]);
    }
}
