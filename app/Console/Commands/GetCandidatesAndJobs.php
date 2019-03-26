<?php

namespace App\Console\Commands;

use App\Models\Candidate;
use App\Models\Job;
use Illuminate\Console\Command;

class GetCandidatesAndJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:candidates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert new candidates and jobs if exists and print the data at the end';

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
        $this->call('add:candidates');
        $this->call('add:jobs');
        $headers = ["id", "name", "surname", "job name", "company name", "init date", "finish date"];
        $candidates_with_jobs = Job::select("candidates.id as id_candidate", "candidates.name", "surname",
                "jobs.name as job_name", "company_name", "date_init", "date_finish")
            ->join('candidates', 'jobs.candidate_id' , 'candidates.id')
            ->orderBy('candidates.id', 'asc')->orderBy('date_init', 'asc')->get()->toArray();

        $this->table($headers, $candidates_with_jobs);
    }
}
