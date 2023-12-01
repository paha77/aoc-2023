<?php

// https://adventofcode.com/2023/day/1
echo array_reduce(
        explode(PHP_EOL, trim(file_get_contents(__DIR__ . '/' . basename(__FILE__, '.php') . (isset($argv[1]) ? '.test.txt' : '.input.txt')))),
        function ($carry, $item) {
            // remove non-alphanumeric characters
            $item = preg_replace('/[^0-9]/', '', $item);
            // summarize the leftmost and rightmost digits
            return $carry + 10 * $item[0] + $item[strlen($item) - 1];
        },
        0
);
