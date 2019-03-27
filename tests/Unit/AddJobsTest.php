<?php

namespace Tests\Unit;

use Box\Spout\Reader\ReaderFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddJobsTest extends TestCase
{
    /**
     * @test Verify that the class exists
     */
    public function testIfClassExists()
    {
        $this->assertTrue(class_exists(\App\Console\Commands\AddJobs::class));
    }

    public function testJobFileExist()
    {
        $this->assertTrue(Storage::disk('local_files')->exists("candidates.csv"));
    }

    public function testReadFile()
    {
        $reader = ReaderFactory::create(Type::CSV);
        $reader->setFieldDelimiter(',');
        $reader->setEndOfLineCharacter("\r\n");
        $file_path = env('FILE_PATH') . "candidates.csv";
        $reader->open($file_path);
        $data = [];
        $fileValues = [
            'Accountant',
            'Actor',
            'Actuary',
            'Administrative Services Manager',
            'High School Teachers ',
            'Advertising Sales Agent',
            'Advertising',
            'Operations Technician',
            'Aerospace Engineer',
            'Food Science Technician',
            'Agricultural Scientist',
            'Agricultural Engineer ',
            'Agricultural Worker',
            'Air Traffic Controllers',
            'Service Technician',
            'Aircraft Pilot',
            'Animal Care and Service Worker',
            'Announcer',
            'Anthropologist',
            'Appraiser of Real Estate ',
            'Mediator',
            'Architect',
            'Architectural and Engineering Manager',
            'Museum Technician',
            'Art Director',
            'Artist',
            'Assembler',
            'Athlete',
            'Athletic Trainer',
            'Atmospheric Scientist'
        ];
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
                    }
                }
                $data[] = $values;
            }
        }
        foreach ($data as $keyValues => $values) {
            $this->assertEquals($values["name"], $fileValues[$keyValues]);
        }
    }
}
