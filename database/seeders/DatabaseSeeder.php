<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        
        $this->call([
            RoleAndPermission::class,
        ]);

        DB::table('users')->insert([
            'name' => 'Juan',
            'email' => 'juliusfranknotario@gmail.com',
            'password' => Hash::make('password'),
            'office_id' => 1,
        ]);
        
        $user = User::first();
        $user->assignRole('Admin');


        Auth::loginUsingId(1);

        $this->call([
            OfficeSeeder::class,
        ]);
        
        $this->call([
            ArticleClassificationSeeder::class,
        ]);

       

        
    }
}
