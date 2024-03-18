ModeloConsultaComprobanteCaja = function (data) {
    var self = this;
    var base = data;

    self.ParseComprobantesCaja = function (data, event) {
      if (event) {
        return new VistaModeloComprobanteCaja(data);
      }
    }

    self.ListarComprobantesCaja = function (data,event,callback) {
      if(event) {
        var objeto = ko.mapping.toJS(data);

        objeto.IdCaja = objeto.IdCaja == undefined ? "%" : objeto.IdCaja;
        objeto.IdTipoOperacionCaja = objeto.IdTipoOperacionCaja == undefined ? "%" : objeto.IdTipoOperacionCaja;
        objeto.IdMoneda = objeto.IdMoneda == undefined ? "%" : objeto.IdMoneda;
        objeto.IdTipoDocumento = objeto.IdTipoDocumento == undefined ? "%" : objeto.IdTipoDocumento;
        objeto.IdUsuario = objeto.IdUsuario == undefined ? "%" : objeto.IdUsuario;

        self.ConsultarComprobantesCaja(objeto,event,function($data,$event) {
        base.data.ComprobantesCaja([]);

        ko.utils.arrayForEach($data.resultado, function (item) {
          base.data.ComprobantesCaja.push(self.ParseComprobantesCaja(item, event));
        });

        callback($data,$event);

        });
      }
    }

    self.ListarComprobantesCajaPorPagina =function(data,event,callback) {
      if(event) {
        var objeto = ko.mapping.toJS(data);
        objeto.IdCaja = objeto.IdCaja == undefined ? "%" : objeto.IdCaja;
        objeto.IdTipoOperacionCaja = objeto.IdTipoOperacionCaja == undefined ? "%" : objeto.IdTipoOperacionCaja;
        objeto.IdMoneda = objeto.IdMoneda == undefined ? "%" : objeto.IdMoneda;
        objeto.IdTipoDocumento = objeto.IdTipoDocumento == undefined ? "%" : objeto.IdTipoDocumento;
        objeto.IdUsuario = objeto.IdUsuario == undefined ? "%" : objeto.IdUsuario;

        self.ConsultarComprobantesCajaPorPagina(objeto,event,function($data,$event){
          base.data.ComprobantesCaja([]);

          ko.utils.arrayForEach($data, function (item) {
            base.data.ComprobantesCaja.push(self.ParseComprobantesCaja(item, event));
          });

          callback(data,event);
        });
      }
    }

    self.ConsultarComprobantesCaja = function(data,event,callback) {
      if(event) {
        $("#loader").show();
        var datajs = { Data: JSON.stringify(ko.mapping.toJS(data, mappingIgnore))}
        $.ajax({
          type: 'POST',
          dataType: 'json',
          data : datajs,
          url: SITE_URL+'/Caja/cConsultaComprobanteCaja/ConsultarComprobantesCaja',
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

    self.ConsultarComprobantesCajaPorPagina = function(data,event,callback) {
      if(event) {
        $("#loader").show();
        var datajs = { Data: JSON.stringify(ko.mapping.toJS(data, mappingIgnore))}
        $.ajax({
          type: 'POST',
          dataType: 'json',
          data : datajs,
          cache : false,
          url: SITE_URL+'/Caja/cConsultaComprobanteCaja/ConsultarComprobantesCajaPorPagina',
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
