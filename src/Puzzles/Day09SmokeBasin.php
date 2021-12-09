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

    /**
     * Find the low points (locations in the heightmap lower than its neighbours)
     *
     * @return int
     */
    public function getPartOneAnswer(): int
    {
        $height = count($this->heightmap);
        $width = count($this->heightmap[0]);

        $risk_level_total = 0;

        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $current_height = $this->heightmap[$y][$x];

                // If is neighbour isn't set (because we're on a corner or edge), treat that missing neighbour as a 9.
                // This allows us to avoid doing any extra conditionals or filtering for the (literal) edge cases.
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

    /**
     * Multiply the sizes of the three largest basins together
     *
     * A basin is any group of numbers surrounded by nines (or the edges of the heightmap) on all sides.
     * To find the basins we can just run a flood fill algorithm on each square, skipping the squares we've already
     * visited as part of this process.
     *
     * @return int
     */
    public function getPartTwoAnswer(): int
    {
        $height = count($this->heightmap);
        $width = count($this->heightmap[0]);

        $this->basinmap = array_fill(0, $height, array_fill(0, $width, null));
        $basin = 0;

        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                if ($this->basinmap[$y][$x] === null) {
                    /*
                     * We need a way to identify the different basins, so to do this we just use an incremental
                     * basin number which we pass through to the algorithm. When the flood fill function returns,
                     * we know that we've found one complete basin and can increment the ID.
                     */
                    $this->performFloodFill($x, $y, $basin);
                    $basin++;
                }
            }
        }

        // Now that we know which squares are in which basin, we can throw away all the positional and height data
        // and just do a count on the basin IDs.
        $all_locations = array_reduce($this->basinmap, fn ($carry, $row) => [...$carry, ...$row], []);
        $basins_by_size = array_count_values($all_locations);
        unset($basins_by_size['x']);
        arsort($basins_by_size);

        return array_shift($basins_by_size) * array_shift($basins_by_size) * array_shift($basins_by_size);
    }

    private int $times_called = 0;

    private function performFloodFill(int $x, int $y, int $basin): void
    {
        $this->times_called++;

        // Doing the check here instead of when we call performFloodFill allows us to simplify the logic of
        // checking each neighbour. (It also means that the function is called about three times as much as is strictly
        // needed, but surely it's worth it for cleaner code!)
        if (!isset($this->heightmap[$y][$x])) {
            return;
        }

        $current_height = $this->heightmap[$y][$x];

        // Skip any coords already visited
        if ($this->basinmap[$y][$x] !== null) {
            return;
        }

        if ($current_height == 9) {
            // Nines aren't part of any basin, but we can't just leave it null because that's how we identify if we've
            // already visited the square or not. Instead, identify them with an 'x' so we can easily filter them out
            // of the results later.
            $this->basinmap[$y][$x] = 'x';
            return;
        } else {
            $this->basinmap[$y][$x] = $basin;
        }

        $this->performFloodFill($x - 1, $y, $basin);
        $this->performFloodFill($x, $y - 1, $basin);
        $this->performFloodFill($x + 1, $y, $basin);
        $this->performFloodFill($x, $y + 1, $basin);
    }
}
