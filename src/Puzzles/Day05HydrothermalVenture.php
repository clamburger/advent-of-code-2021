<?php

namespace App\Puzzles;

class Day05HydrothermalVenture extends AbstractPuzzle
{
    protected static int $day_number = 5;

    public function getPartOneAnswer(): int
    {
        $danger_zones = [];

        foreach ($this->input->lines as $vent) {
            $raw_coords = explode(' -> ', $vent);

            list($x1, $y1) = explode(',', $raw_coords[0]);
            list($x2, $y2) = explode(',', $raw_coords[1]);

            if ($x1 !== $x2 && $y1 !== $y2) {
                continue;
            }

            if ($y1 === $y2) {
                for ($x = $x1; $x != (int)$x2 + ($x2 <=> $x1); $x += $x2 <=> $x1) {
                    if (!isset($danger_zones["$x,$y1"])) {
                        $danger_zones["$x,$y1"] = 0;
                    }
                    $danger_zones["$x,$y1"]++;
                }
            }

            if ($x1 === $x2) {
                for ($y = $y1; $y != (int)$y2 + ($y2 <=> $y1); $y += $y2 <=> $y1) {
                    if (!isset($danger_zones["$x1,$y"])) {
                        $danger_zones["$x1,$y"] = 0;
                    }
                    $danger_zones["$x1,$y"]++;
                }
            }
        }

        return count(array_filter($danger_zones, fn ($number) => $number >= 2));
    }

    public function getPartTwoAnswer(): int
    {
        $danger_zones = [];

        foreach ($this->input->lines as $vent) {
            $raw_coords = explode(' -> ', $vent);

            list($x1, $y1) = explode(',', $raw_coords[0]);
            list($x2, $y2) = explode(',', $raw_coords[1]);

            // Diagonal
            if ($x1 !== $x2 && $y1 !== $y2) {
                for ($x = $x1, $y = $y1; $x != (int)$x2 + ($x2 <=> $x1); $x += $x2 <=> $x1, $y += $y2 <=> $y1) {
                    if (!isset($danger_zones["$x,$y"])) {
                        $danger_zones["$x,$y"] = 0;
                    }
                    $danger_zones["$x,$y"]++;
                }
            }

            // Horizontal
            if ($y1 === $y2) {
                for ($x = $x1; $x != (int)$x2 + ($x2 <=> $x1); $x += $x2 <=> $x1) {
                    if (!isset($danger_zones["$x,$y1"])) {
                        $danger_zones["$x,$y1"] = 0;
                    }
                    $danger_zones["$x,$y1"]++;
                }
            }

            // Vertical
            if ($x1 === $x2) {
                for ($y = $y1; $y != (int)$y2 + ($y2 <=> $y1); $y += $y2 <=> $y1) {
                    if (!isset($danger_zones["$x1,$y"])) {
                        $danger_zones["$x1,$y"] = 0;
                    }
                    $danger_zones["$x1,$y"]++;
                }
            }
        }

        return count(array_filter($danger_zones, fn ($number) => $number >= 2));
    }
}
