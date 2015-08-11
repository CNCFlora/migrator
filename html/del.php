<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Resultado exclusão</title>
</head>
<body>
<?php

include 'config.php';
include 'http.php';

echo '<pre>';
var_dump($_GET);
echo '</pre>';
echo '<br />';
if(strlen(trim($_GET["id"])) < 3 || strlen(trim($_GET["rev"])) < 5) {
  echo 'BAD REQUEST';
  exit;
}

$r = http_delete(ELASTICSEARCH.'/'.$_GET["src"].'/'.$_GET['type'].'/'.rawurlencode($_GET['id']));
echo '<pre>';
var_dump($r);
echo '</pre>';
echo '<br />';

$r = http_delete(COUCHDB.'/'.$_GET["src"].'/'.rawurlencode($_GET['id'])."?rev=".$_GET['rev']);
echo '<pre>';
var_dump($r);
echo '</pre>';
echo '<br />';

//header("Location: delete.php?src=".$_GET["src"]."&type=".$_GET["type"]."&query=".rawurlencode($_GET["query"]));
echo '<a href="javascript:history.back();">VOLTAR</a>';

?>
</body>
</html>
