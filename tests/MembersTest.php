<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class MembersTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @test */
    public function adding_a_new_member()
    {
        $response = $this->json('POST', '/members', [
            'firstname' => 'Sansa',
            'insertion' => '',
            'surname' => 'Stark',
            'group' => 'North',
        ])->seeJson([
            'created' => true,
        ]);

        $this->assertEquals(201, $this->response->status());

        $this->seeInDatabase('members', [
            'firstname' => 'Sansa',
            'insertion' => '',
            'surname' => 'Stark',
            'group' => 'North',
        ]);
    }

    /** @test */
    public function editing_a_member()
    {
        $member = factory(\App\Member::class)->create([
            'id' => 1,
        ]);

        $this->json('PUT', '/members/1', [
            'firstname' => 'John',
            'insertion' => '',
            'surname' => 'Targaryen',
            'group' => 'South',
        ])->seeJson([
            'updated' => true,
        ]);

        $this->assertEquals(202, $this->response->status());

        $this->seeInDatabase('members', [
            'firstname' => 'John',
            'insertion' => '',
            'surname' => 'Targaryen',
            'group' => 'South',
        ]);
    }

    /** @test */
    public function getting_all_members_when_there_are_no_members()
    {
        $response = $this->get('/members')
            ->seeJson([
                'members' => [],
            ]);
    }

    /** @test */
    public function getting_all_members()
    {
        factory(\App\Member::class, 4)->create();

        $this->get('/members');

        (new TestResponse($this->response))->assertJsonCount(4, 'members');
        (new TestResponse($this->response))->assertJsonStructure([
            'members' => [[
                'id',
                'voornaam',
                'tussenvoegsel',
                'achternaam',
                'geboortedatum',
                'latest_purchase_at',
                'total_spent',
                'prominent',
                'bijnaam',
                'kleur',
                'afbeelding',
                'button_width',
                'button_height',
            ]],
        ]);
    }

    /** @test */
    public function getting_all_members_where_one_member_has_made_a_purchase()
    {
        $member = factory(\App\Member::class)->create([
            'birthdate' => new DateTime('1990-01-01'),
            'group' => 'Group 4'
        ]);
        $orderedAt = new \DateTimeImmutable('2018-01-01');

        DB::table('transactions')->insert([
            'member_id' => $member->id,
            'product_id' => 1,
            'price' => 100,
            'ordered_at' => $orderedAt,
        ]);

        DB::table('transactions')->insert([
            'member_id' => $member->id,
            'product_id' => 1,
            'price' => 200,
            'ordered_at' => $orderedAt,
        ]);

        $this->get('/members')->seeJsonEquals([
            'members' => [
                [
                    'id' => $member->id,
                    'voornaam' => $member->firstname,
                    'tussenvoegsel' => '',
                    'achternaam' => $member->surname,
                    'geboortedatum' => '1990-01-01',
                    'latest_purchase_at' => '2018-01-01 00:00:00',
                    'total_spent' => 300,
                    'group' => 'Group 4',
                    'prominent' => null,
                    'bijnaam' => null,
                    'kleur' => null,
                    'afbeelding' => null,
                    'button_width' => null,
                    'button_height' => null,
                ],
            ],
        ]);
    }

    /**
     * Assert that the response has a given JSON structure.
     *
     * @param array|null $structure
     * @param array|null $responseData
     *
     * @return $this
     */
    public function assertJsonStructure(array $structure = null, $responseData = null)
    {
        if (is_null($structure)) {
            return $this->assertJson($this->json());
        }

        if (is_null($responseData)) {
            $responseData = $this->decodeResponseJson();
        }

        foreach ($structure as $key => $value) {
            if (is_array($value) && '*' === $key) {
                PHPUnit::assertInternalType('array', $responseData);

                foreach ($responseData as $responseDataItem) {
                    $this->assertJsonStructure($structure['*'], $responseDataItem);
                }
            } elseif (is_array($value)) {
                PHPUnit::assertArrayHasKey($key, $responseData);

                $this->assertJsonStructure($structure[$key], $responseData[$key]);
            } else {
                PHPUnit::assertArrayHasKey($value, $responseData);
            }
        }

        return $this;
    }
}
