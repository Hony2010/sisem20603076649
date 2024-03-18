ModeloSaldoInicialCuentaPago = function (data) {
    var self = this;
    var base = data;

    self.GuardarSaldoInicialCuentaPago = function (data, event, callback) {
        if(event) {
          var objeto = ko.mapping.toJS(data, mappingIgnore);

          if (data.OpcionProceso() == opcionProceso.Nuevo) {
            objeto.IdSaldoInicialCuentaPago = "";
            var datajs = {"Data" : JSON.stringify(objeto)};
            self.Insertar(datajs, event, function($data, $event) {
              if ($data.error) {
                callback($data,$event);
              } else {
                ko.mapping.fromJS($data, {}, self);
                $data.mensaje = "Se registró el comprobante  " +self.SerieDocumento() + " - " + self.NumeroDocumento()+ " correctamente.";
                callback($data,$event);
              }
            });
          }
          else {
            var datajs = {"Data" : JSON.stringify(objeto)};
            self.Actualizar(datajs,event,function($data,$event) {
              if ($data.error) {
                callback($data,$event);
              } else {
                $data.mensaje ="Se actualizó el comprobante  " +self.SerieDocumento() + " - " + self.NumeroDocumento()+ " correctamente.";
                callback($data,$event);
              }
            });
          }
        }
    }

    self.EliminarSaldoInicialCuentaPago = function (data, event, callback) {
        if(event) {
          var datajs = {"Data" : JSON.stringify(ko.mapping.toJS(data, mappingIgnore))};

          self.Eliminar(datajs, event, function ($data, $event) {
            callback($data,$event);
          });
        }
    }

    self.Insertar = function(data,event,callback) {
      if (event) {
        $.ajax({
          type: 'POST',
          data : data,
          dataType: "json",
          url: SITE_URL+'/CuentaPago/cSaldoInicialCuentaPago/InsertarSaldoInicialCuentaPago',
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

    self.Actualizar = function(data,event,callback) {
      if (event) {
        $.ajax({
          type: 'POST',
          data : data,
          dataType: "json",
          url: SITE_URL+'/CuentaPago/cSaldoInicialCuentaPago/ActualizarSaldoInicialCuentaPago',
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

    self.Eliminar = function(data,event,callback) {
      if (event) {
        $.ajax({
          type: 'POST',
          data : data,
          dataType: "json",
          url: SITE_URL+'/CuentaPago/cSaldoInicialCuentaPago/BorrarSaldoInicialCuentaPago',
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
