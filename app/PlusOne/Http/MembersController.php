<?php

declare(strict_types=1);

namespace App\PlusOne\Http;

use Illuminate\Database\DatabaseManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class MembersController
{
    private $members;
    private $db;

    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
        $this->members = $db->table('members');
    }

    public function index()
    {
        $selects = [
            'id', 'firstname', 'insertion', 'surname', 'birthdate', 'group', 'transactions.latest_purchase_at', 'transactions.total_spent',
        ];

        // For each member we want to find the date of their latest purchase,
        // so that we can give a warning when someone wants to make an order
        // on a member who has not purchased anything lately
        $latestPurchasePerMember = $this->db->table('transactions')
                                 ->select(['member_id', $this->db->raw('MAX(ordered_at) as latest_purchase_at, SUM(price) as total_spent')])
                                 ->groupBy('member_id')
                                 ->toSql();

        $members = $this->members->leftJoin(
            $this->db->raw('('.$latestPurchasePerMember.') transactions'),
            function ($join) {
                return $join->on('members.id', '=', 'transactions.member_id');
            }
        )
            ->where('members.deleted_at', '=', null)
            ->select($selects)
            ->get()
            ->map(function ($member) {
                return [
                    'id' => $member->id,
                    'voornaam' => $member->firstname,
                    'tussenvoegsel' => $member->insertion,
                    'achternaam' => $member->surname,

                    // Since we only sell coins we don't care about people's birthday
                    'geboortedatum' => '1990-01-01',

                    'latest_purchase_at' => $member->latest_purchase_at,
                    'total_spent' => (int) $member->total_spent,
                    'group' => $member->group,

                    'prominent' => null,
                    'bijnaam' => null,
                    'kleur' => null,
                    'afbeelding' => null,
                    'button_width' => null,
                    'button_height' => null,
                ];
            });

        return collect(['members' => $members]);
    }

    public function store(Request $request)
    {
        \App\Member::create($request->only([
            'firstname',
            'insertion',
            'surname',
            'birthdate',
            'group',
        ]));

        return new Response(['created' => true], 201);
    }

    public function update(Request $request, $memberId)
    {
        $member = \App\Member::findOrFail($memberId);
        $member->update(
            $request->only([
                'firstname',
                'insertion',
                'surname',
                'birthdate',
                'group',
            ])
        );

        return new Response(['updated' => true], 202);
    }

    public function remove(Request $request, $memberId)
    {
        $member = \App\Member::findOrFail($memberId);
        $member->destroy();

        return new Response(['updated' => true], 204);
    }
}
