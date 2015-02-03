<?php

include_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;
$raw = Yaml::parse(file_get_contents(__DIR__."/../config.yml"));

$env = getenv("PHP_ENV");
if($env == null) {
    $env = 'development';
}

if(isset($_SERVER) && isset($_SERVER['HTTP_HOST'])) {
    if($_SERVER['HTTP_HOST'] == 'test.localhost') {
        $env = "test";
    }
}

$data["ENV"] = $env;
$data["TEST"]=($env=='test');

$array = $raw[$env];

foreach($array as $k=>$v) {
  if(!defined($k)) {
    define(strtoupper($k),$v);
  }
}

