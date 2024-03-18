ModeloDetalleTransferenciaAlmacen = function (data) {
    var self = this;
    var base = data;

    self.InicializarModelo =function(event,callback,callback2) {
      if(event) {
        if(callback)
          self.callback=callback;
        if(callback2)
          self.callback2=callback2;
      }
    }

    self.Reemplazar = function(data) {
      if(data) {
        data.ValorUnitario = data.ValorUnitario === "" || data.ValorUnitario === null ? "0.00" : data.ValorUnitario;
        var nuevodetalle = self.NuevoDetalleTransferenciaAlmacen;
        var includesList =Object.keys(ko.mapping.toJS(nuevodetalle,{ignore : "Cantidad"}));
        var nuevadata = leaveJustIncludedProperties(data, includesList);
        var copia = Knockout.CopiarObjeto(nuevodetalle);
        var $cantidad = self.Cantidad() === "" ? "0.00" : self.Cantidad();
        var $fechavencimientolote = self.FechaVencimientoLote() === "" ? "" : self.FechaVencimientoLote();
        var $fechadocumentozofra = self.FechaEmisionDocumentoSalidaZofra() === "" ? self.FechaHoy() : self.FechaEmisionDocumentoSalidaZofra();
        var $fechadua = self.FechaEmisionDua() === "" ? self.FechaHoy() : self.FechaEmisionDua();        

        var adicionaldata= { 'SaldoPendienteSalida':  "0.00" ,
                              'Cantidad':  data.Cantidad ,
                              'FechaVencimientoLote' : $fechavencimientolote,
                              'FechaEmisionDocumentoSalidaZofra' : $fechadocumentozofra,
                             'FechaEmisionDua' : $fechadua,
                             'ValorUnitario':  data.ValorUnitario ,
                             'IdDetalleTransferenciaAlmacen' : self.IdDetalleTransferenciaAlmacen()  };
        ko.mapping.fromJS(adicionaldata, {} , copia);
        ko.mapping.fromJS(nuevadata, MappingTransferenciaAlmacen.DetalleTransferenciaAlmacen, copia);
        
        var resultado = new VistaModeloDetalleTransferenciaAlmacen(copia);
        base.__ko_mapping__= undefined;
        var output = ko.mapping.toJS(resultado,mappingIgnore);
        ko.mapping.fromJS(output,{}, base);
        return resultado;
      }
    }

  
}
