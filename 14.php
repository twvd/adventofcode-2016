<?php
function md5stretch(string $h, int $stretch = 0) {
    $stretch++;
    while ($stretch--) { $h = md5($h); }
    return $h;
}

// Hash calculation function that keeps a cache of results
function getHash($i) {
    global $hashCache, $salt, $stretchCount;
    if (!isset($hashCache[$i])) { $hashCache[$i] = md5stretch($salt.$i, $stretchCount); }
    return $hashCache[$i];
}

// Pass puzzle input on the command line
if ($argc < 2) {
    echo 'Usage: ' . $argv[0] . ' <salt> [stretch count (2016 for second puzzle)]' . PHP_EOL;
    exit();
}

$salt = $argv[1];
$stretchCount = (!isset($argv[2]) ? 0 : $argv[2]);

$hashesFound = 0;
$hashCache = [];

for ($index = 0; $hashesFound < 64; $index++) {
    $currentHash = getHash($index);

    // Find if there are 3 consequent characters
    if (preg_match('/([a-z0-9])\1\1/', $currentHash, $match)) {
        for ($nidx = 1; $nidx < 1000; $nidx++) {
            $currentHash5 = getHash($index + $nidx);

            // Check for 5 consequent characters
            if (preg_match('/([a-z0-9])\1\1\1\1/', $currentHash5, $match5)) {
                if ($match5[1] == $match[1]) {
                    $hashesFound++;
                    printf('%02d: %d (%s)' . PHP_EOL, $hashesFound, $index, $currentHash);
                    break;
                }
            }
        }
    }
}