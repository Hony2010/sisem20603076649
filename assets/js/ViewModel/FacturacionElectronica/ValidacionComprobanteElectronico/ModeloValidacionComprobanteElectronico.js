ModeloValidacionComprobanteElectronico = function (data) {
    var self = this;
    var base = data;

    self.ListarComprobantesVenta = function(data, event, callback)
    {
      if(event)
      {
        $dataFiltro = {};
        $dataFiltro.FechaInicio = data.FechaInicio();
        $dataFiltro.FechaFin = data.FechaFin();
        $dataFiltro.TipoDocumento = data.IdTipoDocumento() == undefined ? '%' : data.IdTipoDocumento();
        $dataFiltro.TextoFiltro = data.TextoFiltro() == "" || data.TextoFiltro() == null ? '%' : data.TextoFiltro();
        self.ConsultarComprobantesVenta($dataFiltro,event,function($data, $event)
        {
          base.data.ComprobantesVenta([]);

          ko.utils.arrayForEach($data, function (item) {
            base.data.ComprobantesVenta.push(new VistaModeloValidacionComprobantes(item));
          });

          callback($data, $event);
        });
      }
    }

    self.ConsultarComprobantesVenta = function(data,event,callback) {
      if(event) {
        $("#loader").show();
        var datajs = {"Data": JSON.stringify(data)};
        $.ajax({
          type: 'POST',
          dataType: 'json',
          data : datajs,
          url: SITE_URL+'/FacturacionElectronica/cValidacionComprobanteElectronico/ConsultarComprobanteElectronicosParaValidacion',
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

    self.ExportarVentas = function(data,event,callback) {
      if(event) {
        $("#loader").show();
        var datajs = {"Data": JSON.stringify(ko.mapping.toJS(data, mappingIgnore))};
        $.ajax({
          type: 'POST',
          dataType: 'json',
          data : datajs,
          url: SITE_URL+'/Venta/cExportacionVenta/ExportarDataJSON',
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

    self.EnviarComprobanteServidor = function(data,event,callback) {
      if(event) {
        var datajs = {"Data": JSON.stringify(ko.mapping.toJS(data, mappingIgnore))};
        $.ajax({
          type: 'POST',
          dataType: 'json',
          data : datajs,
          url: SITE_URL+'/FacturacionElectronica/cComprobanteElectronico/ValidarComprobobanteElectronico',
          success: function (data) {
            callback(data,event);
          },
          error : function (jqXHR, textStatus, errorThrown) {
            var data = {error:{msg:jqXHR.responseText}};
            callback(data,event);
          }
        });
      }
    }
}
