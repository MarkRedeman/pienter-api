<?php

declare(strict_types=1);

namespace App\PlusOne\Http;

use Illuminate\Database\DatabaseManager;

final class ProductsController
{
    private $products;

    public function __construct(DatabaseManager $db)
    {
        $this->products = $db->table('products');
    }

    public function index()
    {
        $products = $this->products->get()->map(function ($product) {
            return [
          'id' => $product->id,
          'naam' => $product->name,
          'prijs' => $product->amount, // bleh our old system used floating numbers
          'positie' => 999,
          'categorie' => 'Eten',
          'afbeelding' => null,
          'splash_afbeelding' => null,
        ];
        });

        return ['products' => $products];

        $products = collect(
          [
            ['id' => 1, 'naam' => '1 Coin', 'prijs' => 1],
            ['id' => 5, 'naam' => '5 Coins', 'prijs' => 5],
            ['id' => 10, 'naam' => '10 Coins', 'prijs' => 10],
            ['id' => 15, 'naam' => '15 Coins', 'prijs' => 15],
            ['id' => 20, 'naam' => '20 Coins', 'prijs' => 20],
            ['id' => 25, 'naam' => '25 Coins', 'prijs' => 25],
            ['id' => 30, 'naam' => '30 Coins', 'prijs' => 30],
            ['id' => 35, 'naam' => '35 Coins', 'prijs' => 35],
            ['id' => 40, 'naam' => '40 Coins', 'prijs' => 40],
            ['id' => 45, 'naam' => '45 Coins', 'prijs' => 45],
            ['id' => 50, 'naam' => '50 Coins', 'prijs' => 50],
            ['id' => 55, 'naam' => '55 Coins', 'prijs' => 55],
          ]
      )->map(function ($product) {
          return [
          'id' => $product['id'],
          'naam' => $product['naam'],
          'prijs' => $product['prijs'],
          'positie' => 999,
          'categorie' => 'Eten',
          'afbeelding' => null,
          'splash_afbeelding' => null,
        ];
      });

        return ['products' => $products];
    }
}
