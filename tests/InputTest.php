<?php

use App\Input;
use PHPUnit\Framework\TestCase;

class InputTest extends TestCase
{
    private Input $input;

    public function setUp(): void
    {
        $this->input = new Input(__DIR__ . '/test-input.txt');
    }

    public function testRawInput(): void
    {
        $expected = <<<TEXT
7,4,9,5,11,17,23,2,0,14,21,24,10,16,13,6,15,25,12,22,18,20,8,19,3,26,1

22 13 17 11  0
 8  2 23  4 24
TEXT;

        $this->assertStringStartsWith($expected, $this->input->raw);
    }

    public function testLines(): void
    {
        $expected = [
            '7,4,9,5,11,17,23,2,0,14,21,24,10,16,13,6,15,25,12,22,18,20,8,19,3,26,1',
            '',
            '22 13 17 11  0',
            ' 8  2 23  4 24'
        ];

        $this->assertEquals($expected, array_slice($this->input->lines, 0, 4));
    }

    public function testRawBlocks(): void
    {
        $expected = [
            '7,4,9,5,11,17,23,2,0,14,21,24,10,16,13,6,15,25,12,22,18,20,8,19,3,26,1',
            <<<TEXT
            22 13 17 11  0
             8  2 23  4 24
            21  9 14 16  7
             6 10  3 18  5
             1 12 20 15 19
            TEXT
        ];

        $this->assertEquals($expected, array_slice($this->input->raw_blocks, 0, 2));
    }
}
