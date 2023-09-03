<?php

use Illuminate\Database\Seeder;

class TypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // デフォルトデータを挿入
        $types = [
            ['name' => '単発案件'],
            ['name' => 'レベニューシェア案件'],
        ];

        DB::table('project_type')->insert($types);

    }
}
