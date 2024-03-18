VistaModeloEmisionBoletaViajeVenta = function (data) {
  var self = this;

  ko.mapping.fromJS(data, MappingVenta, self);

  self.Inicializar = function ()  {
    self.Nuevo(self.data.BoletaViajeVenta,window);
  }

  self.Nuevo = function(data,event) {
    if(event) {
      self.data.BoletaViajeVenta.Nuevo(data,event,self.PostGuardar);
    }
  }

  self.PostGuardar = function(data,event) {
    if(event) {
      if(data.error) {
        $("#loader").hide();
        alertify.alert(data.error.msg,function()  {
        });
      }
      else {
        $("#loader").hide();
        var comprobante_nuevo = ko.mapping.toJS(self.data.ComprobanteVentaNuevo);
        comprobante_nuevo.IdTipoVenta = data.IdTipoVenta;
        self.Nuevo(comprobante_nuevo,event);
      }
    }
  }

}
