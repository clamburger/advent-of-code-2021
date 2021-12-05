<?php


use App\Puzzles\Day01SolarSweep;
use App\Puzzles\Day02Dive;
use App\Puzzles\Day03BinaryDiagnostic;
use App\Puzzles\Day04GiantSquid;
use PHPUnit\Framework\TestCase;

class SolvedPuzzlesTest extends TestCase
{
    public function testDay01()
    {
        $day01 = new Day01SolarSweep();
        $this->assertEquals(1387, $day01->getPartOneAnswer());
        $this->assertEquals(1362, $day01->getPartTwoAnswer());
    }

    public function testDay02()
    {
        $day02 = new Day02Dive();
        $this->assertEquals(1507611, $day02->getPartOneAnswer());
        $this->assertEquals(1880593125, $day02->getPartTwoAnswer());
    }

    public function testDay03()
    {
        $day03 = new Day03BinaryDiagnostic();
        $this->assertEquals(4160394, $day03->getPartOneAnswer());
        $this->assertEquals(4125600, $day03->getPartTwoAnswer());
    }

    public function testDay04()
    {
        $day04 = new Day04GiantSquid();
        $this->assertEquals(58412, $day04->getPartOneAnswer());
        $this->assertEquals(10030, $day04->getPartTwoAnswer());
    }
}
