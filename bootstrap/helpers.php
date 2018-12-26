<?php

function view(...$args)
{
    return new \Core\View(...$args);
}

function redirect(...$args)
{
    return new \Core\Redirect(...$args);
}

function response(...$args)
{
    return new \Core\Response(...$args);
}

function vmessage(...$args)
{
    return new \Core\Validator\ValidatorMessage(...$args);
}

function vfield($name)
{
    return \Core\Validator\ValidatorFields::get($name);
}

function storage(string $storageName)
{
    return new \Core\Storage($storageName);
}
