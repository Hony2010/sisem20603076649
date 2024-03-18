VistaModeloEmisionNotaDebito = function (data) {
    var self = this;

    ko.mapping.fromJS(data, MappingVenta, self);

    self.Inicializar = function ()  {
      self.Nuevo(self.data.NotaDebito,window);
      self.data.NotaDebito.CambiarMotivoNotaDebito(window);
    }

    self.Nuevo = function(data,event) {
      if(event)
      {
        self.data.NotaDebito.Nuevo(data,event,self.PostGuardar);
      }
    }

    self.Editar =  function(data,event) {
      if(event) {
        self.data.NotaDebito.Editar(data,event,self.PostGuardar);
      }
    }

    self.PostGuardar = function(data,event) {
      if(event)
      {
        console.log("self.PostGuardar");
        console.log(data);
        $("#loader").hide();
        if(data) {
          self.Nuevo(self.data.NuevoNotaDebito,event);
        }
        else {
          self.data.NotaDebito.IndicadorReseteoFormulario = false;
          self.Editar(self.data.NotaDebito,event);
        }
      }
    }

}
