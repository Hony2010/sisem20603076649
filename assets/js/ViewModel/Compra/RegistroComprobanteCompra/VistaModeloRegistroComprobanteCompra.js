VistaModeloRegistroComprobanteCompra = function (data) {
    var self = this;

    ko.mapping.fromJS(data, MappingCompra, self);

    self.Inicializar = function ()  {
      self.Nuevo(self.data.ComprobanteCompra,window);
    }

    self.Nuevo = function(data,event) {
      if(event) {
        self.data.ComprobanteCompra.Nuevo(data,event,self.PostGuardar);
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
          self.Nuevo(self.data.ComprobanteCompraNuevo,event);
        }
      }
    }

}
