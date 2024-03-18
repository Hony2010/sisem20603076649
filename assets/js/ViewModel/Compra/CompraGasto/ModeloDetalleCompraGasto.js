ModeloDetalleCompraGasto = function (data) {
  var self = this;
  var base = data;

  self.InicializarModelo = function (event) {
    if (event) {

    }
  }

  self.CalcularSubTotal = function (data, event) {
    if (event) {
      var resultado = "";
      if (base.NombreProducto !== undefined) {

        if (base.Cantidad() === "" || base.PrecioUnitario() === "" || base.CostoUnitario() === "" || base.DescuentoItem() === "") {
          base.PrecioItem("");
          base.CostoItem("");
          resultado = "";
        } else {
          var cantidad = parseFloatAvanzado(base.Cantidad());
          var descuentounitario = parseFloatAvanzado(base.DescuentoUnitario());
          var descuentoitem = cantidad * descuentounitario;
          base.DescuentoItem(descuentoitem.toFixed(CANTIDAD_DECIMALES_COMPRA.DESCUENTO_UNITARIO));
          if ($(self.InputPrecioUnitario()).length == 1) {
            var preciounitario = parseFloatAvanzado(base.PrecioUnitario());
            if (base.AfectoIGV() == 1) {
              var costounitario = (preciounitario / 1.18);
            } else {
              var costounitario = preciounitario;
            }
            // var precioitem = (cantidad * preciounitario) - descuentoitem;
          } else {
            var costounitario = parseFloatAvanzado(base.CostoUnitario());
            if (base.AfectoIGV() == 1) {
              var preciounitario = (costounitario * 1.18);
            } else {
              var preciounitario = costounitario;
            }
            base.PrecioUnitario(preciounitario);
          }
          var precioitem = (cantidad * preciounitario) - descuentoitem;

          base.CostoUnitario(costounitario);
          var costounitariocalculado = costounitario - descuentounitario;
          var preciounitariocalculado = preciounitario - descuentounitario;
          base.CostoUnitarioCalculado(costounitariocalculado.toFixed(CANTIDAD_DECIMALES_COMPRA.COSTO_UNITARIO_CALCULADO))

          costoitem = costounitariocalculado * cantidad;
          base.CostoItem(costoitem.toFixed(CANTIDAD_DECIMALES_COMPRA.COSTO_UNITARIO_CALCULADO));
          base.PrecioItem(precioitem);

          if ($(self.InputPrecioUnitario()).length == 1) {
            var igvunitario = (preciounitariocalculado * VALOR_IGV) / (1 + VALOR_IGV);
          }
          else {
            var igvunitario = (preciounitariocalculado * VALOR_IGV);
          }

          var igvitem = parseFloatAvanzado(igvunitario * parseFloatAvanzado(cantidad));
          base.IGVItem(igvitem.toFixed(NUMERO_DECIMALES_COMPRA));

          if ($(self.InputPrecioUnitario()).length == 1) {
            resultado = precioitem;
          } else {
            resultado = costoitem;
          }
        }
        return resultado;
      } else {
        return resultado;
      }
    }
  }

  self.CalcularPrecioCosto = function (data, event) {
    if (event) {
      var resultado = "";
      if (base.NombreProducto !== undefined) {
        var descuentounitario = parseFloatAvanzado(base.DescuentoUnitario());
        var cantidad = parseFloatAvanzado(base.Cantidad());
        var descuentoitem = cantidad * descuentounitario;
        base.DescuentoItem(descuentoitem.toFixed(CANTIDAD_DECIMALES_COMPRA.DESCUENTO_UNITARIO));

        if ($(self.InputPrecioUnitario()).length == 1) {
          var subtotal = parseFloatAvanzado(base.PrecioItem());
          var preciounitario = (subtotal + descuentoitem) / cantidad;
          if (base.AfectoIGV() == "1") {
            var costounitario = (preciounitario / 1.18);
          }
          else {
            var costounitario = preciounitario;
          }
          var costounitariocalculado = costounitario - descuentounitario;
          base.CostoUnitarioCalculado(costounitariocalculado.toFixed(CANTIDAD_DECIMALES_COMPRA.COSTO_UNITARIO_CALCULADO))
          costoitem = costounitariocalculado * cantidad;
          base.CostoItem(costoitem.toFixed(CANTIDAD_DECIMALES_COMPRA.COSTO_UNITARIO_CALCULADO));

          var resultado = preciounitario;
        }
        else {
          var subtotal = parseFloatAvanzado(base.CostoItem());
          costounitariocalculado = subtotal / cantidad;
          base.CostoUnitarioCalculado(costounitariocalculado.toFixed(CANTIDAD_DECIMALES_COMPRA.COSTO_UNITARIO_CALCULADO));
          var costounitario = costounitariocalculado + descuentounitario;
          if (base.AfectoIGV() == "1") {
            var preciounitario = (costounitariocalculado * 1.18);
          }
          else {
            var preciounitario = costounitariocalculado;
          }
          var precioitem = (cantidad * preciounitario) - descuentoitem;
          base.PrecioItem(precioitem);
          var resultado = costounitario;
        }
        base.CostoUnitario(costounitario);
        base.PrecioUnitario(preciounitario);
        return resultado;
      }
      else {
        base.CostoUnitario(0);
        base.PrecioUnitario(0);
        return resultado;
      }
    }
  }

  self.Reemplazar = function (data) {
    if (data) {
      data.ValorVentaItem = data.ValorVentaItem === "" || data.ValorVentaItem === null ? "0.00" : data.ValorVentaItem;
      var nuevodetalle = self.NuevoDetalleCompraGasto;
      var includesList = Object.keys(ko.mapping.toJS(nuevodetalle, { ignore: "Cantidad" }));
      var nuevadata = leaveJustIncludedProperties(data, includesList);
      var copia = Knockout.CopiarObjeto(nuevodetalle);
      var $cantidad = self.Cantidad() === "" ? "1.00" : self.Cantidad();
      // var $descuentoitem = self.AfectoIGV() === "" ? "0.00" : self.AfectoIGV();
      var adicionaldata = {
        'ValorVentaItem': "0.00",
        'CostoUnitario': "0.0000",
        'PrecioUnitario': "0.00",
        'DescuentoItem': "0.00",
        'DescuentoUnitario': "0.00",
        'IdDetalleComprobanteCompra': self.IdDetalleComprobanteCompra(),
        'Cantidad': $cantidad
      };
      ko.mapping.fromJS(adicionaldata, {}, copia);
      ko.mapping.fromJS(nuevadata, MappingCompra.DetalleCompraGasto, copia);
      var resultado = new VistaModeloDetalleCompraGasto(copia);
      base.__ko_mapping__ = undefined;
      var output = ko.mapping.toJS(resultado, mappingIgnore);
      ko.mapping.fromJS(output, {}, base);
      return resultado;
    }
  }
}
