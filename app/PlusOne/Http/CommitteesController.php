<?php

declare(strict_types=1);

namespace App\PlusOne\Http;

use Illuminate\Database\DatabaseManager;

final class CommitteesController
{
    private $committees;
    private $db;

    public function __construct(DatabaseManager $db)
    {
        $this->committees = $db->table('commissie_lid');
        $this->db = $db;
    }

    public function index()
    {
        $groups = $this->db->table('members')
            ->distinct()
            ->select('group')
            ->get()
            ->pluck('group');

        $members = \App\Member::all()->map(function ($member) use ($groups) {
            return [
                'lid_id' => $member->id,
                'jaar' => 2018,
                'functie' => '',
                'commissie_id' => $groups->search($member->group),
                'naam' => $member->group
            ];
        });

        return collect(['committees' => $members]);
    }
}
