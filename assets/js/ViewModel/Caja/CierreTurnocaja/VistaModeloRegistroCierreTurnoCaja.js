VistaModeloRegistroCierreTurnoCaja = function (data) {
  var self = this;

  ko.mapping.fromJS(data, MappingCaja, self);

  self.Inicializar = function ()  {
    self.Nuevo(self.data.CierreTurnoCaja,window);
  }

  self.Nuevo = function(data,event) {
    if(event) {
      self.data.CierreTurnoCaja.OnNuevo(data,event,self.PostGuardar);
    }
  }

  self.PostGuardar = function(data,event) {
    if(event) {
      if(data.error) {
        alertify.alert(data.error.msg,function()  {
        });
      }
      else {
        alertify.alert("REGISTRO DE CIERRE TURNO","Se registró correctamente",function () {
          self.Nuevo(self.data.CierreTurnoCaja.NuevoComprobanteCaja,event);
          alertify.alert().destroy();
        })
      }
    }
  }
}
