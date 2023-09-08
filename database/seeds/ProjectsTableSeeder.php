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
                'content'    => 'バナーを作って欲しいんだよね。',
            ],
            [
                'user_id'    => 2,
                'title'      => '友達の作り方',
                'type'       => 1,
                'upperPrice' => 5555,
                'lowerPrice' => 1000,
                'content'    => '友達いっぱい作れるよ！',
            ],

        ];

        // データベースにシーダーデータを挿入
        DB::table('projects')->insert($projects);

    }
}
