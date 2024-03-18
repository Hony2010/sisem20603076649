VistaModeloDetalleListaPrecio = function (data, base) {
    var self = this;
    var cabecera = base;
    ko.mapping.fromJS(data, MappingCatalogo , self);
    self.DecimalPrecio = ko.observable(CANTIDAD_DECIMALES_VENTA.PRECIO_UNITARIO);
    self.DecimalLista = ko.observable(2);

    // self.UltimoPrecioFormateado = function(){
    //   return 
    // }
    self.UltimoPrecioFormateado = ko.computed( function () {
      var resultado =accounting.formatNumber(self.UltimoPrecio(), CANTIDAD_DECIMALES_VENTA.PRECIO_UNITARIO);
      return resultado;
    },this);

    // ModeloDetalleListaPrecio.call(this,self);

    self.InicializarVistaModelo =  function (event,callback,callback2) {
      if(event) {
          // self.InicializarModelo(event,callback,callback2);
          $(self.InputProducto()).on("focusout",function(event){
            self.ValidarNombreProducto(undefined,event);
          });
      }
    }

    self.InputCodigoMercaderia = ko.computed( function() {
      if(self.IdListaPrecioMercaderia != undefined) {
        return "#"+self.IdListaPrecioMercaderia()+"_input_CodigoMercaderia";
      }
      else {
        return "";
      }
    },this);

    self.ValidarPrecio = function(data,event) {
      if(event) {
        // var precio =parseFloat($(event.target).val());

        // data.Precio(precio.toFixed(2));
      }
    }

    self.OnKeyEnter = function(data,event,$callbackParent) {
      if(event) {
        $callbackParent(data,event);
      }
      return true;
    }

    self.OnFocus = function(data,event) {
      if(event)  {
          $(event.target).select();
      }
    }

    self.OnChangePrecio = function (data,event) {
      if (event) {
        var objeto = { IdProducto : data.IdProducto() , IndicadorProducto : data.IndicadorProducto() };        
        cabecera.CopiaIdProductosDetalle().push(objeto);        
        console.log(ko.mapping.toJS(cabecera.CopiaIdProductosDetalle()));
      }
    }

    self.CalcularPrecioVenta = function(data, event)
    {
      if(event)
      {
        if(cabecera.ParametroCostoOPrecioPromedio() == 0)
        {
          self.CalcularPrecioVentaPorCosto(data, event);
        }
        else
        {
          self.CalcularPrecioVentaPorPrecio(data, event);
        }
      }
    }

    self.CalcularPrecioVentaPorCosto = function(data, event)
    {
      if(event)
      {
        var costoPromedioPonderado = parseFloatAvanzado(self.CostoPromedioPonderado());
        var margenPorcentaje = parseFloatAvanzado(self.MargenPorcentaje());
        var porcentaje = margenPorcentaje / 100;
        var margenUtilidad = costoPromedioPonderado * porcentaje;
        var valorVenta = costoPromedioPonderado + margenUtilidad;

        var valorIGV = 0;
        if(self.IdTipoAfectacionIGV() == TIPO_AFECTACION_IGV.GRAVADO)
        {
          valorIGV = valorVenta * VALOR_IGV;
        }

        var precioVenta = valorVenta + valorIGV;

        self.MargenUtilidad(margenUtilidad);
        self.ValorVenta(valorVenta);
        self.ValorIGV(valorIGV);
        self.Precio(precioVenta);
      }
    }

    self.CalcularPrecioVentaPorPrecio = function(data, event)
    {
      if(event)
      {
        var precioPromedioCompra = parseFloatAvanzado(self.PrecioPromedioCompra());
        var margenPorcentaje = parseFloatAvanzado(self.MargenPorcentaje());
        var porcentaje = margenPorcentaje / 100;
        var margenUtilidad = precioPromedioCompra * porcentaje;

        var precioVenta = precioPromedioCompra + margenUtilidad;

        self.MargenUtilidad(margenUtilidad);
        self.Precio(precioVenta);
      }
    }

    self.CalcularPorPrecioVenta = function(data, event)
    {
      if(event)
      {
        if(cabecera.ParametroCostoOPrecioPromedio() == 0)
        {
          self.CalcularPorPrecioVentaParaCosto(data, event);
        }
        else
        {
          self.CalcularPorPrecioVentaParaPrecio(data, event);
        }
      }
    }

    self.CalcularPorPrecioVentaParaCosto = function(data, event)
    {
      if(event)
      {
        //INVERSION
        var precioVenta = parseFloatAvanzado(self.Precio());
        var valorVenta = precioVenta;
        var valorIGV = 0;
        if(self.IdTipoAfectacionIGV() == TIPO_AFECTACION_IGV.GRAVADO)
        {
          valorVenta = precioVenta / (1 + VALOR_IGV);
          valorIGV = valorVenta * VALOR_IGV;
        }
        
        //CON PORCENTAJE
        // var margenPorcentaje = parseFloatAvanzado(self.MargenPorcentaje());
        // var porcentaje = margenPorcentaje / 100;
        // var costoPromedioPonderado = valorVenta / (1 + porcentaje);
        // var margenUtilidad = costoPromedioPonderado * porcentaje;
        var costoPromedioPonderado = parseFloatAvanzado(self.CostoPromedioPonderado());
        var porcentaje = (valorVenta - costoPromedioPonderado) / costoPromedioPonderado;
        var margenPorcentaje = porcentaje * 100;
        var margenUtilidad = costoPromedioPonderado * porcentaje;

        // self.CostoPromedioPonderado(costoPromedioPonderado);
        self.ValorVenta(valorVenta);
        self.ValorIGV(valorIGV);
        self.MargenUtilidad(margenUtilidad);
        self.MargenPorcentaje(margenPorcentaje);
      }
    }

    self.CalcularPorPrecioVentaParaPrecio = function(data, event)
    {
      if(event)
      {
        //INVERSION
        var precioVenta = parseFloatAvanzado(self.Precio());
        var precioPromedioCompra = parseFloatAvanzado(self.PrecioPromedioCompra());
        var porcentaje = (precioVenta - precioPromedioCompra) / precioPromedioCompra;
        var margenPorcentaje = porcentaje * 100;
        var margenUtilidad = precioPromedioCompra * porcentaje;

        self.MargenUtilidad(margenUtilidad);
        self.MargenPorcentaje(margenPorcentaje);
      }
    }
}
