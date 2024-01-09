<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ErpGoodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $goods = json_decode(file_get_contents(storage_path('jsons/yancao.json')), true);
        $insert_data = [];
        foreach ($goods as $key => $good) {
            $insert_data[2 * $key] = [
                'bar_code' => trim($good['bar_code'] ?: 'bar_code_null' . $key),
                'name' => $this->replaceName($good['show_name']),
                'specification' => '盒',
                'img' => trim($good['img_main']),
            ];
            $insert_data[2 * $key + 1] = [
                'bar_code' => trim($good['bar_code2'] ?: 'bar_code_2_null' . $key),
                'name' => $this->replaceName($good['show_name']),
                'specification' => '条',
                'img' => trim($good['img_main']),
            ];
        }

        DB::table('erp_goods')->insert($insert_data);
    }

    private function replaceName($name)
    {
        $name = str_replace("\u{3000}", "", $name);
        $name = str_replace("（", "(", $name);
        $name = str_replace("）", ")", $name);

        return trim($name);
    }
}
