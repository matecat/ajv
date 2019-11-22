<?php
namespace Matecat\AJV\Tests;

use Matecat\AJV\Checker;
use PHPUnit\Framework\TestCase;

class CheckerTest extends TestCase
{
    /**
     * @test
     */
    public function file_not_found()
    {
        $string = "fdsfdsfds";

        $checker = new Checker($string);
        $report = $checker->report();

        $this->assertEquals($report['status'], "File not found");
    }

    /**
     * @test
     */
    public function keys_errors()
    {
        $string = __DIR__ . '/json/missing-key.json';

        $checker = new Checker($string);
        $report = $checker->report();

        $this->assertEquals($report['status'], "OK");
        $this->assertCount(1, $report['errors']['keys']);
    }

    /**
     * @test
     */
    public function duplicates()
    {
        $string = __DIR__ . '/json/duplicate.json';

        $checker = new Checker($string);
        $report = $checker->report();

        $this->assertEquals($report['status'], "OK");
        $this->assertCount(2, $report['errors']['target']);
    }

    /**
     * @test
     */
    public function duplicates_ids()
    {
        $string = __DIR__ . '/json/duplicate-ids.json';

        $checker = new Checker($string);
        $report = $checker->report();

        $this->assertEquals($report['status'], "OK");
        $this->assertCount(2, $report['errors']['ids']);
    }

    /**
     * @test
     */
    public function duplicates_segment_ids()
    {
        $string = __DIR__ . '/json/duplicate-segmentids.json';

        $checker = new Checker($string);
        $report = $checker->report();

        $this->assertEquals($report['status'], "OK");
        $this->assertCount(1, $report['errors']['segment_ids']);
    }

    /**
     * @test
     */
    public function no_errors()
    {
        $string = __DIR__ . '/json/ok.json';

        $checker = new Checker($string);
        $report = $checker->report();

        $this->assertEquals($report['status'], "OK");
        $this->assertCount(0, $report['errors']);
    }
}