<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MediaCateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('media_cates')->insert([
            ['pid' => 0, 'name' => '2024', 'sort' => 1, 'type' => 'year', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '2023', 'sort' => 2, 'type' => 'year', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '2022', 'sort' => 3, 'type' => 'year', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '2021', 'sort' => 4, 'type' => 'year', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '2020', 'sort' => 5, 'type' => 'year', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '2019', 'sort' => 6, 'type' => 'year', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '2018', 'sort' => 7, 'type' => 'year', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '2017', 'sort' => 8, 'type' => 'year', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '2016', 'sort' => 9, 'type' => 'year', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '2015', 'sort' => 10, 'type' => 'year', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '2014', 'sort' => 11, 'type' => 'year', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '2013', 'sort' => 12, 'type' => 'year', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '2012', 'sort' => 13, 'type' => 'year', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '2011', 'sort' => 14, 'type' => 'year', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '2010', 'sort' => 15, 'type' => 'year', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '2009', 'sort' => 16, 'type' => 'year', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '2008', 'sort' => 17, 'type' => 'year', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '2007', 'sort' => 18, 'type' => 'year', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '2006', 'sort' => 19, 'type' => 'year', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '2005', 'sort' => 20, 'type' => 'year', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '2004', 'sort' => 21, 'type' => 'year', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '2003', 'sort' => 22, 'type' => 'year', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '2002', 'sort' => 23, 'type' => 'year', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '2001', 'sort' => 24, 'type' => 'year', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '2000', 'sort' => 25, 'type' => 'year', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '中国大陆', 'sort' => 1, 'type' => 'area', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '中国香港', 'sort' => 2, 'type' => 'area', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '中国台湾', 'sort' => 3, 'type' => 'area', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '美国', 'sort' => 4, 'type' => 'area', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '日本', 'sort' => 5, 'type' => 'area', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '韩国', 'sort' => 6, 'type' => 'area', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '印度', 'sort' => 7, 'type' => 'area', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '英国', 'sort' => 8, 'type' => 'area', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '加拿大', 'sort' => 9, 'type' => 'area', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '俄罗斯', 'sort' => 10, 'type' => 'area', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '墨西哥', 'sort' => 11, 'type' => 'area', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '澳大利亚', 'sort' => 12, 'type' => 'area', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '泰国', 'sort' => 13, 'type' => 'area', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '土耳其', 'sort' => 14, 'type' => 'area', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '奥地利', 'sort' => 15, 'type' => 'area', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '巴西', 'sort' => 16, 'type' => 'area', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '德国', 'sort' => 17, 'type' => 'area', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '意大利', 'sort' => 18, 'type' => 'area', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '挪威', 'sort' => 19, 'type' => 'area', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '比利时', 'sort' => 20, 'type' => 'area', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '法国', 'sort' => 21, 'type' => 'area', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '波兰', 'sort' => 22, 'type' => 'area', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '瑞典', 'sort' => 23, 'type' => 'area', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '西班牙', 'sort' => 24, 'type' => 'area', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '其它', 'sort' => 25, 'type' => 'area', 'belonging_to' => '0'],
            ['pid' => 0, 'name' => '电影', 'sort' => 1, 'type' => 'cate', 'belonging_to' => '1'],
            ['pid' => 0, 'name' => '连续剧', 'sort' => 2, 'type' => 'cate', 'belonging_to' => '1'],
            ['pid' => 0, 'name' => '综艺', 'sort' => 3, 'type' => 'cate', 'belonging_to' => '1'],
            ['pid' => 0, 'name' => '动漫', 'sort' => 4, 'type' => 'cate', 'belonging_to' => '1'],
            ['pid' => 52, 'name' => '国产剧', 'sort' => 1, 'type' => 'type', 'belonging_to' => '1'],
            ['pid' => 52, 'name' => '欧美剧', 'sort' => 2, 'type' => 'type', 'belonging_to' => '1'],
            ['pid' => 52, 'name' => '港台剧', 'sort' => 3, 'type' => 'type', 'belonging_to' => '1'],
            ['pid' => 52, 'name' => '日韩剧', 'sort' => 4, 'type' => 'type', 'belonging_to' => '1'],
            ['pid' => 53, 'name' => '真人秀', 'sort' => 1, 'type' => 'type', 'belonging_to' => '1'],
            ['pid' => 53, 'name' => '搞笑', 'sort' => 2, 'type' => 'type', 'belonging_to' => '1'],
            ['pid' => 53, 'name' => '音乐', 'sort' => 3, 'type' => 'type', 'belonging_to' => '1'],
            ['pid' => 53, 'name' => '曲艺', 'sort' => 4, 'type' => 'type', 'belonging_to' => '1'],
            ['pid' => 53, 'name' => '家庭', 'sort' => 5, 'type' => 'type', 'belonging_to' => '1'],
            ['pid' => 51, 'name' => '爱情片', 'sort' => 1, 'type' => 'type', 'belonging_to' => '1'],
            ['pid' => 51, 'name' => '惊悚片', 'sort' => 2, 'type' => 'type', 'belonging_to' => '1'],
            ['pid' => 51, 'name' => '悬疑片', 'sort' => 3, 'type' => 'type', 'belonging_to' => '1'],
            ['pid' => 51, 'name' => '科幻片', 'sort' => 4, 'type' => 'type', 'belonging_to' => '1'],
            ['pid' => 51, 'name' => '动作片', 'sort' => 5, 'type' => 'type', 'belonging_to' => '1'],
            ['pid' => 51, 'name' => '动画片', 'sort' => 6, 'type' => 'type', 'belonging_to' => '1'],
            ['pid' => 51, 'name' => '剧情片', 'sort' => 7, 'type' => 'type', 'belonging_to' => '1'],
            ['pid' => 51, 'name' => '犯罪片', 'sort' => 8, 'type' => 'type', 'belonging_to' => '1'],
            ['pid' => 51, 'name' => '喜剧片', 'sort' => 0, 'type' => 'type', 'belonging_to' => '1'],
            ['pid' => 51, 'name' => '奇幻片', 'sort' => 10, 'type' => 'type', 'belonging_to' => '1'],
            ['pid' => 51, 'name' => '战争片', 'sort' => 11, 'type' => 'type', 'belonging_to' => '1'],
            ['pid' => 51, 'name' => '恐怖片', 'sort' => 12, 'type' => 'type', 'belonging_to' => '1'],
            ['pid' => 51, 'name' => '纪录片', 'sort' => 13, 'type' => 'type', 'belonging_to' => '1'],
            ['pid' => 54, 'name' => '日本', 'sort' => 1, 'type' => 'type', 'belonging_to' => '1'],
            ['pid' => 54, 'name' => '国产', 'sort' => 2, 'type' => 'type', 'belonging_to' => '1'],
            ['pid' => 54, 'name' => '欧美', 'sort' => 3, 'type' => 'type', 'belonging_to' => '1'],
        ]);
    }
}
