<?php

include 'config.php';
include 'http.php';

$src = $_POST["src"];
$dst = $_POST["dst"];
$spp = $_POST["spp"];

var_dump($_POST);

$docs=[];

$q = '"'.$spp.'"';
foreach($_POST["type"] as $type) {
  $hits = search(ELASTICSEARCH,$src,$type,$q);
  foreach($hits as $hit){
    unset($hit->_rev);
    $docs[]=$hit;
  }
}

var_dump($docs);

$json = json_encode(array("docs"=>$docs));
$opts = ['http'=>['method'=>'POST','content'=>$json,'header'=>'Content-type: application/json']];
$r = file_get_contents(COUCHDB."/".$dst."/_bulk_docs", NULL, stream_context_create($opts));
var_dump(json_decode( $r ));

if($_POST['copy_or_move'] == 'move') {
  $docs = array();

  foreach($_POST["type"] as $type) {
    $hits = search(ELASTICSEARCH,$src,$type,$q);
    foreach($hits as $hit) $hit->_deleted=true;
    $json = json_encode(array("docs"=>$hits));
    $opts = ['http'=>['method'=>'POST','content'=>$json,'header'=>'Content-type: application/json']];
    $r = file_get_contents(COUCHDB."/".$src."/_bulk_docs", NULL, stream_context_create($opts));
    var_dump(json_decode( $r ));
  }

}

var_dump("DONE");

#header("Location: index.php");

