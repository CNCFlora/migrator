window.onload = function() {

  var logged =false;

  $("#logout-bt").hide();
  $("#app").hide();

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
          $("#app").show();
        }
      },
      onlogout: function() {
        if(logged) {
          $("#login-bt").show();
          $("#logout-bt").hide();
          $("#app").hide();
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
  
  $("#spp").autocomplete({
      source: function (request, response) {
          $.getJSON("complete.php?term="+request.term, function (data) {
              //processResu.resultlt(data, response, request.term);
              console.log(data);
              response(data);
          });
      }
  });
    function processResult(data, callback, searchTerm) {
      callback(
        $.map(data, 
          function (value) {
            var inputAutocomplete = value.scientificNameWithoutAuthorship.toLowerCase();
            if( inputAutocomplete.indexOf( searchTerm.toLowerCase() ) != -1 
                && value.family.length >= 1) {
                return value = value.scientificNameWithoutAuthorship;
            }
        }));
    };

  $.ajax({
    url: couchdb+'/_all_dbs?callback=?',
    method: 'GET',
    dataType: 'jsonp',
    success: function(dbs) {
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
};
