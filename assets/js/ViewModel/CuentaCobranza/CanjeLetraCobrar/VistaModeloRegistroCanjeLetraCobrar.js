VistaModeloRegistroCanjeLetraCobrar = function (data) {
  var self = this;

  ko.mapping.fromJS(data, MappingCuentaCobranza, self);

  self.Inicializar = function ()  {
    self.Nuevo(self.data.CanjeLetraCobrar,window);
  }

  self.Nuevo = function(data,event) {
    if(event) {
      self.data.CanjeLetraCobrar.OnNuevo(data,event,self.PostGuardar);
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
          self.Nuevo(self.data.CanjeLetraCobrar.NuevoCanjeLetraCobrar,event);
          alertify.alert().destroy();
        })
      }
    }
  }
}
