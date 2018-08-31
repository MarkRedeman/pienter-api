<?php

declare(strict_types=1);

namespace App\PlusOne\Http;

use DB;
use DateTimeImmutable;
use DateInterval;

use Illuminate\Database\DatabaseManager;
use Illuminate\Http\Request;

final class CategoryStatisticsController
{
    private $db;

    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }
    public function index(Request $request)
    {
        //        return ['statistics' => []];
        // By default use the period between today and 6 months ago
        $endDate = DateTimeImmutable::createFromFormat(
            'Y-m-d',
            $request->get('endDate', (new DateTimeImmutable)->format('Y-m-d'))
        );

        $startDate = DateTimeImmutable::createFromFormat(
            'Y-m-d',
            $request->get('startDate', $endDate->sub(new DateInterval('P6M'))->format('Y-m-d'))
        );

        $stats = $this->db
            ->table('transactions')
            ->join('products', 'transactions.product_id', '=', 'products.id')
            ->orderBy('ordered_at', 'desc')
            ->select([
                $this->db->raw('count(transactions.id) as amount'),
                'products.category',
                $this->db->raw('DATE_FORMAT(DATE_SUB(ordered_at, INTERVAL 6 HOUR), "%Y-%m-%d") as date')
            ])
            ->groupBy('date')
            ->whereBetween('ordered_at', [$startDate, $endDate])
            ->get();

        return [
            'statistics' => $stats->groupBy(function ($statistic) {
                return $statistic->date;
            })->map(function ($statByDate, $date) {
                // For each date we probably have a category for beer, soda and food unless said category
                // wasn't purchased that day
                $beer = $statByDate->first(function ($stat) {
                    return 'Bier' === $stat->category;
                });
                $soda = $statByDate->first(function ($stat) {
                    return 'Fris' === $stat->category;
                });
                $food = $statByDate->first(function ($stat) {
                    return 'Eten' === $stat->category;
                });

                return [
                    'date' => $date,
                    'beer' => $beer ? $beer->amount : 0,
                    'soda' => $soda ? $soda->amount : 0,
                    'food' => $food ? $food->amount : 0,
                ];
            })->values()
        ];
    }
}
