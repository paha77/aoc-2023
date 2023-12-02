<?php

const RED_COUNT = 12;
const GREEN_COUNT = 13;
const BLUE_COUNT = 14;

echo array_reduce(
    explode(PHP_EOL, trim(file_get_contents(__DIR__ . '/' . basename(__FILE__, '.php') . (isset($argv[1]) ? '.test.txt' : '.input.txt')))),
    function ($powerCount, $game) {
        $minColorCount = [
            'red' => 0,
            'green' => 0,
            'blue' => 0,
        ];
        preg_match('/^Game ([0-9]+): (.+)$/m', $game, $matches);
        foreach (explode(';', trim($matches[2])) as $draw) {
            foreach (explode(',', trim($draw)) as $color) {
                $colorCount = explode(' ', trim($color));
                $minColorCount[$colorCount[1]] = max($minColorCount[$colorCount[1]], $colorCount[0]);
            }
        }
        return $powerCount + array_reduce($minColorCount, function ($gamePowerCount, $colorCount) {
            return $gamePowerCount * $colorCount;
        }, 1);
    },
    0
);
