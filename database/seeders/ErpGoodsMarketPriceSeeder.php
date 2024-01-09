<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ErpGoodsMarketPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file_to_read = fopen(storage_path('csvs/market_price.csv'), 'r');

        $idx = 0;
        while (!feof($file_to_read)) {
            $line_data = fgetcsv($file_to_read, 1000, ',');
            foreach ($line_data as $key => $val) {
                if ($idx === 0) {
                    continue;
                }
                if ($key === 4) {
                    $line_data[$key] = (int)($line_data[$key] ?? 0) * 100;
                }
            }
            $idx++;
            $lines[] = $line_data;
        }

        fclose($file_to_read);

//        dd($lines);

        unset($lines[0]);

        $insert_data = [];

        foreach ($lines as $key => $value) {
            $goods = DB::table('erp_goods')->where(['bar_code' => $value[1], 'specification' => $value[3]])->first();
            $insert_data[$key] = [
                'goods_id' => $goods->id,
                'bar_code' => $value[1],
                'name' => $goods->name,
                'specification' => $value[3],
                'price' => $value[4]
            ];
        }

        DB::table('erp_goods_market_prices')->insert($insert_data);
    }
}
