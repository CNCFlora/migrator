<?php

include 'config.php';
include 'http.php';

$src = $_POST["src"];
$dst = $_POST["dst"];
$spp = $_POST["spp"];

echo "POST";
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

echo "DOCS";
var_dump($docs);

$json = json_encode(array("docs"=>$docs));
$opts = ['http'=>['method'=>'POST','content'=>$json,'header'=>'Content-type: application/json']];
$r = file_get_contents(COUCHDB."/".$dst."/_bulk_docs", NULL, stream_context_create($opts));

echo "COUCHDB R";
var_dump(json_decode( $r ));

foreach($docs as $doc) {
  $doc->id = $doc->_id;
  #$doc->rev = $doc->_rev;
  unset($doc->_id);
  #unset($doc->_rev);
  $json=json_encode($doc);
  $opts = ['http'=>['method'=>'POST','content'=>$json,'header'=>'Content-type: application/json']];
  $r = file_get_contents(ELASTICSEARCH."/".$dst."/".$doc->metadata->type."/".urlencode($doc->id), NULL, stream_context_create($opts));
  echo "ES";
  var_dump(json_decode($r));
}

if($_POST['copy_or_move'] == 'move') {
  $docs = array();

  foreach($_POST["type"] as $type) {
    $hits = search(ELASTICSEARCH,$src,$type,$q);
    foreach($hits as $hit) $hit->_deleted=true;
    $json = json_encode(array("docs"=>$hits));
    $opts = ['http'=>['method'=>'POST','content'=>$json,'header'=>'Content-type: application/json']];
    $r = file_get_contents(COUCHDB."/".$src."/_bulk_docs", NULL, stream_context_create($opts));
    echo "COUCHDB D";
    var_dump(json_decode( $r ));

    foreach($hits as $doc) {
      $opts = ['http'=>['method'=>'DELETE']];
      $r = file_get_contents(ELASTICSEARCH."/".$src."/".$doc->metadata->type."/".urlencode($doc->_id), NULL, stream_context_create($opts));
      echo "ES D";
      var_dump(json_decode( $r ));
    }
  }

}

var_dump("DONE");

#header("Location: index.php");

