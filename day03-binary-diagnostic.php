<?php
$numbers = file(__DIR__ . '/day03.input.txt');
$numbers = array_map('trim', $numbers);

/**
 *
 * Part One
 *
 */

$bits = array_map(fn ($number) => str_split($number, 1), $numbers);

$bit_count = count($bits[0]);

$gamma_rate = '';
$epsilon_rate = '';

for ($i = 0; $i < $bit_count; $i++) {
    $bits_in_column = array_column($bits, $i);
    $counts = array_count_values($bits_in_column);

    if ($counts[0] > $counts[1]) {
        $gamma_rate .= '0';
        $epsilon_rate .= '1';
    } else {
        $gamma_rate .= '1';
        $epsilon_rate .= '0';
    }
}

$gamma_rate = bindec($gamma_rate);
$epsilon_rate = bindec($epsilon_rate);
$power_consumption = $gamma_rate * $epsilon_rate;

echo $power_consumption . "\n";



/**
 *
 * Part Two
 *
 */

function get_most_or_least_common_bit_in_position_n(array $bits, int $position, bool $most): int
{
    $bits_in_column = array_column($bits, $position);
    $counts = array_count_values($bits_in_column);

    if ($most) {
        $bit_result = array_keys($counts, max($counts), true);
    } else {
        $bit_result = array_keys($counts, min($counts), true);
    }

    // If $most_common_bit contains two values, that means both 0 and 1 are equally present.
    // As per the spec, we return 1 if this occurs.
    if (count($bit_result) > 1) {
        return $most ? 1 : 0;
    }

    return $bit_result[0];
}

//
// Calculate the oxygen generator rating
//

$candidates = $bits;
$bit_index = 0;

while (count($candidates) > 1) {
    $most_common_bit = get_most_or_least_common_bit_in_position_n($candidates, $bit_index, true);

    // Only keep the candidates where the bit in position N matches the most common bit
    $candidates = array_filter($candidates, function (array $candidate) use ($bit_index, $most_common_bit) {
        return $candidate[$bit_index] == $most_common_bit;
    });

    $bit_index++;
}

$oxygen_generator_rating = bindec(implode('', array_values($candidates)[0]));

//
// Calculate the CO2 scrubber rating
//

$candidates = $bits;
$bit_index = 0;

while (count($candidates) > 1) {
    $least_common_bit = get_most_or_least_common_bit_in_position_n($candidates, $bit_index, false);

    // Only keep the candidates where the bit in position N matches the least common bit
    $candidates = array_filter($candidates, function (array $candidate) use ($bit_index, $least_common_bit) {
        return $candidate[$bit_index] == $least_common_bit;
    });

    $bit_index++;
}

$co2_scrubber_rating = bindec(implode('', array_values($candidates)[0]));

$life_support_rating = $oxygen_generator_rating * $co2_scrubber_rating;

echo $life_support_rating . "\n";
