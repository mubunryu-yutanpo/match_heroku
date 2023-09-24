<?php

use Illuminate\Database\Seeder;

class PublicMessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $publicMessages = [
            [
                'user_id'    => 1,
                'project_id'      => 1,
                'comment'    => 'よろしくお願いします',
            ],
            [
                'user_id'    => 2,
                'project_id'      => 1,
                'comment'    => 'これはテストですか？それともテストですか？',
            ],
            [
                'user_id'    => 2,
                'project_id'      => 2,
                'comment'    => '是非とも我が社の魔法少女たちをご贔屓に！',
            ],
            [
                'user_id'    => 1,
                'project_id'      => 2,
                'comment'    => '具体的にはどんな魔法が使えますか？',
            ],

        ];

        // データベースにシーダーデータを挿入
        DB::table('public_messages')->insert($publicMessages);

    }
}
