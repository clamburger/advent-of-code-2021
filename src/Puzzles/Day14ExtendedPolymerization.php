<?php

namespace App\Puzzles;

class Day14ExtendedPolymerization extends AbstractPuzzle
{
    protected static int $day_number = 14;

    public function getPartOneAnswer(): int
    {
        $template = $this->input->raw_blocks[0];
        $rules = array_map(fn ($rule) => explode(' -> ', $rule), explode("\n", $this->input->raw_blocks[1]));

        for ($i = 1; $i <= 10; $i++) {
            foreach ($rules as $rule) {
                $replace = $rule[0][0] . strtolower($rule[1]) . $rule[0][1];
                $template = preg_replace("/{$rule[0]}/", $replace, $template);
                $template = preg_replace("/{$rule[0]}/", $replace, $template);
            }
            $template = strtoupper($template);
        }

        $elements = str_split($template);
        $element_counts = array_count_values($elements);
        asort($element_counts);

        return array_pop($element_counts) - array_shift($element_counts);
    }

    public function getPartTwoAnswer(): int
    {
        return 0;
    }
}

// NBCCNBBBCBHCB
// NBCCNBBBCBHCB
