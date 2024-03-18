VistaModeloRegistroCobranzaCliente = function (data) {
  var self = this;

  ko.mapping.fromJS(data, MappingCuentaCobranza, self);

  self.Inicializar = function ()  {
    self.Nuevo(self.data.CobranzaCliente,window);
  }

  self.Nuevo = function(data,event) {
    if(event) {
      self.data.CobranzaCliente.OnNuevo(data,event,self.PostGuardar);
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
          var datacaja = self.data.CobranzaCliente.NuevaCobranzaCliente
          datacaja.IdCaja(data.IdCaja());
          datacaja.MontoComprobante(data.MontoComprobante());

          self.Nuevo(datacaja,event);
          alertify.alert().destroy();
        })
      }
    }
  }
}
