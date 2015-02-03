<?php include 'config.php' ?><!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Migração</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
  <style type="text/css">
    .container {
      margin-top: 50px;
    }
  </style>
</head>
<body>
  <div class="container">
    <form action="migrate.php" method="POST">
      <fieldset class=''>
        <legend>Migrar dados entre recortes</legend>
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
  <script type="text/javascript">
  $.ajax({
    url: '<?php echo COUCHDB_URL ?>/_all_dbs?callback=?',
    method: 'GET',
    dataType: 'jsonp',
    success: function(dbs) {
      console.log(dbs)
      for(var d=0;d<dbs.length;d++) {
        if(!dbs[d].match(/^_/) && !dbs[d].match(/_history$/)) {
          $("#src,#dst").append("<option value='"+dbs[d]+"'>"+dbs[d].toUpperCase().replace("_"," ")+"</option>");
        }
      }
    },
    error: function(a,b) {
      console.log(a,b);
    }
  });
  </script>
</body>
</html>
