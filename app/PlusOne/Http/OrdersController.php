<?php

declare(strict_types=1);

namespace App\PlusOne\Http;

use Illuminate\Database\DatabaseManager;
use Illuminate\Log\Logger;
use Illuminate\Http\Request;

final class OrdersController
{
    private $logger;
    private $orders;

    public function __construct(DatabaseManager $db, Logger $logger)
    {
        $this->orders = $db;
        $this->logger = $logger;
    }

    public function index()
    {
        return $this->orders->table('transactions')
            ->orderBy('ordered_at', 'DESC')
            ->get()
            ->map(function ($transactie) {
                return $transactie;

                return [
                    'id' => $transactie->id,
                    'member_id' => $transactie->member_id,
                    'product_id' => $transactie->product_id,
                    'orderd_at' => $transactie->ordered_at,
                    'price' => $transactie->price,
                ];
            });
    }

    public function post(Request $request)
    {
        $this->logger->info(
            'Buying an order',
            ['ip' => $request->input('id'), 'order' => $request->input('order')]
        );

        $order = $request->input('order');

        foreach ($order['products'] as $product) {
            $productFromDb = $this->orders->table('products')
                     ->where('id', $product['id'])
                     ->first();

            $this->orders->table('transactions')
                ->insert([
                    'member_id' => $order['member']['id'],
                    'product_id' => $product['id'],
                    'price' => $productFromDb->price,
                    'ordered_at' => (new \DateTimeImmutable())->setTimestamp(
                        (int) ($order['ordered_at'] / 1000)
                    ),
                ]);
        }

        return response(['create' => 'ok'], 201);
    }

    public function remove(int $orderId)
    {
        $this->orders->table('transactions')
            ->where('id', $orderId)
            ->update(['removed_at' => (new \DateTimeImmutable())->setTimestamp(
                (int) ($order['ordered_at'] / 1000)
            )]);
    }
}
