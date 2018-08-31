<?php

namespace App\Console\Commands;


use App\Member;
use DateTimeImmutable;
use Illuminate\Database\DatabaseManager;
use Illuminate\Console\Command;
use League\Csv\Reader;
use League\Csv\Statement;

class ImportMembers extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "import:members {filename : The csv file that is imported}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Import members using a csv file";

    private $members;

    public function __construct()
    {
        parent::__construct();

        $db = app()->make(DatabaseManager::class);
        $this->members = $db->table('members');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (! $this->checkExistenceOfMembesInDb()) {
            return;
        }

        $filename = $this->argument('filename');

        $csv = Reader::createFromPath(database_path($filename), 'r')
            ->setHeaderOffset(0);
        $members =  [];
        $headers = $csv->getHeader();
        $this->info("");
        foreach ($csv as $record) {
            $members[] = [
                'firstname' => $record[$headers[0]],
                'insertion' => $record[$headers[1]],
                'surname' => $record[$headers[2]],
                'group' => $record[$headers[3]],
            ];
        }
        $this->table(['firstname', 'insertion', 'surname', 'group'], $members);

        $amount = count($members);
        if ($this->confirm("Would you like to import {$amount} members?")) {
            $this->members->insert($members);
        }
    }

    private function checkExistenceOfMembesInDb() : bool
    {
        $amountOfMembersInDb = $this->members->count();

        if ($amountOfMembersInDb == 0 ) {
            return true;
        }

        $this->info("There are currently {$amountOfMembersInDb} in the database");
        if ($this->confirm("Would you like to truncate the database?")) {
            $this->members->update([
                'deleted_at' => new DateTimeImmutable
            ]);
        }

        if ($this->confirm("Continue importing?")) {
            return true;
        }

        return false;
    }
}

