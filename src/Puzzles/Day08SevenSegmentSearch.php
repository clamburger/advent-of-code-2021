<?php

namespace App\Puzzles;

class Day08SevenSegmentSearch extends AbstractPuzzle
{
    protected static int $day_number = 8;

    private array $displays = [];

    public function __construct()
    {
        parent::__construct();

        foreach ($this->input->lines as $display) {
            list($patterns, $output) = explode(' | ', $display);
            $this->displays[] = [
                'patterns' => explode(' ', $patterns),
                'output' => explode(' ', $output)
            ];
        }
    }

    public function getPartOneAnswer(): int
    {
        $unique_segments = 0;
        foreach ($this->displays as $display) {
            $unique_segments += count(array_filter($display['output'], function ($signals) {
                return in_array(strlen($signals), [2, 3, 4, 7]);
            }));
        }

        return $unique_segments;
    }

    public function getPartTwoAnswer(): int
    {
        return 0;
    }
}
