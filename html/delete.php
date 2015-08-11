<?php include 'config.php' ?><!DOCTYPE HTML>
<?php
include 'config.php';
include 'http.php';

$r = http_get(COUCHDB.'/_all_dbs');
$names=[];
foreach($r as $db) {
  if(!preg_match("/^_/",$db) && !preg_match('/_history$/',$db)) {
  $names[] = $db;
  }
}

$names = array_unique($names);
sort($names);
?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Exclusão</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script src="<?php echo CONNECT_URL ?>/js/connect.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
  <script type="text/javascript">
    var test = <?php echo (getenv('PHP_ENV')=='test'?'true':'false') ?>;
  </script>
  <script src="del.js" type="text/javascript"></script>
  <style type="text/css">
    .container {
      margin-top: 50px;
    }

    textarea {
      width: 90%;
      height: 250px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Exclusão de dados</h2>
    <?php if( isset($_GET["msg"]) ): ?>
      <p class="msg label label-success"><?php echo $_GET["msg"] ;?></p>
    <?php endif;?>
    <form id="login">
      <div class='form-group'>
        <button id="login-bt" class='btn btn-primary'>Login</button>
        <button id="logout-bt" class='btn btn-primary'>Logout</button>
      </div>
    </form>
    <form action="search.php" method="GET" id="app">
      <fieldset class=''>
        <div class="form-group">
          <label for="src">Origem</label>
          <select id="src" name="src" class='form-control'>
          <?php foreach($names as $db ): ?>
            <option value="<?php echo $db ;?>">
                <?php echo strtoupper(str_replace("_"," ",$db)) ; ?>
            </option>
          <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label for="type">Tipo</label>
          <select id="type" name="type" class='form-control'>
            <option value="taxon">Taxon</option>
            <option value="occurrence">Ocorrência</option>
            <option value="profile">Perfil</option>
            <option value="assessment" selected>Avaliação</option>
          </select>
        </div>
        <div class="form-group">
          <label for="query">Busca</label>
          <input id="query" name="query" class='form-control' type='text'/>
        </div>
        <div class="form-group">
          <button type='submit' class='btn btn-primary'>Buscar</button>
        </div>
      </fieldset>
    </form> 
    <div id="result"></div>
  </div>
</body>
</html>
