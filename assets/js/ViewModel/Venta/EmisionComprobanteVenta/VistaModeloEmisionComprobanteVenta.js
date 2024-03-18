VistaModeloEmisionComprobanteVenta = function (data) {
    var self = this;

    ko.mapping.fromJS(data, MappingVenta, self);

    self.Inicializar = function ()  {
      self.Nuevo(self.data.ComprobanteVenta,window);
    }

    self.Nuevo = function(data,event) {
      if(event) {
        self.data.ComprobanteVenta.Nuevo(data,event,self.PostGuardar);
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
          self.Nuevo(self.data.ComprobanteVentaNuevo,event);
        }
      }
    }

}
