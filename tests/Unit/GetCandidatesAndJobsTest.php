<?php

namespace Tests\Unit;

use App\Models\Job;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetCandidatesAndJobsTest extends TestCase
{
    /**
     * @test Verify that the class exists
     */
    public function testIfClassExists()
    {
        $this->assertTrue(class_exists(\App\Console\Commands\GetCandidatesAndJobs::class));
    }

    public function testDataFromDB()
    {
        $candidates_with_jobs = Job::select("candidates.id as id_candidate", "candidates.name", "surname",
            "jobs.name as job_name", "company_name", "date_init", "date_finish")
            ->join('candidates', 'jobs.candidate_id' , 'candidates.id')
            ->orderBy('candidates.id', 'asc')->orderBy('date_init', 'desc')->get()->toArray();
        $result = false;
        if (count($candidates_with_jobs)) {
            $result = true;
        }
        $this->assertTrue($result);
    }
}
