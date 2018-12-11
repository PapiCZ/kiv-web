<?php

$handle = fopen(__DIR__ . '/../.env', 'r');
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        putenv($line);
    }

    fclose($handle);
}
