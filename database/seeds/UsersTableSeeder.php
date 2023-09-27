<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // デフォルトデータを挿入
        $users = [
            [
                'name'              => 'テスト太郎',
                'email'             => 'test@1.com',
                'password'          => bcrypt('password'),
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'name'              => 'テスト花子',
                'email'             => 'test@2.com',
                'password'          => bcrypt('password'),
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'name'              => 'お試しんのすけ',
                'email'             => 'test@3.com',
                'password'          => bcrypt('password'),
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ],

        ];

        // データベースにユーザーデータを挿入
        DB::table('users')->insert($users);
    }
}
