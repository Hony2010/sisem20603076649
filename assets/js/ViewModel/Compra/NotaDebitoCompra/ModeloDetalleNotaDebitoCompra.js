ModeloDetalleNotaDebitoCompra = function (data) {
    var self = this;
    var base = data;
    // self.TipoCompra = ko.observable(TIPO_VENTA.MERCADERIAS);
    self.IGVUnitario = ko.observable(0.00);

    self.InicializarModelo =function(event,callback,callback2) {
      if(event) {
        if(callback)
          self.callback=callback;
        if(callback2)
          self.callback2=callback2;
      }
    }

    self.CalcularTotales = function(data, event)
    {
      if(event)
      {
        var costounitario = self.CalcularCostoUnitarioCalculado(event);
        var costoitem = self.CalcularCostoItem(event);
        var IGVUnitario = self.CalcularIGVUnitario(event);
        var IGVItem = self.CalcularIGVItem(event);
        var ISCItem = self.CalcularISCItem(event);

      }
    }

    self.CalcularTasaDescuentoUnitario = function(data, event)
    {
      if(event)
      {
        var costoUnitario = parseFloatAvanzado(self.CostoUnitario());
        var porcentaje_decimal = parseFloatAvanzado(self.TasaDescuentoUnitario()) / 100;
        var descuentounitario = costoUnitario * porcentaje_decimal;
        self.DescuentoUnitario(descuentounitario.toFixed(NUMERO_DECIMALES_COMPRA));

        self.CalcularTotales(event);
      }
    }

    self.CalcularDescuentoUnitario = function(data, event)
    {
      if(event)
      {
        var descuentoUnitario = parseFloatAvanzado(self.CostoUnitario());
        var costoUnitario = parseFloatAvanzado(self.CostoUnitario());
        var porcentaje_decimal = descuentoUnitario / costoUnitario;
        var porcentaje = porcentaje_decimal * 100;
        self.TasaDescuentoUnitario(porcentaje.toFixed(NUMERO_DECIMALES_COMPRA));

        self.CalcularTotales(event);
      }
    }

    self.CalcularCostoUnitarioCalculado = function(event)
    {
      if(event)
      {
        var descuentoUnitario = parseFloatAvanzado(self.CostoUnitario());
        var costoUnitario = parseFloatAvanzado(self.CostoUnitario());
        var costounitariocalculado = costoUnitario - descuentoUnitario;
        self.CostoUnitarioCalculado(costounitariocalculado.toFixed(NUMERO_DECIMALES_COMPRA));
        return costounitariocalculado.toFixed(NUMERO_DECIMALES_COMPRA);
      }
    }

    self.CalcularCostoItem = function(event)
    {
      if(event)
      {
        var costoUnitarioCalculado = parseFloatAvanzado(self.CostoUnitarioCalculado());
        var cantidad = parseFloatAvanzado(self.Cantidad());

        var costoitem = cantidad * costoUnitarioCalculado;
        self.CostoItem(costoitem.toFixed(NUMERO_DECIMALES_COMPRA));
        return costoitem.toFixed(NUMERO_DECIMALES_COMPRA);
      }
    }

    self.CalcularIGVUnitario = function(event)
    {
      if(event)
  		{
        if(self.AfectoIGV() == '1')
        {
          var costoUnitarioCalculado = parseFloatAvanzado(self.CostoUnitarioCalculado());

          var igvunitario = costoUnitarioCalculado * VALOR_IGV;
          self.IGVUnitario(igvunitario.toFixed(NUMERO_DECIMALES_COMPRA));
          return igvunitario.toFixed(NUMERO_DECIMALES_COMPRA);
        }
      }
    }

    self.CalcularIGVItem = function(event)
    {
      if(event)
      {
        if(self.AfectoIGV() == '1')
        {
          var IGVUnitario = parseFloatAvanzado(self.IGVUnitario());
          var cantidad = parseFloatAvanzado(self.Cantidad());

          var igvitem = IGVUnitario * cantidad;
          self.IGVItem(igvitem.toFixed(NUMERO_DECIMALES_COMPRA));
          return igvitem.toFixed(NUMERO_DECIMALES_COMPRA);
        }
      }
    }

    self.CalcularISCItem = function(event)
    {
      if(event)
      {
        if(self.AfectoISC() == '1')
        {
          var costoItem = parseFloatAvanzado(self.CostoItem());

          var iscitem = (costoItem * parseFloatAvanzado(self.TasaISC())) / 100;
          self.ISCItem(iscitem.toFixed(NUMERO_DECIMALES_COMPRA));
          return iscitem.toFixed(NUMERO_DECIMALES_COMPRA);
        }
      }
    }

    self.Reemplazar = function(data) {
      if(data) {
        data.CostoUnitario = data.CostoUnitario === "" || data.CostoUnitario === null ? "0.00" : data.CostoUnitario;
        var nuevodetalle = self.NuevoDetalleNotaDebitoCompra;
        var includesList =Object.keys(ko.mapping.toJS(nuevodetalle,{ignore : ["Cantidad", "CostoUnitario", "CostoItem", "SaldoPendienteNotaDebito"]}));
        var nuevadata = leaveJustIncludedProperties(data, includesList);
        var copia = Knockout.CopiarObjeto(nuevodetalle);
        var $cantidad = self.Cantidad() === "" ? "0.00" : self.Cantidad();
        var $descuentoitem = self.DescuentoUnitario() === "" ? "0.00" : self.DescuentoUnitario();
        var $preciounitario = self.CostoUnitario() === "" ? "0.00" : self.CostoUnitario();
        var $subtotal = self.CostoItem() === "" ? "0.00" : self.CostoItem();
        var adicionaldata= { 'Cantidad':  $cantidad ,
                             'DescuentoUnitario' : $descuentoitem,
                             'CostoUnitario' : $preciounitario,
                             'CostoItem' : $subtotal,
                             'IdDetalleComprobanteCompra' : self.IdDetalleComprobanteCompra()  };
        ko.mapping.fromJS(adicionaldata, {} , copia);
        ko.mapping.fromJS(nuevadata, MappingCompra.DetalleNotaDebitoCompra, copia);
        var resultado = new VistaModeloDetalleNotaDebitoCompra(copia);
        base.__ko_mapping__= undefined;
        var output = ko.mapping.toJS(resultado,mappingIgnore);
        ko.mapping.fromJS(output,{}, base);
        return resultado;
      }
    }
}
