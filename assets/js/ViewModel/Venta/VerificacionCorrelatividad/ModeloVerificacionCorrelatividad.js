ModeloVerificacionCorrelatividad = function (data) {
    var self = this;
    var base = data;
  
    self.InicializarModelo = function (event) {
      if(event) {
      }
    }

    self.RegistarVerificacionCorrelatividad  = function (data, event, callback) {
      if (event) {
        $.ajax({
          type: 'POST',
          data : data,
          dataType : "json",
          url: SITE_URL+'/Venta/cVerificacionCorrelatividad/VerificarCorrelatividadTipo',
          success: function (data) {
            callback(data,event);
          },
          error :  function (jqXHR, textStatus, errorThrown) {
            var data = {error:{msg:jqXHR.responseText}};
            callback(data,event);
          }
        });
      }
    }
    
  }
  