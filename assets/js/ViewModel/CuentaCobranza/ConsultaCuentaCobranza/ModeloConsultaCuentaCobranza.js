ModeloConsultaCuentaCobranza = function (data) {
    var self = this;
    var base = data;

    self.ParseCuentasCobranza = function (data, event) {
      if (event) {
        return new VistaModeloCuentaCobranza(data);
      }
    }

    self.ListarCuentasCobranza = function (data,event,callback) {
      if(event) {
        var objeto = ko.mapping.toJS(data);

        objeto.IdCaja = "%";
        objeto.IdTipoOperacionCaja = "%" ;
        objeto.IdTipoDocumento = objeto.IdTipoDocumento == undefined ? "%" : objeto.IdTipoDocumento;
        objeto.IdUsuario = objeto.IdUsuario == undefined ? "%" : objeto.IdUsuario;
        objeto.IdMedioPago = objeto.IdMedioPago == undefined ? "%" : objeto.IdMedioPago;
        objeto.IdCaja = objeto.IdCaja == undefined ? "%" : objeto.IdCaja;

        self.ConsultarCuentasCobranza(objeto,event,function($data,$event) {
        base.data.CuentasCobranza([]);

        ko.utils.arrayForEach($data.resultado, function (item) {
          base.data.CuentasCobranza.push(self.ParseCuentasCobranza(item, event));
        });

        callback($data,$event);

        });
      }
    }

    self.ListarCuentasCobranzaPorPagina =function(data,event,callback) {
      if(event) {
        var objeto = ko.mapping.toJS(data);

        objeto.IdCaja = "%";
        objeto.IdTipoOperacionCaja = "%" ;
        objeto.IdTipoDocumento = objeto.IdTipoDocumento == undefined ? "%" : objeto.IdTipoDocumento;
        objeto.IdUsuario = objeto.IdUsuario == undefined ? "%" : objeto.IdUsuario;
        objeto.IdMedioPago = objeto.IdMedioPago == undefined ? "%" : objeto.IdMedioPago;
        objeto.IdCaja = objeto.IdCaja == undefined ? "%" : objeto.IdCaja;

        self.ConsultarCuentasCobranzaPorPagina(objeto,event,function($data,$event){
          base.data.CuentasCobranza([]);

          ko.utils.arrayForEach($data, function (item) {
            base.data.CuentasCobranza.push(self.ParseCuentasCobranza(item, event));
          });

          callback(data,event);
        });
      }
    }

    self.ConsultarCuentasCobranza = function(data,event,callback) {
      if(event) {
        $("#loader").show();
        var datajs = { Data: JSON.stringify(ko.mapping.toJS(data, mappingIgnore))}
        $.ajax({
          type: 'POST',
          dataType: 'json',
          data : datajs,
          url: SITE_URL+'/CuentaCobranza/cConsultaCuentaCobranza/ConsultarCuentasCobranza',
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

    self.ConsultarCuentasCobranzaPorPagina = function(data,event,callback) {
      if(event) {
        $("#loader").show();
        var datajs = { Data: JSON.stringify(ko.mapping.toJS(data, mappingIgnore))}
        $.ajax({
          type: 'POST',
          dataType: 'json',
          data : datajs,
          cache : false,
          url: SITE_URL+'/CuentaCobranza/cConsultaCuentaCobranza/ConsultarCuentasCobranzaPorPagina',
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
