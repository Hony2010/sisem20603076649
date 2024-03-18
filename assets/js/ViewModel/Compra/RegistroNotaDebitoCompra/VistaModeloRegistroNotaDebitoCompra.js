VistaModeloRegistroNotaDebitoCompra = function (data) {
    var self = this;

    ko.mapping.fromJS(data, MappingCompra, self);

    self.Inicializar = function ()  {
      self.Nuevo(self.data.NotaDebitoCompra,window);
      self.data.NotaDebitoCompra.CambiarMotivoNotaDebitoCompra(window);
    }

    self.Nuevo = function(data,event) {
      if(event)
      {
        self.data.NotaDebitoCompra.Nuevo(data,event,self.PostGuardar);
      }
    }

    self.Editar =  function(data,event) {
      if(event) {
        self.data.NotaDebitoCompra.Editar(data,event,self.PostGuardar);
      }
    }

    self.PostGuardar = function(data,event) {
      if(event)
      {
        console.log("self.PostGuardar");
        console.log(data);
        $("#loader").hide();
        if(data) {
          self.Nuevo(self.data.NuevoNotaDebitoCompra,event);
        }
        else {
          self.data.NotaDebitoCompra.IndicadorReseteoFormulario = false;
          self.Editar(self.data.NotaDebitoCompra,event);
        }
      }
    }

}
