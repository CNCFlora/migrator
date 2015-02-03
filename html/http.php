<?php

function http_get($url) {
  return json_decode(file_get_contents($url));
}

function http_post($url,$doc) {
  $opts = ['http'=>['method'=>'POST','content'=>json_encode($doc),'header'=>'Content-type: application/json']];
  $r = file_get_contents($url, NULL, stream_context_create($opts));
  return json_decode($r);
}

function http_put($url,$doc) {
  $opts = ['http'=>['method'=>'PUT','content'=>json_encode($doc),'header'=>'Content-type: application/json']];
  $r = file_get_contents($url, NULL, stream_context_create($opts));
  return json_decode($r);
}

function http_delete($url) {
  $opts = ['http'=>['method'=>'DELETE']];
  $r = file_get_contents($url, NULL, stream_context_create($opts));
  return json_decode($r);
}


function search($es,$db,$idx,$q) {
  $q = str_replace("=",":",$q);
  $url = $es.'/'.$db.'/'.$idx.'/_search?size=9999&q='.urlencode($q);
  var_dump($url);
  $r = http_get($url);
  $arr =array();
  $ids = [];
  foreach($r->hits->hits as $hit) {
      $doc = $hit->_source;
      $doc->_id = $doc->id;
      $doc->_rev = $doc->rev;
      unset($doc->id);
      unset($doc->rev);
      $arr[] = $doc;
  }

  return $arr;
}

