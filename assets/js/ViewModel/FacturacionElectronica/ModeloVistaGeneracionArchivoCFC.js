ComprobantesVentaModel = function (data) {
    var self = this;
    var objeto = data;
    ko.mapping.fromJS(data, {}, self);

    self.FechaFormateada = ko.pureComputed(function(){
      return FechaFormato.FormatearFechaYYYYMMDD(self.FechaEmision());
    }, this);

    self.BuscarCodigoMotivo = function(data, event){
      if(event)
      {
        var id = data.IdMotivoComprobanteFisicoContingencia();
        ko.utils.arrayFirst(Models.data.Motivos(), function(item) {
          if(item.IdMotivoComprobanteFisicoContingencia() == id)
          {
              self.CodigoMotivoComprobanteFisicoContingencia(item.CodigoMotivoComprobanteFisicoContingencia());
          }
        });
      }
    }

    // self.BuscarCodigoMotivo(self, window);

}


BuscadorModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.NumeroDocumento = ko.observable("");

    self.BusquedaTexto = function(data, event){
      if(data.NumeroDocumento() == ""){
        Models.data.Buscador.NumeroDocumento("%");
      }
    }

    self.BuscarFactura =function(data,event){
      if(event)
      {
        $("#loader").show();
        var accion = "";
        var _data = Knockout.CopiarObjeto(data);
        var datajs = ko.mapping.toJS({"Data" : _data}, mappingIgnore);

        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/FacturacionElectronica/cGeneracionArchivoCFC/ConsultarComprobantesVenta',
          success: function (data) {
              console.log(data);
              if(data != "")
              {
                Models.data.ComprobantesVenta.removeAll();

                Models.data.ComprobantesVenta([]);
                ko.utils.arrayFirst(data, function(item) {
                    Models.data.ComprobantesVenta.push(new ComprobantesVentaModel(item));
                });
              }
              else {
                Models.data.ComprobantesVenta([]);
              }
              $("#loader").hide();
          },
          error : function (jqXHR, textStatus, errorThrown) {
            //console.log(jqXHR.responseText);
            $("#loader").hide();
          }
        });
      }

    }

}

var MappingGeneracionArchivoCFC = {
    'ComprobantesVenta': {
        create: function (options) {
            if (options)
              return new ComprobantesVentaModel(options.data);
            }
    },
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel(options.data);
            }
    }
}
