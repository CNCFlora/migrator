<?php

include 'config.php';
include 'http.php';

$db = trim($_GET["db"]);

$result = search(ELASTICSEARCH,$db,'taxon',"*");
$names=[];
foreach($result as $taxon) {
  $names[] = strtoupper( trim( $taxon->family ) );
}

sort($names);
echo json_encode(array_unique($names));

