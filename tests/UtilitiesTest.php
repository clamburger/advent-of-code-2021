<?php


use App\Utilities;
use PHPUnit\Framework\TestCase;

class UtilitiesTest extends TestCase
{
    public function testTransposeArray()
    {
        $array = [
            [1,2,3,4],
            [11,12,13,14],
            [21,22,23,24]
        ];

        $transposed = Utilities::transposeArray($array);

        $expected = [
            [1,11,21],
            [2,12,22],
            [3,13,23],
            [4,14,24],
        ];

        $this->assertEquals($expected, $transposed);
    }
}
