<?php

include 'config.php';
include 'http.php';

$term = trim($_GET["term"]);

$result = search(ELASTICSEARCH,'_all','taxon',$term."*");
$names=[];
foreach($result as $taxon) {
  $names[] = $taxon->scientificNameWithoutAuthorship;
}

sort($names);
echo json_encode(array_unique($names));

