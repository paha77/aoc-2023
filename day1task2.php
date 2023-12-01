<?php

// https://adventofcode.com/2023/day/1#part2
$numbers = [
    'one',
    'two',
    'three',
    'four',
    'five',
    'six',
    'seven',
    'eight',
    'nine',
];

$revNumbers = array_map(function ($number) {
    return strrev($number);
}, $numbers);

echo array_reduce(
    explode(PHP_EOL, trim(file_get_contents(__DIR__ . '/' . basename(__FILE__, '.php') . (isset($argv[1]) ? '.test.txt' : '.input.txt')))),
    function ($carry, $item) use ($numbers, $revNumbers) {
        preg_match('/([0-9]|' . implode('|', $numbers) . ')/m', $item, $matches);
        $d1 = $matches[1];

        preg_match('/([0-9]|' . implode('|', $revNumbers) . ')/m', strrev($item), $matches);
        $d2 = $matches[1];

        if (strlen($d1) > 1) {
            $d1 = (array_search($d1, $numbers) ?: 0) + 1;
        } else {
            $d1 = intval($d1);
        }

        if (strlen($d2) > 1) {
            $d2 = (array_search($d2, $revNumbers) ?: 0) + 1;
        } else {
            $d2 = intval($d2);
        }

        // summarize the leftmost and rightmost digits
        return $carry + 10 * $d1 + $d2;
    },
    0
);
