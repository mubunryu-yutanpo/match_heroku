<?php

use Illuminate\Database\Seeder;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $projects = [
            [
                'user_id'    => 1,
                'title'      => 'バナー制作依頼',
                'type'       => 1,
                'upperPrice' => 30000,
                'lowerPrice' => 10000,
                'thumbnail'  => '/uploads/img19.png',
                'content'    => 'バナーを作って欲しいんだよね。',
            ],
            [
                'user_id'    => 2,
                'title'      => '【どんな相手でも対応可】魔法少女派遣します',
                'type'       => 1,
                'upperPrice' => 50000,
                'lowerPrice' => 4000,
                'content'    => 'うちの魔法少女は強いので誰が相手でも問題ありません',
            ],

        ];

        // データベースにシーダーデータを挿入
        DB::table('projects')->insert($projects);

    }
}
