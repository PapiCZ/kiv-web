<?php

$handle = fopen(__DIR__ . '/../.env', 'r');
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        if (substr($line, -1) == "\n") {
            $line = substr($line, 0, -1);
        } elseif (substr($line, -2) == "\r\n") {
            $line = substr($line, 0, -2);
        }

        if (preg_match('/^(\w+)=(.+)?$/', $line)) {
            putenv($line);
        }
    }

    fclose($handle);
}
