VistaModeloRegistroCompraCostoAgregado = function (data) {
    var self = this;

    ko.mapping.fromJS(data, MappingCompra, self);

    self.Inicializar = function ()  {
      self.Nuevo(self.data.CompraCostoAgregado,window);
      self.data.FiltrosCostoAgregado.InicializarVistaModelo(self.data.FiltrosCostoAgregado,window, self, self.AgregarComprobantesCompraReferencia);

    }

    self.AgregarComprobantesCompraReferencia = function(event, callback)
    {
      if(event)
      {
        ko.utils.arrayForEach(self.data.DocumentoCompra(), function (entry) {
          var cabecera = ko.mapping.toJS(entry, {ignore:["DetallesComprobanteCompra", "__ko_mapping__"]});
          ko.utils.arrayForEach(entry.DetallesComprobanteCompra(), function (entry2) {
            var detalle = ko.mapping.toJS(self.data.CompraCostoAgregado.NuevoDocumentoReferencia, mappingIgnore);
            var entryJS = ko.mapping.toJS(entry2, mappingIgnore);

            var fila = Object.assign(detalle, cabecera, entryJS);
            var objeto = new ModeloDocumentoReferencia(fila, self.data.CompraCostoAgregado);
            self.data.CompraCostoAgregado.DetallesDocumentoReferencia.push(objeto);
          });
        });

        callback(event);
      }
    }

    self.Nuevo = function(data,event) {
      if(event) {
        self.data.CompraCostoAgregado.Nuevo(data,event,self.PostGuardar);
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
          self.Nuevo(self.data.CompraCostoAgregadoNuevo,event);
        }
      }
    }

}
