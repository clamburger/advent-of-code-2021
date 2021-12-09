<?php

namespace App\Puzzles;

class Day09SmokeBasin extends AbstractPuzzle
{
    protected static int $day_number = 9;

    private array $heightmap;
    private array $basinmap;

    public function __construct()
    {
        parent::__construct();
        $this->heightmap = array_map(fn ($line) => str_split($line), $this->input->lines);
    }

    public function getPartOneAnswer(): int
    {
        $height = count($this->heightmap);
        $width = count($this->heightmap[0]);

        $risk_level_total = 0;

        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $current_height = $this->heightmap[$y][$x];

                $neighbours = [
                    $this->heightmap[$y][$x - 1] ?? 9,
                    $this->heightmap[$y - 1][$x] ?? 9,
                    $this->heightmap[$y][$x + 1] ?? 9,
                    $this->heightmap[$y + 1][$x] ?? 9,
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
        $height = count($this->heightmap);
        $width = count($this->heightmap[0]);

        $this->basinmap = array_fill(0, $height, array_fill(0, $width, null));
        $basin = 0;

        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                if ($this->basinmap[$y][$x] === null) {
                    $this->performFloodFill($x, $y, $basin);
                    $basin++;
                }
            }
        }

        $all_locations = array_reduce($this->basinmap, fn ($carry, $row) => [...$carry, ...$row], []);
        $basins_by_size = array_count_values($all_locations);
        unset($basins_by_size['x']);
        arsort($basins_by_size);

        return array_shift($basins_by_size) * array_shift($basins_by_size) * array_shift($basins_by_size);
    }

    private function performFloodFill(int $x, int $y, int $basin): void
    {
        $current_height = $this->heightmap[$y][$x];

        if ($this->basinmap[$y][$x] !== null) {
            return;
        }

        if ($current_height == 9) {
            $this->basinmap[$y][$x] = 'x';
            return;
        } else {
            $this->basinmap[$y][$x] = $basin;
        }

        if (isset($this->heightmap[$y][$x-1])) {
            $this->performFloodFill($x-1, $y, $basin);
        }
        if (isset($this->heightmap[$y-1][$x])) {
            $this->performFloodFill($x, $y-1, $basin);
        }
        if (isset($this->heightmap[$y][$x+1])) {
            $this->performFloodFill($x+1, $y, $basin);
        }
        if (isset($this->heightmap[$y+1][$x])) {
            $this->performFloodFill($x, $y+1, $basin);
        }
    }
}
