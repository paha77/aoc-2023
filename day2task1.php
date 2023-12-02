<?php

const RED_COUNT = 12;
const GREEN_COUNT = 13;
const BLUE_COUNT = 14;

echo array_reduce(
    explode(PHP_EOL, trim(file_get_contents(__DIR__ . '/' . basename(__FILE__, '.php') . (isset($argv[1]) ? '.test.txt' : '.input.txt')))),
    function ($validCount, $game) {
        preg_match('/^Game ([0-9]+): (.+)$/m', $game, $matches);
        foreach (explode(';', trim($matches[2])) as $draw) {
            foreach (explode(',', trim($draw)) as $color) {
                $colorCount = explode(' ', trim($color));
                switch (true) {
                    case $colorCount[1] === 'red' && (int)$colorCount[0] > RED_COUNT:
                    case $colorCount[1] === 'green' && (int)$colorCount[0] > GREEN_COUNT:
                    case $colorCount[1] === 'blue' && (int)$colorCount[0] > BLUE_COUNT:
                        return $validCount;
                }
            }
        }
        return $validCount + (int)$matches[1];
    },
    0
);
