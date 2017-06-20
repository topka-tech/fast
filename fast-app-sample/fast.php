<?php

require_once __DIR__.'/vendor/autoload.php';

foreach ($argv as $arg) {
    $e=explode("=",$arg);
    if(count($e)==2)
        $_GET[$e[0]]=$e[1];
    else
        $_GET[]=$e[0];
}


$app = \App\App::instance(include(__DIR__."/Config/Config.php"));

if(array_key_exists("--port", $_GET))
{
    $app->setPort($_GET['--port']);
}

$app->start();
