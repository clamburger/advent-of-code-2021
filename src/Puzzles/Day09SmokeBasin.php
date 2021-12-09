<?php

namespace App\Puzzles;

class Day09SmokeBasin extends AbstractPuzzle
{
    protected static int $day_number = 9;

    public function getPartOneAnswer(): int
    {
        $heightmap = array_map(fn ($line) => str_split($line), $this->input->lines);

        $height = count($heightmap);
        $width = count($heightmap[0]);

        $risk_level_total = 0;

        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $current_height = $heightmap[$y][$x];

                $neighbours = [
                    $heightmap[$y][$x - 1] ?? 9,
                    $heightmap[$y - 1][$x] ?? 9,
                    $heightmap[$y][$x + 1] ?? 9,
                    $heightmap[$y + 1][$x] ?? 9,
                ];

                if ($current_height < min($neighbours)) {
                    $risk_level = $current_height + 1;
                    $risk_level_total += $risk_level;
                }
            }
        }

        return $risk_level_total;
    }

    public function getPartTwoAnswer(): int
    {
        return 0;
    }
}
