ModeloDetalleNotaDebito = function (data) {
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
        data.PrecioUnitario = data.PrecioUnitario === "" || data.PrecioUnitario === null ? "0.00" : data.PrecioUnitario;
        var nuevodetalle = self.NuevoDetalleNotaDebito;
        var includesList =Object.keys(ko.mapping.toJS(nuevodetalle,{ignore : ["Cantidad", "PrecioUnitario", "SubTotal"]}));
        var nuevadata = leaveJustIncludedProperties(data, includesList);
        var copia = Knockout.CopiarObjeto(nuevodetalle);
        var $cantidad = self.Cantidad() === "" ? "0.00" : self.Cantidad();
        var $descuentoitem = self.DescuentoItem() === "" ? "0.00" : self.DescuentoItem();
        var $preciounitario = self.PrecioUnitario() === "" ? "0.00" : self.PrecioUnitario();
        var $subtotal = self.SubTotal() === "" ? "0.00" : self.SubTotal();
        var adicionaldata= { 'Cantidad':  $cantidad ,
                             'DescuentoItem' : $descuentoitem,
                             'PrecioUnitario' : $preciounitario,
                             'SubTotal' : $subtotal,
                             'IdDetalleComprobanteVenta' : self.IdDetalleComprobanteVenta()  };
        ko.mapping.fromJS(adicionaldata, {} , copia);
        ko.mapping.fromJS(nuevadata, MappingVenta.DetalleNotaDebito, copia);
        var resultado = new VistaModeloDetalleNotaDebito(copia);
        base.__ko_mapping__= undefined;
        var output = ko.mapping.toJS(resultado,mappingIgnore);
        ko.mapping.fromJS(output,{}, base);
        return resultado;
      }
    }
}
