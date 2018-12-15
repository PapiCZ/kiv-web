<?php

function view(...$args) {
    return new \Core\View(...$args);
}

function redirect(...$args) {
    return new \Core\Redirect(...$args);
}
