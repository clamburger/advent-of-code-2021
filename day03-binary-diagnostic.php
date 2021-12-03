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
