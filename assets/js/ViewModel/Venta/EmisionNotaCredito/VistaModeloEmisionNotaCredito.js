VistaModeloEmisionNotaCredito = function (data) {
    var self = this;

    ko.mapping.fromJS(data, MappingVenta, self);

    self.Inicializar = function ()  {
      self.Nuevo(self.data.NotaCredito,window);
      self.data.NotaCredito.CambiarMotivoNotaCredito(window);
    }

    self.Nuevo = function(data,event) {
      if(event)
      {
        self.data.NotaCredito.Nuevo(data,event,self.PostGuardar);
      }
    }

    self.Editar =  function(data,event) {
      if(event) {
        self.data.NotaCredito.Editar(data,event,self.PostGuardar);
      }
    }

    self.PostGuardar = function(data,event) {
      if(event)
      {
        console.log("self.PostGuardar");
        console.log(data);
        $("#loader").hide();
        if(data) {
          self.Nuevo(self.data.NuevoNotaCredito,event);
        }
        else {
          self.data.NotaCredito.IndicadorReseteoFormulario = false;
          self.Editar(self.data.NotaCredito,event);
        }
      }
    }

}
