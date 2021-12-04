<?php

use App\PuzzleRunner;
use App\Puzzles\AbstractPuzzle;

require __DIR__ . '/vendor/autoload.php';

echo "====================================\n";
echo "Welcome to Sam's Advent of Code 2021\n";
echo "====================================\n";
echo "\n";

$puzzle_runner = new PuzzleRunner();

foreach ($puzzle_runner->getPuzzles() as $day => $puzzle) {
    echo "Day $day\n";
    echo "Part One: {$puzzle->getPartOneAnswer()}\n";
    echo "Part Two: {$puzzle->getPartTwoAnswer()}\n";
    echo "\n";
}
