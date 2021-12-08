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
                'patterns' => array_map(function ($pattern) {
                    $segments = str_split($pattern);
                    sort($segments);
                    return $segments;
                }, explode(' ', $patterns)),
                'output' => array_map(function ($pattern) {
                    $segments = str_split($pattern);
                    sort($segments);
                    return $segments;
                }, explode(' ', $output))
            ];
        }
    }

    public function getPartOneAnswer(): int
    {
        $unique_segments = 0;
        foreach ($this->displays as $display) {
            $unique_segments += count(array_filter($display['output'], function ($signals) {
                return in_array(count($signals), [2, 3, 4, 7]);
            }));
        }

        return $unique_segments;
    }

    private function findNumber(int $number, array $patterns)
    {
        // Map of digit to number of segments
        $segment_counts = [
            1 => 2,
            7 => 3,
            4 => 4,
            8 => 7
        ];

        if (!isset($segment_counts[$number])) {
            throw new \Exception('Number ' . $number . ' not supported');
        }

        return array_values(array_filter($patterns, fn ($pattern) => count($pattern) === $segment_counts[$number]))[0];
    }

    public function getPartTwoAnswer(): int
    {
        $total = 0;

        foreach ($this->displays as $display) {
            // The key of this array is the segment name when shown normally
            $confirmed = [];

            // STEP 1: compare one and seven to allow us identify segment A
            $one = $this->findNumber(1, $display['patterns']);
            $seven = $this->findNumber(7, $display['patterns']);
            $confirmed['a'] = array_values(array_diff($seven, $one))[0];

            // STEP 2: identify segments based on how many times they're shown
            // We can get B, E and F using this
            $segment_appearances = [
                'a' => 0, // 8 normally
                'b' => 0, // 6 [unique]
                'c' => 0, // 8
                'd' => 0, // 7
                'e' => 0, // 4 [unique]
                'f' => 0, // 9 [unique]
                'g' => 0, // 7
            ];

            foreach ($display['patterns'] as $pattern) {
                foreach ($pattern as $segment) {
                    $segment_appearances[$segment]++;
                }
            }

            $confirmed['e'] = array_search(4, $segment_appearances);
            $confirmed['b'] = array_search(6, $segment_appearances);
            $confirmed['f'] = array_search(9, $segment_appearances);

//            $confirmed = ['a', 'e', 'b', 'f'];
//            $confirmed_mixed = array_map(fn ($segment) => $possibilities[$segment], $confirmed);
//
//            foreach ($possibilities as $letter => $remaining_possibilities) {
//                if (in_array($letter, $confirmed)) {
//                    continue;
//                }
//
//                $possibilities[$letter] = array_diff($remaining_possibilities, $confirmed_mixed);
//            }

            // STEP 3: we know F, so identify C based on one
            $confirmed['c'] = array_values(array_diff($one, [$confirmed['f']]))[0];

            // STEP 4: the only unknown segment four contains at this point is D
            $four = $this->findNumber(4, $display['patterns']);
            $confirmed['d'] = array_values(array_diff($four, array_values($confirmed)))[0];

            // STEP 5: G is the last segment
            $eight = $this->findNumber(8, $display['patterns']);
            $confirmed['g'] = array_values(array_diff($eight, array_values($confirmed)))[0];

            // All numbers are known.
            $mixed_to_normal = array_flip($confirmed);
            $output = array_map(function ($segments) use ($mixed_to_normal) {
                $segments = array_map(fn ($segment) => $mixed_to_normal[$segment], $segments);
                sort($segments);
                return implode('', $segments);
            }, $display['output']);

            $normal_number_map = [
                0 => 'abcefg',
                1 => 'cf',
                2 => 'acdeg',
                3 => 'acdfg',
                4 => 'bcdf',
                5 => 'abdfg',
                6 => 'abdefg',
                7 => 'acf',
                8 => 'abcdefg',
                9 => 'abcdfg'
            ];
            $normal_number_map = array_flip($normal_number_map);

            $final_number = '';
            foreach ($output as $segment_string) {
                $final_number .= $normal_number_map[$segment_string];
            }

            $total += (int)$final_number;
        }

        return $total;
    }
}
