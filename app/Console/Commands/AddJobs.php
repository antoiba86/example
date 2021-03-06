<?php

namespace App\Console\Commands;

use App\Models\Job;
use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AddJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read a csv file and insert jobs in database into jobs table';

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
        $file_path = env('FILE_PATH') . "jobs.csv";
        try {
            $reader->open($file_path);
        } catch (IOException $e) {
            Log::error("Error " . $e . " File doesn't exist");
            exit;
        }
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $keyRow => $row) {
                $values = [];
                foreach ($row as $keyValue => $value) {
                    switch ($keyValue) {
                        case 0:
                            $values['id'] = $value;
                            break;
                        case 1:
                            $values['candidate_id'] = $value;
                            break;
                        case 2:
                            $values['name'] = $value;
                            break;
                        case 3:
                            $values['company_name'] = $value;
                            break;
                        case 4:
                            $values['date_init'] = $this->createDateFromFormat($value);
                            break;
                        case 5:
                            $values['date_finish'] = $this->createDateFromFormat($value);
                            break;
                    }
                }
                Job::updateOrCreate([
                    "candidate_id" => $values["candidate_id"],
                    "company_name" => $values["company_name"],
                    "name" => $values["name"],
                    "date_init" => $values["date_init"]
                ], $values);
            }
        }
    }

    public function createDateFromFormat($date)
    {
        return Carbon::createFromFormat("d.m.Y H:i", $date);
    }
}
