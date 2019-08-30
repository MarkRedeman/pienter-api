<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $products = [
            ['id' => 1, 'name' => '1 Coin', 'price' => 1, 'category' => 'Eten'],
            ['id' => 5, 'name' => '5 Coins', 'price' => 5, 'category' => 'Eten'],
            ['id' => 10, 'name' => '10 Coins', 'price' => 10, 'category' => 'Eten'],
            ['id' => 15, 'name' => '15 Coins', 'price' => 15, 'category' => 'Eten'],
            ['id' => 20, 'name' => '20 Coins', 'price' => 20, 'category' => 'Eten'],
            ['id' => 25, 'name' => '25 Coins', 'price' => 25, 'category' => 'Eten'],
            ['id' => 30, 'name' => '30 Coins', 'price' => 30, 'category' => 'Eten'],
            ['id' => 35, 'name' => '35 Coins', 'price' => 35, 'category' => 'Eten'],
            ['id' => 40, 'name' => '40 Coins', 'price' => 40, 'category' => 'Eten'],
            ['id' => 45, 'name' => '45 Coins', 'price' => 45, 'category' => 'Eten'],
            ['id' => 50, 'name' => '50 Coins', 'price' => 50, 'category' => 'Eten'],
            ['id' => 55, 'name' => '55 Coins', 'price' => 55, 'category' => 'Eten'],
        ];

        $products = [
            ['id' => 1, 'name' => 'Beer', 'price' => 100, 'category' => 'Bier'],
            ['id' => 2, 'name' => 'Wine', 'price' => 100, 'category' => 'Bier'],
            ['id' => 3, 'name' => 'Soda', 'price' => 70, 'category' => 'Fris'],
            ['id' => 4, 'name' => 'Straw', 'price' => 0, 'category' => 'Eten'],
        ];

        app('db')->table('products')->truncate();
        app('db')->table('products')->insert($products);

        factory(\App\Member::class, 250)->create();

        $members = [
            [
            'firstname' => 'Mark',
            'insertion' => '',
            'surname' => 'Redeman',

            'birthdate' => new DateTime('1993-04-26'),

            'group' => 'S[ck]rip(t|t?c)ie',
            ],
        ];
        app('db')->table('members')->insert($members);

        app('db')->table('transactions')
                ->insert([
                    'member_id' => 1,
                    'product_id' => 1,
                    'price' => 100,
                    'ordered_at' => '2018-08-27 21:52:02',
                ]);
    }
}
