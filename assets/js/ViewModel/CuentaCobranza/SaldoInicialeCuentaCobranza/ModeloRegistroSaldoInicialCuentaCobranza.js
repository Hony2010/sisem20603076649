ModeloRegistroSaldoInicialCuentaCobranza = function (data) {
    var self = this;
    var base = data;

    self.ParseSaldosInicialesCuentaCobranza = function (data, event) {
      if (event) {
        var clone = ko.mapping.toJS(self.data.NuevoSaldoInicialCuentaCobranza);
        return new VistaModeloSaldoInicialCuentaCobranza(Object.assign(clone, data), self);
      }
    }

    self.ListarSaldosInicialesCuentaCobranza = function (data,event,callback) {
      if(event) {
        var objeto = ko.mapping.toJS(data);

        objeto.IdCaja = objeto.IdCaja == undefined ? "%" : objeto.IdCaja;
        objeto.IdTipoOperacionCaja = objeto.IdTipoOperacionCaja == undefined ? "%" : objeto.IdTipoOperacionCaja;

        self.ConsultarSaldosInicialesCuentaCobranza(objeto,event,function($data,$event) {
        self.data.SaldosInicialesCuentaCobranza([]);
        self.OnFilaNueva(false);
        self.UltimoItemSeleccionado = [];

        ko.utils.arrayForEach($data.resultado, function (item) {
          var nuevoSaldo = self.ParseSaldosInicialesCuentaCobranza(item, event)
          base.data.SaldosInicialesCuentaCobranza.push(nuevoSaldo);
          nuevoSaldo.Inicializar();
        });

        callback($data,$event);

        });
      }
    }

    self.ListarSaldosInicialesCuentaCobranzaPorPagina =function(data,event,callback) {
      if(event) {
        data.IdCaja = data.IdCaja == undefined ? "%" : data.IdCaja;
        data.IdTipoOperacionCaja = data.IdTipoOperacionCaja == undefined ? "%" : data.IdTipoOperacionCaja;

        self.ConsultarSaldosInicialesCuentaCobranzaPorPagina(data,event,function($data,$event){
          self.data.SaldosInicialesCuentaCobranza([]);

          ko.utils.arrayForEach($data, function (item) {
            base.data.SaldosInicialesCuentaCobranza.push(self.ParseSaldosInicialesCuentaCobranza(item, event));
          });

          callback(data,event);
        });
      }
    }

    self.ConsultarSaldosInicialesCuentaCobranza = function(data,event,callback) {
      if(event) {
        $("#loader").show();
        var datajs = { Data: JSON.stringify(ko.mapping.toJS(data, mappingIgnore))}
        $.ajax({
          type: 'POST',
          dataType: 'json',
          data : datajs,
          url: SITE_URL+'/CuentaCobranza/cSaldoInicialCuentaCobranza/ConsultarSaldosInicialCuentaCobranza',
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

    self.ConsultarSaldosInicialesCuentaCobranzaPorPagina = function(data,event,callback) {
      if(event) {
        $("#loader").show();
        var datajs = { Data: JSON.stringify(ko.mapping.toJS(data, mappingIgnore))}
        $.ajax({
          type: 'GET',
          dataType: 'json',
          data : datajs,
          cache : false,
          url: SITE_URL+'/CuentaCobranza/cSaldoInicialCuentaCobranza/ConsultarSaldosInicialCuentaCobranzaPorPagina',
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
