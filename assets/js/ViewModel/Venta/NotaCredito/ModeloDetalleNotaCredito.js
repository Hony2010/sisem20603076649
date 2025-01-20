ModeloDetalleNotaCredito = function (data) {
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
    if (data) {
        // Validar y asignar valores iniciales
        data.PrecioUnitario = data.PrecioUnitario === "" || data.PrecioUnitario === null ? "0.00" : data.PrecioUnitario;

        var nuevodetalle = self.NuevoDetalleNotaCredito;

        // Obtener solo las propiedades incluidas
        var includesList = Object.keys(ko.mapping.toJS(nuevodetalle, { ignore: ["Cantidad", "PrecioUnitario", "SubTotal", "SaldoPendienteNotaCredito"] }));
        var nuevadata = leaveJustIncludedProperties(data, includesList);

        // Crear una copia del objeto nuevodetalle
        var copia = Knockout.CopiarObjeto(nuevodetalle);

        // Validar los valores de los observables
        var $cantidad = self.Cantidad() === "" ? "0.00" : self.Cantidad();
        var $descuentoitem = self.DescuentoItem() === "" ? "0.00" : self.DescuentoItem();
        var $preciounitario = self.PrecioUnitario() === "" ? "0.00" : self.PrecioUnitario();
        var $subtotal = self.SubTotal() === "" ? "0.00" : self.SubTotal();

        // Agregar los valores de IdProducto y NombreProducto
        var $idproducto = self.IdProducto ? self.IdProducto() : null;
        var $nombreproducto = self.NombreProducto ? self.NombreProducto() : null;

        // Crear el objeto adicionaldata con los nuevos campos
        var adicionaldata = {
            'Cantidad': $cantidad,
            'DescuentoItem': $descuentoitem,
            'PrecioUnitario': $preciounitario,
            'SubTotal': $subtotal,
            'IdDetalleComprobanteVenta': self.IdDetalleComprobanteVenta(),
            'IdProducto': $idproducto, // Nuevo campo
            'NombreProducto': $nombreproducto // Nuevo campo
        };

        // Mapear adicionaldata en copia
        ko.mapping.fromJS(adicionaldata, {}, copia);

        // Mapear nuevadata en copia
        ko.mapping.fromJS(nuevadata, MappingVenta.DetalleNotaCredito, copia);

        // Crear el resultado como un nuevo objeto mapeado
        var resultado = new VistaModeloDetalleNotaCredito(copia);

        // Validar si base está definido y mapeado correctamente
        if (!base || !base.__ko_mapping__) {
            base = ko.mapping.fromJS({});
        }

        // Mapear resultado a base
        var output = ko.mapping.toJS(resultado, mappingIgnore);
        ko.mapping.fromJS(output, {}, base);

        return resultado;
    }
}
}
