<?php

namespace Tests\Unit;

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

    /*public function testDataFromDB()
    {

    }*/
}
