<?php

namespace App;

use App\Puzzles\AbstractPuzzle;
use App\Puzzles\Day01SolarSweep;
use App\Puzzles\Day02Dive;
use App\Puzzles\Day03BinaryDiagnostic;
use App\Puzzles\Day04GiantSquid;

class PuzzleRunner
{
    /** @var AbstractPuzzle[] */
    private array $puzzles;

    public function __construct()
    {
        $this->puzzles = [
            1 => new Day01SolarSweep(),
            new Day02Dive(),
            new Day03BinaryDiagnostic(),
            new Day04GiantSquid()
        ];
    }

    public function getPuzzles(): array
    {
        return $this->puzzles;
    }
}
