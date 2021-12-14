<?php

namespace App\Puzzles;

class Day14ExtendedPolymerization extends AbstractPuzzle
{
    protected static int $day_number = 14;

    private string $template;
    private array $rules = [];
    private array $pairs = [];

    public function __construct()
    {
        parent::__construct();

        $this->template = $this->input->raw_blocks[0];

        $rules = array_map(fn ($rule) => explode(' -> ', $rule), explode("\n", $this->input->raw_blocks[1]));
        foreach ($rules as $rule) {
            // Store the two new pairs that are added when a rule is applied
            $this->rules[$rule[0]] = [
                $rule[0][0] . $rule[1],
                $rule[1] . $rule[0][1]
            ];
        }
    }

    private function growPolymers(): void
    {
        // Take a copy so that we're not modifying the array as we iterate over it
        $pairs_copy = $this->pairs;

        foreach ($pairs_copy as $pair => $count) {
            if (!isset($this->rules[$pair])) {
                continue;
            }

            $new_pairs = $this->rules[$pair];

            $this->pairs[$pair] -= $count;
            $this->pairs[$new_pairs[0]] = ($this->pairs[$new_pairs[0]] ?? 0) + $count;
            $this->pairs[$new_pairs[1]] = ($this->pairs[$new_pairs[1]] ?? 0) + $count;
        }
    }

    private function countElements(): array
    {
        $elements = [];

        foreach ($this->pairs as $pair => $count) {
            // Each pair consists of two elements, but the second element will always be the first element of
            // another pair (except for the end of the string). To avoid double counting elements, only count the
            // elements that are in the first character of a pair.
            $element = $pair[0];
            $elements[$element] = ($elements[$element] ?? 0) + $count;
        }

        // Add one for the last character of the template. Because elements are only inserted between
        // two other elements, the last element will never change.
        $last_char = $this->template[strlen($this->template)-1];
        $elements[$last_char]++;

        asort($elements);
        return $elements;
    }

    private function generatePairsFromTemplate(): void
    {
        $this->pairs = [];

        for ($i = 0; $i < strlen($this->template) - 1; $i++) {
            $pair = substr($this->template, $i, 2);
            $this->pairs[$pair] = ($this->pairs[$pair] ?? 0) + 1;
        }
    }

    public function getPartOneAnswer(): int
    {
        $this->generatePairsFromTemplate();

        for ($i = 1; $i <= 10; $i++) {
            $this->growPolymers();
        }

        $element_count = $this->countElements();
        return array_pop($element_count) - array_shift($element_count);
    }

    public function getPartTwoAnswer(): int
    {
        $this->generatePairsFromTemplate();

        for ($i = 1; $i <= 40; $i++) {
            $this->growPolymers();
        }

        $element_count = $this->countElements();
        return array_pop($element_count) - array_shift($element_count);
    }
}

// NBCCNBBBCBHCB
// NBCCNBBBCBHCB
