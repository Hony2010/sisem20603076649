VistaModeloRegistroAperturaTurnoCaja = function (data) {
  var self = this;

  ko.mapping.fromJS(data, MappingCaja, self);

  self.Inicializar = function ()  {
    self.Nuevo(self.data.AperturaTurnoCaja,window);
  }

  self.Nuevo = function(data,event) {
    if(event) {
      self.data.AperturaTurnoCaja.OnNuevo(data,event,self.PostGuardar);
    }
  }

  self.PostGuardar = function(data,event) {
    if(event) {
      if(data.error) {
        alertify.alert(data.error.msg,function()  {
        });
      }
      else {
        alertify.alert("REGISTRO DE APERTURA TURNO","Se registró correctamente",function () {
          self.Nuevo(self.data.AperturaTurnoCaja.NuevoComprobanteCaja,event);
          alertify.alert().destroy();
        })
      }
    }
  }
}
