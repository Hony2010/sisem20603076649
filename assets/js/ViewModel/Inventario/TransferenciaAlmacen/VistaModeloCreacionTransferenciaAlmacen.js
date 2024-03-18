VistaModeloCreacionTransferenciaAlmacen = function (data) {
    var self = this;

    ko.mapping.fromJS(data, MappingTransferenciaAlmacen, self);

    self.Inicializar = function ()  {
      self.Nuevo(self.data.TransferenciaAlmacen,window);
    }

    self.Nuevo = function(data,event) {
      if(event) {
        self.data.TransferenciaAlmacen.Nuevo(data,event,self.PostGuardar);
      }
    }

    self.PostGuardar = function(data,event) {
      if(event) {
        if(data.error) {
          $("#loader").hide();
          alertify.alert(data.error.msg,function()  {
            //debugger;
          });
        }
        else {
          $("#loader").hide();
          self.Nuevo(self.data.NuevoTransferenciaAlmacen,event);
        }
      }
    }

}
