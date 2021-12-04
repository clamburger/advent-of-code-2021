<?php

use App\Puzzles\AbstractPuzzle;
use App\Puzzles\Day01SolarSweep;
use App\Puzzles\Day02Dive;
use App\Puzzles\Day03BinaryDiagnostic;

require __DIR__ . '/vendor/autoload.php';

echo "====================================\n";
echo "Welcome to Sam's Advent of Code 2021\n";
echo "====================================\n";
echo "\n";

// TODO: load and run these in a nicer way

$puzzles = [
    1 => Day01SolarSweep::class,
    Day02Dive::class,
    Day03BinaryDiagnostic::class
];

foreach ($puzzles as $day => $puzzle_class) {
    /** @var AbstractPuzzle $puzzle */
    $puzzle = new $puzzle_class();
    echo "Day $day\n";
    echo "Part One: {$puzzle->getPartOneAnswer()}\n";
    echo "Part Two: {$puzzle->getPartTwoAnswer()}\n";
    echo "\n";
}
