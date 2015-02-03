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

$couchdb_ip=getenv("COUCHDB_PORT_5984_TCP_ADDR");
if($couchdb_ip) {
  define("COUCHDB","http://".$couchdb_ip.":5984");
}

$elasticsearch_ip=getenv("ELASTICSEARCH_PORT_5984_TCP_ADDR");
if($elasticsearch_ip) {
  define("ELASTICSEARCH","http://".$elasticsearch_ip.":5984");
}

$array = $raw[$env];

foreach($array as $k=>$v) {
  if(!defined(strtoupper($k))) {
    define(strtoupper($k),$v);
  }
}

