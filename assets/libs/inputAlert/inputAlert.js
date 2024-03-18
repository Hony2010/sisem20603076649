$(document).ready(function() {

  mostrarError = function(id){
      if (!$(id).hasClass('input-error')) {
        $(id).addClass("input-error");
        $(id).after('<div id="addon" class="input-group-addon addon-error"><span class="glyphicon glyphicon-remove"></span></div>');
      }
    };

  mostrarWarning = function(id){
    if (!$(id).hasClass('input-warning')) {
      $(id).addClass("input-warning");
      $(id).after('<div id="addon" class="input-group-addon addon-warning"><span class="glyphicon glyphicon-warning-sign"></span></div>');
    }
  };

  mostrarSucces = function (id) {
    if (!$(id).hasClass('input-success')) {
      $(id).addClass("input-success");
      $(id).after('<div id="addon" class="input-group-addon addon-success"><span class="glyphicon glyphicon-ok"></span></div>');
    }
  };

  quitarError = function(id){
    if ($(id).hasClass('input-error')) {
      $(id).removeClass("input-error");
      $('#addon').remove();
    }
  };

  quitarWarning = function(id){
    if ($(id).hasClass('input-warning')) {
      $(id).removeClass("input-warning");
      $('#addon').remove();
    }
  };

  quitarSuccess = function(id){
    if ($(id).hasClass('input-success')) {
      $(id).removeClass("input-success");
      $('#addon').remove();
    }
  };
});
