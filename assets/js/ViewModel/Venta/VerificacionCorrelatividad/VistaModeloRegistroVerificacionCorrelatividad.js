VistaModeloRegistroVerificacionCorrelatividad = function (data) {
    var self = this;
  
    ko.mapping.fromJS(data, MappingVenta, self);
  
    self.Inicializar = function ()  {

    } 
    
    self.Nuevo = function(data,event) {
      if(event) {
        self.data.VerificacionCorrelatividad.Nuevo(data,event,self.PostGuardar);
      }
    }
  }
  