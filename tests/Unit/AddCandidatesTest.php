<?php

namespace Tests\Unit;

use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddCandidatesTest extends TestCase
{

    /**
     * @test Verify that the class exists
     */
    public function testIfClassExists()
    {
        $this->assertTrue(class_exists(\App\Console\Commands\AddCandidates::class));
    }

    public function testCandidateFileExist()
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
            "garrison40@example.com",
            "qritchie@example.com",
            "ralph.harber@example.org",
            "walsh.tia@example.com",
            "ybraun@example.com",
            "cordia.douglas@example.net",
            "enos.goodwin@example.net",
            "phand@example.org",
            "kblick@example.org",
            "shawna25@example.net"
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
                $data[] = $values;
            }
        }
        foreach ($data as $keyValues => $values) {
            $this->assertEquals($values["email"], $fileValues[$keyValues]);
        }
    }
}
