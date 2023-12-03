<?php

$width = 0;
$height = 0;

$symbols = [];
$numbers = [];

// read the input
foreach (explode(PHP_EOL, trim(file_get_contents(__DIR__ . '/' . basename(__FILE__, '.php') . (isset($argv[1]) ? '.test.txt' : '.input.txt')))) as $line) {

    $actualNumber = null;

    for ($column=0; $column < mb_strlen($line); $column++) {
        $symbol = mb_substr($line, $column, 1);

        // number
        if (preg_match('/[0-9]/', $symbol)) {
            if (is_null($actualNumber)) {
                $actualNumber = $symbol;
            } else {
                $actualNumber .= $symbol;
            }
            continue;
        } else {
            // number end
            if (!is_null($actualNumber)) {
                $numbers[$column - mb_strlen($actualNumber)][$height] = $actualNumber;
                $actualNumber = null;
            }
        }

        // skip the empty cell
        if ($symbol === '.') {
            continue;
        }
        // symbol
        $symbols[$column][$height] = $symbol;
    }

    // number end
    if (!is_null($actualNumber)) {
        $numbers[$column - mb_strlen($actualNumber)][$height] = $actualNumber;
    }

    $width = max(mb_strlen($line), $width);
    $height++;
}

$sum = 0;
foreach ($numbers as $x => $column) {
    foreach ($column as $y => $number) {
        if (!isNumberSurroundedBySymbols($x, $y, mb_strlen($number))) {
            continue;
        }
        $sum += $number;
    }
}

echo $sum . PHP_EOL;

function isNumberSurroundedBySymbols(int $x, int $y, int $length): bool
{
    global $symbols;

    // check the left side
    if (isset($symbols[$x - 1][$y])) {
        return true;
    }

    // check the right side
    if (isset($symbols[$x + $length][$y])) {
        return true;
    }

    // check the top and bottom side
    for ($i = $x - 1; $i <= $x + $length; $i++) {
        // top
        if (isset($symbols[$i][$y - 1])) {
            return true;
        }
        // bottom
        if (isset($symbols[$i][$y + 1])) {
            return true;
        }
    }

    return false;
}



