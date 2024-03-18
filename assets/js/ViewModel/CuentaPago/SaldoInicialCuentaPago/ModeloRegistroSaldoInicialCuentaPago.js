ModeloRegistroSaldoInicialCuentaPago = function (data) {
    var self = this;
    var base = data;

    self.ParseSaldosInicialesCuentaPago = function (data, event) {
      if (event) {
        var clone = ko.mapping.toJS(self.data.NuevoSaldoInicialCuentaPago);
        return new VistaModeloSaldoInicialCuentaPago(Object.assign(clone, data), self);
      }
    }

    self.ListarSaldosInicialesCuentaPago = function (data,event,callback) {
      if(event) {
        var objeto = ko.mapping.toJS(data);

        objeto.IdCaja = objeto.IdCaja == undefined ? "%" : objeto.IdCaja;
        objeto.IdTipoOperacionCaja = objeto.IdTipoOperacionCaja == undefined ? "%" : objeto.IdTipoOperacionCaja;

        self.ConsultarSaldosInicialesCuentaPago(objeto,event,function($data,$event) {
        self.data.SaldosInicialesCuentaPago([]);
        self.OnFilaNueva(false);
        self.UltimoItemSeleccionado = [];

        ko.utils.arrayForEach($data.resultado, function (item) {
          var nuevoSaldo = self.ParseSaldosInicialesCuentaPago(item, event)
          base.data.SaldosInicialesCuentaPago.push(nuevoSaldo);
          nuevoSaldo.Inicializar();
        });

        callback($data,$event);

        });
      }
    }

    self.ListarSaldosInicialesCuentaPagoPorPagina =function(data,event,callback) {
      if(event) {
        data.IdCaja = data.IdCaja == undefined ? "%" : data.IdCaja;
        data.IdTipoOperacionCaja = data.IdTipoOperacionCaja == undefined ? "%" : data.IdTipoOperacionCaja;

        self.ConsultarSaldosInicialesCuentaPagoPorPagina(data,event,function($data,$event){
          self.data.SaldosInicialesCuentaPago([]);

          ko.utils.arrayForEach($data, function (item) {
            base.data.SaldosInicialesCuentaPago.push(self.ParseSaldosInicialesCuentaPago(item, event));
          });

          callback(data,event);
        });
      }
    }

    self.ConsultarSaldosInicialesCuentaPago = function(data,event,callback) {
      if(event) {
        $("#loader").show();
        var datajs = { Data: JSON.stringify(ko.mapping.toJS(data, mappingIgnore))}
        $.ajax({
          type: 'POST',
          dataType: 'json',
          data : datajs,
          url: SITE_URL+'/CuentaPago/cSaldoInicialCuentaPago/ConsultarSaldosInicialCuentaPago',
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

    self.ConsultarSaldosInicialesCuentaPagoPorPagina = function(data,event,callback) {
      if(event) {
        $("#loader").show();
        var datajs = { Data: JSON.stringify(ko.mapping.toJS(data, mappingIgnore))}
        $.ajax({
          type: 'GET',
          dataType: 'json',
          data : datajs,
          cache : false,
          url: SITE_URL+'/CuentaPago/cSaldoInicialCuentaPago/ConsultarSaldosInicialCuentaPagoPorPagina',
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
