VistaModeloEmisionNotaCreditoCompra = function (data) {
    var self = this;

    ko.mapping.fromJS(data, MappingCompra, self);

    self.Inicializar = function ()  {
      self.Nuevo(self.data.NotaCreditoCompra,window);
      self.data.NotaCreditoCompra.CambiarMotivoNotaCreditoCompra(window);
    }

    self.Nuevo = function(data,event) {
      if(event)
      {
        self.data.NotaCreditoCompra.Nuevo(data,event,self.PostGuardar);
      }
    }

    self.Editar =  function(data,event) {
      if(event) {
        self.data.NotaCreditoCompra.Editar(data,event,self.PostGuardar);
      }
    }

    self.PostGuardar = function(data,event) {
      if(event)
      {
        console.log("self.PostGuardar");
        console.log(data);
        $("#loader").hide();
        if(data) {
          self.Nuevo(self.data.NuevoNotaCreditoCompra,event);
        }
        else {
          self.data.NotaCreditoCompra.IndicadorReseteoFormulario = false;
          self.Editar(self.data.NotaCreditoCompra,event);
        }
      }
    }

}
