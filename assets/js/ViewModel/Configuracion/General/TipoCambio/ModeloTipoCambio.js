ModeloTipoCambio = function (data) {
  var self = this;
  var base = data;

  self.InicializarModelo = function() {

  }

  self.ObtenerTipoCambio = function(data,callback){
    if(callback)
    {
      var datajs = ko.toJS({"Data": { "FechaEmision" : data.FechaEmision()}});

      $.ajax({
          method: 'GET',
          data : datajs,
          dataType: 'json',
          url: SITE_URL+'/Configuracion/General/cTipoCambio/ObtenerTipoCambio',
          success: function(data){
              if (data!=null) {
                callback(data);
              }
              else {
                callback(undefined);
              }
            }
      });
    }
  }

}
