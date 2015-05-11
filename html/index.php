<?php include 'config.php' ?><!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Migração</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script src="<?php echo CONNECT_URL ?>/js/connect.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
  <script type="text/javascript">
    var couchdb = '<?php echo COUCHDB_URL ?>';
  </script>
  <script src="app.js" type="text/javascript"></script>
  <style type="text/css">
    .container {
      margin-top: 50px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Migração de dados</h2>
    <?php if( isset($_GET["msg"]) ): ?>
      <p class="msg label label-success"><?php echo $_GET["msg"] ;?></p>
    <?php endif;?>
    <form id="login">
      <div class='form-group'>
        <button id="login-bt" class='btn btn-primary'>Login</button>
        <button id="logout-bt" class='btn btn-primary'>Logout</button>
      </div>
    </form>
    <form action="migrate.php" method="POST" id="app">
      <fieldset class=''>
        <div class="form-group">
          <label for="spp">Espécie</label>
          <input id="spp" name="spp" type="text" placeholder="Aphelandra longiflora" class='form-control'/>
        </div>
        <div class="form-group">
          <label for="src">Origem</label>
          <select id="src" name="src" class='form-control'></select>
        </div>
        <div class="form-group">
          <label for="dst">Destino</label>
          <select id="dst" name="dst" class='form-control'></select>
        </div>
        <div class="form-group">
          <label for="copy_or_move">Copiar ou mover</label>
          <select id="copy_or_move" name="copy_or_move" class="form-control">
            <option value="copy">Copiar</option>
            <option value="move">Mover</option>
          </select>
        </div>
        <div class="form-group">
          <input id="taxon" value="taxon" name="type[]" type="checkbox" />
          <label for="taxon">Taxon</label>
          <br />
          <input id="occurrences" value="occurrence" name="type[]" type="checkbox" />
          <label for="occurrences">Ocorrência</label>
          <br />
          <input id="profile" value="profile" name="type[]" type="checkbox" />
          <label for="profile">Perfil</label>
          <br />
          <input id="assessment" value="assessment" name="type[]" type="checkbox" />
          <label for="assessment">Avaliação</label>
        </div>
        <p><button class='btn btn-primary'>Migrar</button></p>
      </fieldset>
    </form> 
  </div>
</body>
</html>
