ModeloDetalleNotaSalida = function (data) {
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
        var nuevodetalle = self.NuevoDetalleNotaSalida;
        var includesList =Object.keys(ko.mapping.toJS(nuevodetalle,{ignore : "Cantidad"}));
        var nuevadata = leaveJustIncludedProperties(data, includesList);
        var copia = Knockout.CopiarObjeto(nuevodetalle);
        var $cantidad = self.Cantidad() === "" ? "0.00" : self.Cantidad();
        // var $descuentoitem = self.DescuentoItem() === "" ? "0.00" : self.DescuentoItem();
        var adicionaldata= { 'SaldoPendienteSalida':  "0.00" ,
                              // 'Cantidad':  $cantidad ,
                             // 'DescuentoItem' : $descuentoitem,
                             'ValorUnitario':  "0.00" ,
                             'IdDetalleNotaSalida' : self.IdDetalleNotaSalida()  };
        ko.mapping.fromJS(adicionaldata, {} , copia);
        ko.mapping.fromJS(nuevadata, MappingInventario.DetalleNotaSalida, copia);
        var resultado = new VistaModeloDetalleNotaSalida(copia);
        base.__ko_mapping__= undefined;
        var output = ko.mapping.toJS(resultado,mappingIgnore);
        ko.mapping.fromJS(output,{}, base);
        return resultado;
      }
    }
}
