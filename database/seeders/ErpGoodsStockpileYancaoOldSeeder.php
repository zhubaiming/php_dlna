<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ErpGoodsStockpileYancaoOldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $all = DB::table('erp_goods')->orderBy('id')->get();

        $insert_data = [];
        foreach ($all as $key => $good) {
            $insert_data[$key] = [
                'goods_id' => $good->id,
                'bar_code' => $good->bar_code,
                'name' => $good->name,
                'specification' => $good->specification,
                'number' => 0
            ];
        }

        DB::table('erp_goods_stockpiles')->insert($insert_data);


        $file_to_read = fopen(storage_path('csvs/yancao/old.csv'), 'r');

        while (!feof($file_to_read)) {
            $lines[] = fgetcsv($file_to_read, 1000, ',');
        }

        fclose($file_to_read);

        unset($lines[0]);

        foreach ($lines as $value) {
            $stockpile = DB::table('erp_goods_stockpiles')->where(['bar_code' => $value[0], 'specification' => $value[2]])->first();

            DB::table('erp_goods_stockpiles')->where(['id' => $stockpile->id])->update(['number' => (int)$stockpile->number + $value[1]]);
        }
    }
}
