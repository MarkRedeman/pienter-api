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
            ['id' => 1, 'name' => '1 Coin', 'amount' => 100],
            ['id' => 5, 'name' => '5 Coins', 'amount' => 500],
            ['id' => 10, 'name' => '10 Coins', 'amount' => 1000],
            ['id' => 15, 'name' => '15 Coins', 'amount' => 1500],
            ['id' => 20, 'name' => '20 Coins', 'amount' => 2000],
            ['id' => 25, 'name' => '25 Coins', 'amount' => 2500],
            ['id' => 30, 'name' => '30 Coins', 'amount' => 3000],
            ['id' => 35, 'name' => '35 Coins', 'amount' => 3500],
            ['id' => 40, 'name' => '40 Coins', 'amount' => 4000],
            ['id' => 45, 'name' => '45 Coins', 'amount' => 4500],
            ['id' => 50, 'name' => '50 Coins', 'amount' => 5000],
            ['id' => 55, 'name' => '55 Coins', 'amount' => 5500],
        ];

        app('db')->table('products')->truncate();
        app('db')->table('products')->insert($products);

        factory(\App\Member::class, 250)->create();

        $members = [
            [
            'firstname' => 'Mark',
            'insertion' => '',
            'surname' => 'Redeman',

            'group' => 'S[ck]rip(t|t?c)ie',
            ],
        ];
        app('db')->table('members')->insert($members);

        app('db')->table('transactions')
                ->insert([
                    'member_id' => 1,
                    'product_id' => 1,
                    'amount' => 100,
                    'ordered_at' => '2018-08-27 21:52:02',
                ]);
    }
}
