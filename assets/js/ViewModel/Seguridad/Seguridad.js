
SeguridadModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);
    
    self.OnFocus = function(data,event,callback) {
      if(event)  {
          $(event.target).select();
          if(callback) callback(data,event);
      }
    }

    self.OnKeyEnter = function(data,event) {
      if(event) {
         var resultado = $(event.target).enterToTab(event);
         return resultado;
      }
    }
}


var Mapping = {
    'Seguridad': {
        create: function (options) {
            return new SeguridadModel(options.data);
        }
    }
}

Index = function (data) {

    var self = this;
    ko.mapping.fromJS(data, Mapping, self);

    self.Login = function () {
      $("#loader").show();
      var usuario = $('#usuario').val();
      var clave = $('#clave').val();

        //var data = ko.toJS({"Data" : self.Seguridad});

        $.ajax({
                type: 'POST',
                data : {"nombreusuario": usuario, "claveusuario": clave},
                dataType: "json",
                url: SITE_URL+'/Seguridad/cSeguridad/Login',
                success: function (data) {
                    if(data[0].IdUsuario)
                    {
                      console.log(data);
                      window.location.href = "../../cDashBoard/";
                    }
                    else
                    {
                        alertify.alert(data);
                        console.log(data);
                    }
                    $("#loader").hide();
                },
                error : function (jqXHR, textStatus, errorThrown) {
                  //console.log(jqXHR.responseText);
                  $("#loader").hide();
                }
        });
    }

}
