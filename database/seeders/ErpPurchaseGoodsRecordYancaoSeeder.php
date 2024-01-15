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
        $lines = [];

        $dir = scandir(storage_path('csvs/yancao'));
        foreach ($dir as $value) {
            if ($value == '.' || $value == '..' || $value == 'old.csv') {
                continue;
            } else {
                $file_to_read = fopen(storage_path('csvs/yancao/' . $value), 'r');

                $idx = 0;
                $_lines = [];

                while (!feof($file_to_read)) {
                    $line_data = fgetcsv($file_to_read, 1000, ',');

                    foreach ($line_data as $key => $val) {
                        $line_data[$key] = trim(iconv('gb2312', 'utf-8', $val));

                        if ($idx === 0) {
                            continue;
                        }
                        if ($key === 2) {
                            $line_data[$key] = intval(ceil(floatval($line_data[$key]) * 100));
                        } else if ($key === 3 || $key === 4) {
                            $line_data[$key] = intval(ceil($line_data[$key]));
                        } else if ($key === 5) {
                            $line_data[$key] = intval(ceil($line_data[2] * $line_data[4]));
                        } else if ($key === 7) {
                            $line_data[$key] = strlen($line_data[$key]) === 13 ? $line_data[$key] : substr($line_data[$key], 0, 13);
                        }
                    }
                    $idx++;
                    $_lines[] = $line_data;
                }

                fclose($file_to_read);
                unset($_lines[0]);

                $lines[] = ['key' => $value, 'value' => $_lines];
            }
        }

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

        foreach ($lines as $line) {
            $date = explode('.', $line['key'])[0];
//            dump($date);
            foreach ($line['value'] as $key => $item) {
                if ($item[5] === 0) {
                    continue;
                }

                $goods = DB::table('erp_goods')->where(['bar_code' => $item[7], 'specification' => '条'])->first();
                $insert_record_data[] = [
                    'goods_id' => $goods->id,
                    'bar_code' => $item[7],
                    'name' => $goods->name,
                    'specification' => '条',
                    'number' => $item[4],
                    'price' => $item[2],
                    'total_price' => $item[5],
                    'factory' => '烟草',
                    'purchased_at' => $date,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
            }


//            $stockpile = DB::table('erp_goods_stockpiles')->where(['bar_code' => $value[7], 'specification' => '条'])->first();
//            $goods = DB::table('erp_goods')->where(['bar_code' => $value[7], 'specification' => '条'])->first();
////            if (is_null($stockpile)) {
////                $insert_data[$key] = [
////                    'goods_id' => $goods->id,
////                    'bar_code' => $value[7],
////                    'name' => $goods->name,
////                    'specification' => '条',
////                    'number' => $value[4]
////                ];
////            } else {
////                DB::table('erp_goods_stockpiles')->where(['id' => $stockpile->id])->update(['number' => (int)$stockpile->number + $value[4]]);
////            }
        }

//        DB::table('erp_goods_stockpiles')->insert($insert_data);

        DB::table('erp_purchase_goods_records')->insert($insert_record_data);
    }
}
