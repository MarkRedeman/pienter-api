<?php

declare(strict_types=1);

namespace App\PlusOne\Http;

use Illuminate\Database\DatabaseManager;

final class BoardsController
{
    private $db;

    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }

    public function index()
    {
        $members = $this->db->table('members')->get();

        $francken = collect([
            ["Joris","","Doting"],
            ["Su-Elle","","Kamps"],
            ["Chantal","","Kool"],
            ["Bradley","","Spronk"],
            ["Anna","","Kenbeek"],
            ["Jeanne","van","Zuilen"],
        ]);

        $fmf = collect([
            ['Leander', 'van', 'Beek'],
            ['Jasper', '', 'Somsen'],
            ['Thomas', 'van', 'Belle']
        ]);

        return [
            'boardMembers' => $members->filter(function ($member) use ($francken, $fmf) {
                return $francken->contains([$member->firstname, $member->insertion, $member->surname])
                    || $fmf->contains([$member->firstname, $member->insertion, $member->surname]);
            })->map(function ($member) use ($francken, $fmf) {
                return [
                    'lid_id' => $member->id,
                    'jaar' => $fmf->contains([$member->firstname, $member->insertion, $member->surname]) ? '2014' : '2018',
                    'functie' => '',
                ];
            })->values()
        ];
    }
}
