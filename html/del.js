window.onload = function() {

  var logged =true;

  if(!test) {
    logged=false;

    $("#logout-bt").hide();
    $("#app,#result").hide();

    Connect({
        onlogin: function(user) {
          for(var i =0;i<user.roles.length;i++) {
            var context = user.roles[i];
            for(var j=0;j<context.roles.length;j++) {
              var role = context.roles[j];
              var role_name = role.role;
              if(role_name == 'admin') {
                logged=true;
              }
            }
          }
          if(logged) {
            $("#login-bt").hide();
            $("#logout-bt").show();
            $("#app,#result").show();
          }
        },
        onlogout: function() {
          if(logged) {
            $("#login-bt").show();
            $("#logout-bt").hide();
            $("#app,#result").hide();
          }
        }
    });

    $("#login").submit(function(){
        return false;
    });
    $("#logout-bt").click(function(){
        Connect.logout();
    });
    $("#login-bt").click(function(){
        Connect.login();
    });
  }

  function get(url,fn) {
    $.ajax({
      url: url,
      method: 'GET',
      success: function(d) { fn(d); },
      error: function(a,b) { console.log(a,b); }
    });
  }

  $("#result").on('click','a[rel=del]',function(){
    return test || confirm('Tem certeza? isso não pode ser desfeito.');
  });

  $("#result").on('click','a[rel=save]',function(){
    // validate
    return test || confirm('Tem certeza? isso não pode ser desfeito.');
  });

  $("#app").submit(function(){
    $("#result").html("Loading...");
    get('search.php?src='+$("#src").val()+'&type='+$("#type").val()+'&query='+encodeURIComponent($("#query").val()),function(data){
        if(data.length == 0 ) {
          $("#result").html("<p>Nada encontrado.</p>");
        } else {
          $("#result").html("");
        }
        for(var i=0;i<data.length;i++) {
          var html='<div>';
          html += '<strong>'+data[i]._id+'</strong><br />';
          html += "<textarea>"+JSON.stringify(data[i],null,2)+"</textarea><br />";
          //html += "<a href='#save' rel='save'> salvar </a> -";
          var url = "del.php?src="+$("#src").val()
                      +"&type="+$("#type").val()
                      +"&id="+encodeURIComponent(data[i]._id)
                      +"&rev="+encodeURIComponent(data[i]._rev)
                      +"&query="+encodeURIComponent($("#query").val())+"";
          html += "- <a href='"+url+"' rel='del'>excluir "+ data[i]._id +"</a>";
          html += "</div><br /><br />";
          $("#result").append(html);
        }
    });
    return false;
  });
  //$("#app").submit();
}

