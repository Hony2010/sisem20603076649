ModeloConsultaCuentaPago = function (data) {
    var self = this;
    var base = data;

    self.ParseCuentasPago = function (data, event) {
      if (event) {
        return new VistaModeloCuentaPago(data);
      }
    }

    self.ListarCuentasPago = function (data,event,callback) {
      if(event) {
        var objeto = ko.mapping.toJS(data);

        objeto.IdCaja = "%";
        objeto.IdTipoOperacionCaja = "%" ;
        objeto.IdTipoDocumento = objeto.IdTipoDocumento == undefined ? "%" : objeto.IdTipoDocumento;
        objeto.IdUsuario = objeto.IdUsuario == undefined ? "%" : objeto.IdUsuario;
        objeto.IdMedioPago = objeto.IdMedioPago == undefined ? "%" : objeto.IdMedioPago;
        objeto.IdCaja = objeto.IdCaja == undefined ? "%" : objeto.IdCaja;
        
        self.ConsultarCuentasPago(objeto,event,function($data,$event) {
        base.data.CuentasPago([]);

        ko.utils.arrayForEach($data.resultado, function (item) {
          base.data.CuentasPago.push(self.ParseCuentasPago(item, event));
        });

        callback($data,$event);

        });
      }
    }

    self.ListarCuentasPagoPorPagina =function(data,event,callback) {
      if(event) {

        objeto.IdCaja = "%";
        objeto.IdTipoOperacionCaja = "%" ;
        objeto.IdTipoDocumento = objeto.IdTipoDocumento == undefined ? "%" : objeto.IdTipoDocumento;
        objeto.IdUsuario = objeto.IdUsuario == undefined ? "%" : objeto.IdUsuario;
        objeto.IdMedioPago = objeto.IdMedioPago == undefined ? "%" : objeto.IdMedioPago;
        objeto.IdCaja = objeto.IdCaja == undefined ? "%" : objeto.IdCaja;

        self.ConsultarCuentasPagoPorPagina(data,event,function($data,$event){
          base.data.CuentasPago([]);

          ko.utils.arrayForEach($data, function (item) {
            base.data.CuentasPago.push(self.ParseCuentasPago(item, event));
          });

          callback(data,event);
        });
      }
    }

    self.ConsultarCuentasPago = function(data,event,callback) {
      if(event) {
        $("#loader").show();
        var datajs = { Data: JSON.stringify(ko.mapping.toJS(data, mappingIgnore))}
        $.ajax({
          type: 'POST',
          dataType: 'json',
          data : datajs,
          url: SITE_URL+'/CuentaPago/cConsultaCuentaPago/ConsultarCuentasPago',
          success: function (data) {
              $("#loader").hide();
              callback(data,event);
          },
          error : function (jqXHR, textStatus, errorThrown) {
            $("#loader").hide();
            alertify.alert(jqXHR.responseText);
          }
        });
      }
    }

    self.ConsultarCuentasPagoPorPagina = function(data,event,callback) {
      if(event) {
        $("#loader").show();
        var datajs = { Data: JSON.stringify(ko.mapping.toJS(data, mappingIgnore))}
        $.ajax({
          type: 'GET',
          dataType: 'json',
          data : datajs,
          cache : false,
          url: SITE_URL+'/CuentaPago/cConsultaCuentaPago/ConsultarCuentasPagoPorPagina',
          success: function (data) {
              $("#loader").hide();
              callback(data,event);
          },
          error : function (jqXHR, textStatus, errorThrown) {
            $("#loader").hide();
            alertify.alert(jqXHR.responseText);
          }
        });
      }
    }

}
