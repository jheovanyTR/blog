<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fake = Faker::create('es_ES');

        $userIds = User::pluck('id')->toArray();    //el pluck recupera todos los valores de una clave
        $postIds = Post::pluck('id')->toArray();


        for ($i=0; $i <= 10 ; $i++) {
            DB::table('users')->insert([
                'name' => $fake->name,
                'email' => $fake->unique()->safeEmail,
                'password' => Hash::make('password'),
            ]);
        }

        for ($i=0; $i<=100;$i++){
            DB::table('posts')->insert([
                'title' => $fake->sentence,
                'body' => $fake->paragraph,
                'user_id' => $fake->randomElement($userIds)
            ]);
        }

        for ($i=0; $i<=100; $i++){
            DB::table('comments')->insert([
                'body' => $fake->paragraph,
                'user_id' => $fake->randomElement($userIds),
                'post_id' => $fake->randomElement($postIds)
            ]);
        }


    }
}
