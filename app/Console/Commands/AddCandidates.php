<?php

namespace App\Console\Commands;

use App\Models\Candidate;
use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Illuminate\Console\Command;

class AddCandidates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:candidates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read a csv file and insert into database in Candidates table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $reader = ReaderFactory::create(Type::CSV);
        $reader->setFieldDelimiter(',');
        $reader->setEndOfLineCharacter("\r\n");
        $file_path = env('FILE_PATH') . "candidates.csv";
        $reader->open($file_path);
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $keyRow => $row) {
                $values = [];
                foreach ($row as $keyValue => $value) {
                    switch ($keyValue) {
                        case 0:
                            $values['id'] = $value;
                            break;
                        case 1:
                            $values['name'] = $value;
                            break;
                        case 2:
                            $values['surname'] = $value;
                            break;
                        case 3:
                            $values['email'] = $value;
                            break;
                    }
                }
                Candidate::updateOrCreate(["email" => $values["email"]], $values);
            }
        }
    }

}
