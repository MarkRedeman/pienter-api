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
                'prijs' => $product->price, // bleh our old system used floating numbers
                'positie' => 999,
                'categorie' => $product->category,
                'afbeelding' => null,
                'splash_afbeelding' => null,
            ];
        });

        return ['products' => $products];
    }
}
