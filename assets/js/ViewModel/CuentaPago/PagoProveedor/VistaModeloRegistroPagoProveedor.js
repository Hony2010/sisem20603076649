VistaModeloRegistroPagoProveedor = function (data) {
  var self = this;

  ko.mapping.fromJS(data, MappingCuentaPago, self);

  self.Inicializar = function ()  {
    self.Nuevo(self.data.PagoProveedor,window);
  }

  self.Nuevo = function(data,event) {
    if(event) {
      self.data.PagoProveedor.OnNuevo(data,event,self.PostGuardar);
    }
  }

  self.PostGuardar = function(data,event) {
    if(event) {
      if(data.error) {
        alertify.alert(data.error.msg,function()  {
        });
      }
      else {
        alertify.alert(data.titulo, data.mensaje, function () {
          var datacaja = self.data.PagoProveedor.NuevaPagoProveedor
          datacaja.IdCaja(data.IdCaja());
          datacaja.MontoComprobante(data.MontoComprobante());

          self.Nuevo(datacaja,event);
          alertify.alert().destroy();
        })
      }
    }
  }
}
