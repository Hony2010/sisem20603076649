ModeloConsultaComprobanteVenta = function (data) {
    var self = this;
    var base = data;

    self.ListarComprobantesVenta = function(data,event,callback){
      if(event) {
        data.TipoVenta(data.IdTipoVenta() == undefined ? "%" : data.IdTipoVenta());
        data.TipoDocumento(data.IdTipoDocumento() == undefined ? "%" : data.IdTipoDocumento());

        self.ConsultarComprobantesVenta(data,event,function($data,$event) {
        base.data.ComprobantesVenta([]);

        ko.utils.arrayForEach($data.resultado, function (item) {
          base.data.ComprobantesVenta.push(new VistaModeloComprobanteVenta(item,configCV));
        });

        callback($data,$event);

        });
      }
    }

    self.ListarComprobantesVentaPorPagina =function(data,event,callback) {
      if(event) {
        data.TipoVenta = data.IdTipoVenta == undefined ? "%" : data.IdTipoVenta;
        data.TipoDocumento = data.IdTipoDocumento == undefined ? "%" : data.IdTipoDocumento;
        self.ConsultarComprobantesVentaPorPagina(data,event,function($data,$event){
          base.data.ComprobantesVenta([]);

          ko.utils.arrayForEach($data, function (item) {
            base.data.ComprobantesVenta.push(new VistaModeloComprobanteVenta(item,configCV));
          });

          callback(data,event);
        });
      }
    }

    self.ConsultarComprobantesVenta = function(data,event,callback) {
      if(event) {
        $("#loader").show();
        var datajs = ko.mapping.toJS({"Data": data});
        $.ajax({
          type: 'GET',
          dataType: 'json',
          data : datajs,
          url: SITE_URL+'/Venta/ComprobanteVenta/cConsultaComprobanteVenta/ConsultarComprobantesVenta',
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

    self.ConsultarComprobantesVentaPorPagina = function(data,event,callback) {
      if(event) {
        $("#loader").show();
        var datajs = ko.mapping.toJS({"Data": data});
        $.ajax({
          type: 'GET',
          dataType: 'json',
          data : datajs,
          cache : false,
          url: SITE_URL+'/Venta/ComprobanteVenta/cConsultaComprobanteVenta/ConsultarComprobantesVentaPorPagina',
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
