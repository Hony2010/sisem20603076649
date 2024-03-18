VistaModeloRegistroAperturaCaja = function (data) {
  var self = this;

  ko.mapping.fromJS(data, MappingCaja, self);

  self.Inicializar = function ()  {
    self.Nuevo(self.data.AperturaCaja,window);
  }

  self.Nuevo = function(data,event) {
    if(event) {
      self.data.AperturaCaja.OnNuevo(data,event,self.PostGuardar);
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
          var datacaja = self.data.AperturaCaja.NuevoComprobanteCaja
          datacaja.IdCaja(data.IdCaja());
          datacaja.MontoComprobante(data.MontoComprobante());

          self.Nuevo(datacaja,event);
          alertify.alert().destroy();
        })
      }
    }
  }
}
