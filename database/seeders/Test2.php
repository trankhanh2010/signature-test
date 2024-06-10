<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class Test2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = new User();
        $user->name = 'khanh2';
        $user->email = 'khanh2@gmail.com';
        $user->password = Hash::make('123456');
        $user->save();
    }
}
