<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ErpPurchaseGoodsRecordYancaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file_to_read = [];
//        $file_to_read = fopen(storage_path('csvs/yancao/20240110095439.csv'), 'r');

        $idx = 0;
        while (!feof($file_to_read)) {
            $line_data = fgetcsv($file_to_read, 1000, ',');
            foreach ($line_data as $key => $val) {
                $line_data[$key] = trim(iconv('gb2312', 'utf-8', $val));

                if ($idx === 0) {
                    continue;
                }
                if ($key === 2) {
                    $line_data[$key] = (int)$line_data[$key] * 100;
                } else if ($key === 3 || $key === 4) {
                    $line_data[$key] = (int)$line_data[$key];
                } else if ($key === 5) {
                    $line_data[$key] = (int)$line_data[2] * $line_data[4];
                } else if ($key === 7) {
                    $line_data[$key] = strlen($line_data[$key]) === 13 ? $line_data[$key] : substr($line_data[$key], 0, 13);
                }
            }
            $idx++;
            $lines[] = $line_data;
        }

        fclose($file_to_read);

        unset($lines[0]);

//        dd($lines);

        // [
        //    0 => "商品编码"
        //    1 => "商品"
        //    2 => "批发价"
        //    3 => "要货量"
        //    4 => "订单量"
        //    5 => "金额"
        //    6 => "厂家名称"
        //    7 => "条码"
        //  ]

        $insert_data = [];
        $insert_record_data = [];

        foreach ($lines as $key => $value) {
            if ($value[5] === 0) {
                continue;
            }

//            $stockpile = DB::table('erp_goods_stockpiles')->where(['bar_code' => $value[7], 'specification' => '条'])->first();
            $goods = DB::table('erp_goods')->where(['bar_code' => $value[7], 'specification' => '条'])->first();
//            if (is_null($stockpile)) {
//                $insert_data[$key] = [
//                    'goods_id' => $goods->id,
//                    'bar_code' => $value[7],
//                    'name' => $goods->name,
//                    'specification' => '条',
//                    'number' => $value[4]
//                ];
//            } else {
//                DB::table('erp_goods_stockpiles')->where(['id' => $stockpile->id])->update(['number' => (int)$stockpile->number + $value[4]]);
//            }

            $insert_record_data[$key] = [
                'goods_id' => $goods->id,
                'bar_code' => $value[7],
                'name' => $goods->name,
                'specification' => '条',
                'number' => $value[4],
                'price' => $value[2],
                'total_price' => $value[5],
                'factory' => '烟草',
                'purchased_at' => '2024-01-09',
                'created_at' => date('Y-m-d H:i:s'),
            ];
        }

//        DB::table('erp_goods_stockpiles')->insert($insert_data);

        DB::table('erp_purchase_goods_records')->insert($insert_record_data);
    }
}
