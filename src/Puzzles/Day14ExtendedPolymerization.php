<?php

namespace App\Puzzles;

class Day14ExtendedPolymerization extends AbstractPuzzle
{
    protected static int $day_number = 14;

    private string $template;
    private array $rules;

    public function __construct()
    {
        parent::__construct();

        $this->template = $this->input->raw_blocks[0];
        $this->rules = array_map(fn ($rule) => explode(' -> ', $rule), explode("\n", $this->input->raw_blocks[1]));
    }

    public function getPartOneAnswer(): int
    {
        $template = $this->template;

        for ($i = 1; $i <= 10; $i++) {
            foreach ($this->rules as $rule) {
                $replace = $rule[0][0] . strtolower($rule[1]) . $rule[0][1];
                $template = str_replace($rule[0], $replace, $template);
                $template = str_replace($rule[0], $replace, $template);
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
        $pairs = [];
        $rules = [];

        foreach ($this->rules as $rule) {
            // Store the two new pairs that are added when a rule is applied
            $rules[$rule[0]] = [
                $rule[0][0] . $rule[1],
                $rule[1] . $rule[0][1]
            ];
        }

        for ($i = 0; $i < strlen($this->template) - 1; $i++) {
            $pair = substr($this->template, $i, 2);
            $pairs[$pair] = ($pairs[$pair] ?? 0) + 1;
        }

        for ($i = 1; $i <= 40; $i++) {
            $pairs_copy = $pairs;
            foreach ($pairs_copy as $pair => $count) {
                if (!isset($rules[$pair])) {
                    continue;
                }

                $new_pairs = $rules[$pair];

                $pairs[$pair] -= $count;
                $pairs[$new_pairs[0]] = ($pairs[$new_pairs[0]] ?? 0) + $count;
                $pairs[$new_pairs[1]] = ($pairs[$new_pairs[1]] ?? 0) + $count;
            }
        }

        $elements = [];

        foreach ($pairs as $pair => $count) {
            $element = $pair[0];
            $elements[$element] = ($elements[$element] ?? 0) + $count;
        }

        $last_char = $this->template[strlen($this->template)-1];
        $elements[$last_char]++;

        asort($elements);

        return array_pop($elements) - array_shift($elements);
    }
}

// NBCCNBBBCBHCB
// NBCCNBBBCBHCB
