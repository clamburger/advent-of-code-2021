<?php

namespace App\Puzzles;

use Exception;

abstract class AbstractPuzzle
{
    protected array $inputs;
    protected static int $day_number = 0;

    /**
     * @throws Exception Throws an exception if the $day_number is invalid or if the input doesn't exist
     */
    public function __construct()
    {
        if (!static::$day_number) {
            throw new Exception('You need to extend and provide a valid $day_number in ' . get_class($this));
        }

        $filename = sprintf('day%02d.txt', static::$day_number);
        $filepath = __DIR__ . '/../../inputs/' . $filename;
        if (!file_exists($filepath)) {
            throw new Exception(sprintf('Input %s does not exist', $filename));
        }

        $this->inputs = array_map('trim', file($filepath));
    }

    abstract public function getPartOneAnswer(): int;
    abstract public function getPartTwoAnswer(): int;
}
