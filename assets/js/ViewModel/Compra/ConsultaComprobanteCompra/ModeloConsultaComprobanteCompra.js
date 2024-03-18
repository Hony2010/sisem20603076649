ModeloConsultaComprobanteCompra = function (data) {
    var self = this;
    var base = data;

    self.ListarComprobantesCompra = function(data,event,callback){
      if(event) {
        data.TipoCompra(data.IdTipoCompra() == undefined ? "%" : data.IdTipoCompra());
        data.TipoDocumento(data.IdTipoDocumento() == undefined ? "%" : data.IdTipoDocumento());

        self.ConsultarComprobantesCompra(data,event,function($data,$event) {
          base.data.ComprobantesCompra([]);

          ko.utils.arrayForEach($data.resultado, function (item) {
            base.data.ComprobantesCompra.push(new VistaModeloComprobanteCompra(item,configCC));
          });

          callback($data,$event);

        });
      }
    }


    self.ListarComprobantesCompraPorPagina =function(data,event,callback) {
      if(event) {
        data.TipoCompra = data.IdTipoCompra == undefined ? "%" : data.IdTipoCompra;
        data.TipoDocumento = data.IdTipoDocumento == undefined ? "%" : data.IdTipoDocumento;
        self.ConsultarComprobantesCompraPorPagina(data,event,function($data,$event){
          base.data.ComprobantesCompra([]);

          ko.utils.arrayForEach($data, function (item) {
            base.data.ComprobantesCompra.push(new VistaModeloComprobanteCompra(item,configCC));
          });

          callback(data,event);
        });
      }
    }

    self.ConsultarComprobantesCompra = function(data,event,callback) {
      if(event)
      {
        $("#loader").show();
        var datajs = ko.mapping.toJS({"Data": data});
        $.ajax({
          type: 'POST',
          dataType: 'json',
          data : datajs,
          url: SITE_URL+'/Compra/ComprobanteCompra/cConsultaComprobanteCompra/ConsultarComprobantesCompra',
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

    self.ConsultarComprobantesCompraPorPagina = function(data,event,callback) {
      if(event)
      {
        $("#loader").show();
        var datajs = ko.mapping.toJS({"Data": data});
        $.ajax({
          type: 'POST',
          dataType: 'json',
          data : datajs,
          cache : false,
          url: SITE_URL+'/Compra/ComprobanteCompra/cConsultaComprobanteCompra/ConsultarComprobantesCompraPorPagina',
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
