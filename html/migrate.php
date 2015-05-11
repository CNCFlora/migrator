<?php

include 'config.php';
include 'http.php';

$src = $_POST["src"];
$dst = $_POST["dst"];
$spp = trim( $_POST["spp"] );

$synonyms = search(ELASTICSEARCH,$src,"taxon","acceptedNameUsage:\"".$spp."*\" AND taxonomicStatus:\"synonym\"");

$q = '"'.$spp.'*" ';
foreach($synonyms as $syn) {
  $q .= " OR \"".$syn->scientificNameWithoutAuthorship."*\"";
}

$docs=[];

foreach($_POST["type"] as $type) {
  $hits = search(ELASTICSEARCH,$src,$type,$q);
  foreach($hits as $hit){
    unset($hit->_rev);
    $docs[]=$hit;
  }
}

$json = json_encode(array("docs"=>$docs));
$opts = ['http'=>['method'=>'POST','content'=>$json,'header'=>'Content-type: application/json']];
$r = file_get_contents(COUCHDB."/".$dst."/_bulk_docs", NULL, stream_context_create($opts));

foreach($docs as $doc) {
  $doc->id = $doc->_id;
  unset($doc->_id);
  $json=json_encode($doc);
  $opts = ['http'=>['method'=>'POST','content'=>$json,'header'=>'Content-type: application/json']];
  $r = file_get_contents(ELASTICSEARCH."/".$dst."/".$doc->metadata->type."/".urlencode($doc->id), NULL, stream_context_create($opts));
}

if($_POST['copy_or_move'] == 'move') {
  $docs = array();

  foreach($_POST["type"] as $type) {
    $hits = search(ELASTICSEARCH,$src,$type,$q);

    foreach($hits as $hit) $hit->_deleted=true;
    $json = json_encode(array("docs"=>$hits));
    $opts = ['http'=>['method'=>'POST','content'=>$json,'header'=>'Content-type: application/json']];
    $r = file_get_contents(COUCHDB."/".$src."/_bulk_docs", NULL, stream_context_create($opts));

    foreach($hits as $doc) {
      $opts = ['http'=>['method'=>'DELETE']];
      $r = file_get_contents(ELASTICSEARCH."/".$src."/".$doc->metadata->type."/".urlencode($doc->_id), NULL, stream_context_create($opts));
    }
  }

}

header("Location: index.php?msg=Espécies migradas");

