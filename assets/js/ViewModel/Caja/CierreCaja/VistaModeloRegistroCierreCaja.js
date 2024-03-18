VistaModeloRegistroCierreCaja = function (data) {
  var self = this;

  ko.mapping.fromJS(data, MappingCaja, self);

  self.Inicializar = function ()  {
    self.Nuevo(self.data.CierreCaja,window);
  }

  self.Nuevo = function(data,event) {
    if(event) {
      self.data.CierreCaja.OnNuevo(data,event,self.PostGuardar);
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
          self.Nuevo(self.data.CierreCaja.NuevoCierreCaja,event);
          alertify.alert().destroy();
        })
      }
    }
  }
}
