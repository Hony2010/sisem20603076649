ModeloDetalleNotaCreditoCompra = function (data) {
    var self = this;
    var base = data;
    var cabecera;
    self.PrecioUnitarioCalculado = ko.observable(0.00);
    // self.TipoCompra = ko.observable(TIPO_VENTA.MERCADERIAS);
    self.IGVUnitario = ko.observable(0.00);

    self.InicializarModelo =function(event,callback,callback2,parent) {
      if(event) {
        if(callback)
          self.callback=callback;
        if(callback2)
          self.callback2=callback2;

          cabecera = parent;
      }
    }

    self.CalcularTotales = function(data, event)
    {
      if(event)
      {
        var costounitario = self.CalcularCostoUnitarioCalculado(event);
        var preciounitario = self.CalcularPrecioUnitarioCalculado(event);
        var costoitem = self.CalcularCostoItem(event);
        var IGVUnitario = self.CalcularIGVUnitario(event);
        var IGVItem = self.CalcularIGVItem(event);
        var ISCItem = self.CalcularISCItem(event);

      }
    }

    self.CalcularTasaDescuentoUnitario = function(data, event) {
      if(event) {
        var porcentaje_decimal = parseFloatAvanzado(self.TasaDescuentoUnitario()) / 100;
        
        if (cabecera.ParametroPrecioCompra() == 1) {
          var descuentounitario = parseFloatAvanzado(self.PrecioUnitario()) * porcentaje_decimal;
        }
        else {
          var descuentounitario = parseFloatAvanzado(self.CostoUnitario()) * porcentaje_decimal;
        }
        self.DescuentoUnitario(descuentounitario.toFixed(NUMERO_DECIMALES_COMPRA));

        self.CalcularTotales(event);
      }
    }

    self.CalcularDescuentoUnitario = function(data, event) {
      if(event) {
        if (cabecera.ParametroPrecioCompra() == 1) {
          var porcentaje_decimal = parseFloatAvanzado(self.DescuentoUnitario()) / parseFloatAvanzado(self.PrecioUnitario());
        }
        else {
          var porcentaje_decimal = parseFloatAvanzado(self.DescuentoUnitario()) / parseFloatAvanzado(self.CostoUnitario());
        }
        
        var porcentaje = porcentaje_decimal * 100;
        self.TasaDescuentoUnitario(porcentaje.toFixed(NUMERO_DECIMALES_COMPRA));

        self.CalcularTotales(event);
      }
    }

    self.CalcularCostoUnitarioCalculado = function(event) {
      if(event) {      
          var costounitariocalculado = parseFloatAvanzado(self.CostoUnitario()) - parseFloatAvanzado(self.DescuentoUnitario());
          self.CostoUnitarioCalculado(costounitariocalculado);//.toFixed(NUMERO_DECIMALES_COMPRA)
          return costounitariocalculado;//.toFixed(NUMERO_DECIMALES_COMPRA)       
      }
    }

    self.CalcularPrecioUnitarioCalculado = function(event) {
      if(event) {
          var preciounitariocalculado = parseFloatAvanzado(self.PrecioUnitario()) - parseFloatAvanzado(self.DescuentoUnitario());
          self.PrecioUnitarioCalculado(preciounitariocalculado);//.toFixed(NUMERO_DECIMALES_COMPRA)
          return preciounitariocalculado;//.toFixed(NUMERO_DECIMALES_COMPRA)
      }
    }

    self.CalcularCostoItem = function(event) {
      if(event) {
        var precioitem = parseFloatAvanzado(self.Cantidad()) * parseFloatAvanzado(self.PrecioUnitarioCalculado());
        self.PrecioItem(precioitem.toFixed(NUMERO_DECIMALES_COMPRA));
        var costoitem = parseFloatAvanzado(self.Cantidad()) * parseFloatAvanzado(self.CostoUnitarioCalculado());
        self.CostoItem(costoitem.toFixed(NUMERO_DECIMALES_COMPRA));

        if (cabecera.ParametroPrecioCompra() == 1) {
          return precioitem.toFixed(NUMERO_DECIMALES_COMPRA);
        }
        else {
          return costoitem.toFixed(NUMERO_DECIMALES_COMPRA);
        }
        
      }
    }

    self.CalcularIGVUnitario = function(event) {
      if(event) {
        if(self.AfectoIGV() == 1) {
          if (cabecera.ParametroPrecioCompra() == 1) {            
            var igvunitario =((parseFloatAvanzado(self.PrecioUnitarioCalculado()) * VALOR_IGV) / (1+VALOR_IGV)) ;
            self.IGVUnitario(igvunitario);
            return igvunitario.toFixed(NUMERO_DECIMALES_COMPRA);
          }
          else {
            var igvunitario = parseFloatAvanzado(self.CostoUnitarioCalculado()) * VALOR_IGV;
            self.IGVUnitario(igvunitario);
            return igvunitario.toFixed(NUMERO_DECIMALES_COMPRA);
          }
          
        }
      }
    }

    self.CalcularIGVItem = function(event) {
      if(event) {
        if(self.AfectoIGV() == '1') {          
          var igvitem = parseFloatAvanzado(self.IGVUnitario()) * parseFloatAvanzado(self.Cantidad());
          self.IGVItem(igvitem.toFixed(NUMERO_DECIMALES_COMPRA));
          return igvitem.toFixed(NUMERO_DECIMALES_COMPRA);
        }
      }
    }

    self.CalcularISCItem = function(event) {
      if(event) {
        if(self.AfectoISC() == '1') {
          if (cabecera.ParametroPrecioCompra() == 1) {
            var iscitem = (parseFloatAvanzado(self.PrecioItem()) * parseFloatAvanzado(self.TasaISC())) / 100;
          }
          else {
            var iscitem = (parseFloatAvanzado(self.CostoItem()) * parseFloatAvanzado(self.TasaISC())) / 100;
          }
          self.ISCItem(iscitem.toFixed(NUMERO_DECIMALES_COMPRA));
          return iscitem.toFixed(NUMERO_DECIMALES_COMPRA);
        }
      }
    }

    self.Reemplazar = function(data) {
      if(data) {
        data.CostoUnitario = data.CostoUnitario === "" || data.CostoUnitario === null ? "0.00" : data.CostoUnitario;
        data.PrecioUnitario = data.PrecioUnitario === "" || data.PrecioUnitario === null ? "0.00" : data.PrecioUnitario;
        var nuevodetalle = self.NuevoDetalleNotaCreditoCompra;
        var includesList =Object.keys(ko.mapping.toJS(nuevodetalle,{ignore : ["Cantidad", "PrecioUnitario", "PrecioItem","CostoUnitario", "CostoItem", "SaldoPendienteNotaCredito"]}));
        var nuevadata = leaveJustIncludedProperties(data, includesList);
        var copia = Knockout.CopiarObjeto(nuevodetalle);
        var $cantidad = self.Cantidad() === "" ? "0.00" : self.Cantidad();
        var $descuentoitem = self.DescuentoUnitario() === "" ? "0.00" : self.DescuentoUnitario();
        var $costounitario = self.CostoUnitario() === "" ? "0.00" : self.CostoUnitario();
        var $preciounitario = self.PrecioUnitario() === "" ? "0.00" : self.PrecioUnitario();
        var $costoitem = self.CostoItem() === "" ? "0.00" : self.CostoItem();
        var $precioitem = self.PrecioItem() === "" ? "0.00" : self.PrecioItem();
        var adicionaldata= { 'Cantidad':  $cantidad ,
                             'DescuentoUnitario' : $descuentoitem,
                             'CostoUnitario' : $costounitario,
                             'CostoItem' : $costoitem,
                             'PrecioUnitario' : $preciounitario,
                             'PrecioItem' : $precioitem,
                             'IdDetalleComprobanteCompra' : self.IdDetalleComprobanteCompra()  };
        ko.mapping.fromJS(adicionaldata, {} , copia);
        ko.mapping.fromJS(nuevadata, MappingCompra.DetalleNotaCreditoCompra, copia);
        var resultado = new VistaModeloDetalleNotaCreditoCompra(copia);
        base.__ko_mapping__= undefined;
        var output = ko.mapping.toJS(resultado,mappingIgnore);
        ko.mapping.fromJS(output,{}, base);
        return resultado;
      }
    }
}
