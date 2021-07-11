<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = [
            [
                'name'  => 'خلیج_فارس',
                'count' => 1,
            ],
            [
                'name'  => 'استقلال',
                'count' => 1,
            ],
            [
                'name'  => 'پرسپولیس',
                'count' => 1,
            ],
            [
                'name'  => 'تراکتور',
                'count' => 1,
            ],
            [
                'name'  => 'سپاهان',
                'count' => 1,
            ],
            [
                'name'  => 'فولاد',
                'count' => 1,
            ],
            [
                'name'  => 'نساجی',
                'count' => 1,
            ],
            [
                'name'  => 'ذوب_آهن',
                'count' => 1,
            ],
            [
                'name'  => 'لیگ_جزیره',
                'count' => 1,
            ],
            [
                'name'  => 'آرسنال',
                'count' => 1,
            ],
            [
                'name'  => 'لیورپول',
                'count' => 1,
            ],
            [
                'name'  => 'چلسی',
                'count' => 1,
            ],
            [
                'name'  => 'منچستر_سیتی',
                'count' => 1,
            ],
            [
                'name'  => 'منچستر_یونایتد',
                'count' => 1,
            ],
            [
                'name'  => 'تاتنهام',
                'count' => 1,
            ],
            [
                'name'  => 'سری_آ',
                'count' => 1,
            ],
            [
                'name'  => 'اینتر',
                'count' => 1,
            ],
            [
                'name'  => 'رم',
                'count' => 1,
            ],
            [
                'name'  => 'فیورنتینا',
                'count' => 1,
            ],
            [
                'name'  => 'میلان',
                'count' => 1,
            ],
            [
                'name'  => 'ناپولی',
                'count' => 1,
            ],
            [
                'name'  => 'یوونتوس',
                'count' => 1,
            ],
            [
                'name'  => 'بوندس_لیگا',
                'count' => 1,
            ],
            [
                'name'  => 'بایرن_مونیخ',
                'count' => 1,
            ],
            [
                'name'  => 'دورتموند',
                'count' => 1,
            ],
            [
                'name'  => 'شالکه',
                'count' => 1,
            ],
            [
                'name'  => 'بایرلورکوزن',
                'count' => 1,
            ],
            [
                'name'  => 'لالیگا',
                'count' => 1,
            ],
            [
                'name'  => 'رئال_مادرید',
                'count' => 1,
            ],
            [
                'name'  => 'بارسلونا',
                'count' => 1,
            ],
            [
                'name'  => 'اتلتیکو_مادرید',
                'count' => 1,
            ],
            [
                'name'  => 'والنسیا',
                'count' => 1,
            ],
            [
                'name'  => 'سویا',
                'count' => 1,
            ],
        ];

        foreach ($tags as $tag) {
            Tag::factory($tag)->create();
        }
    }
}
