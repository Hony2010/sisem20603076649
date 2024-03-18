VistaModeloTecladoVirtual = function (data) {
  var self = this;
  // var baseVista = this;
  // var baseBusqueda = this;
  ko.mapping.fromJS(data, {}, self);

  self.InicializarVistaModelo = function(data, event, base) {
    if(event) {
      baseVista = base;
    }
  }

  self.PushNumber = function(data, event, base) {

    if(event) {
      var $form = $(base.Options.IDForm);
      var tecladovirtual = $form.find("#input-teclado-virtual").val();
      var input = $(event.target).data('number');
      if(tecladovirtual == "Cantidad"){
        var idDetalle = $form.find(".detalle-punto-venta").find('.active').data('iddetallecomprobanteventa');
        var detalleFiltrado = ko.utils.arrayFilter(base.DetallesComprobanteVenta(), function(item){
          return item.IdDetalleComprobanteVenta() == idDetalle;
        });
        if(detalleFiltrado.length > 0) {
          var newbuf = '' + parseFloatAvanzado(detalleFiltrado[0].Cantidad().slice(0));
          newbuf = ValueNumber(input, newbuf, tecladovirtual);
          detalleFiltrado[0].Cantidad(newbuf);
          detalleFiltrado[0].CalcularSubTotal(data,event);
          base.CalcularTotales(event);
        } else {
          return false;
        }
      } else if(tecladovirtual == "MontoRecibido"){
          var montorecibido = base.MontoRecibido();
          var newbuf = '' + parseFloatAvanzado(montorecibido.slice(0));
          newbuf = ValueNumber(input, newbuf, tecladovirtual);
          base.MontoRecibido(newbuf);
          base.IndicadorCambioMontoRecibido(true);
      } else if(tecladovirtual == "Cliente") {
          if( !$("#Cliente").hasClass("valid")){
            var newbuf = $("#Cliente").val();
            newbuf = ValueNumber(input, newbuf, tecladovirtual);
            if (input === 'BACKSPACE' && newbuf === '' && $("#Cliente").val().length == 0 ) {

            }  else {
              $("#Cliente").val(newbuf);
              $("#Cliente").trigger($.Event("keyup", { keyCode: 49, key: 49, which: 49, ctrlKey: false}));
            }
          }
      } else if(tecladovirtual == "ClientePreCuenta") {
          if( !$("#ClientePreCuenta").hasClass("valid")){
            var newbuf = $("#ClientePreCuenta").val();
            newbuf = ValueNumber(input, newbuf, tecladovirtual);
            if (input === 'BACKSPACE' && newbuf === '' && $("#ClientePreCuenta").val().length == 0 ) {
              
            }  else {
              $("#ClientePreCuenta").val(newbuf);
              $("#ClientePreCuenta").trigger($.Event("keyup", { keyCode: 49, key: 49, which: 49, ctrlKey: false}));
            }
          }
        } else if (tecladovirtual == "DescuentoUnitario") {
        var idDetalle = $form.find(".detalle-punto-venta").find('.active').data('iddetallecomprobanteventa');
        var detalleFiltrado = ko.utils.arrayFilter(base.DetallesComprobanteVenta(), function(item){
          return item.IdDetalleComprobanteVenta() == idDetalle;
        });
        if(detalleFiltrado.length > 0) {
          var newbuf = '' + parseFloatAvanzado(detalleFiltrado[0].DescuentoUnitario().slice(0));
          newbuf = ValueNumber(input, newbuf, tecladovirtual);
          detalleFiltrado[0].DescuentoUnitario(newbuf);
          detalleFiltrado[0].CalcularSubTotal(data,event);
          base.CalcularTotales(event);
        } else {
          return false;
        }
      } else if (tecladovirtual == "PrecioUnitario") {
        var idDetalle = $form.find(".detalle-punto-venta").find('.active').data('iddetallecomprobanteventa');
        var detalleFiltrado = ko.utils.arrayFilter(base.DetallesComprobanteVenta(), function(item){
          return item.IdDetalleComprobanteVenta() == idDetalle;
        });
        if(detalleFiltrado.length > 0) {
          var newbuf = '' + parseFloatAvanzado(detalleFiltrado[0].PrecioUnitario().slice(0));
          newbuf = ValueNumber(input, newbuf, tecladovirtual);
          detalleFiltrado[0].PrecioUnitario(newbuf);
          detalleFiltrado[0].CalcularSubTotal(data,event);
          base.CalcularTotales(event);
        } else {
          return false;
        }
      } else if (tecladovirtual == "PorcentajeDescuento") {
        var idDetalle = $form.find(".detalle-punto-venta").find('.active').data('iddetallecomprobanteventa');
        var detalleFiltrado = ko.utils.arrayFilter(base.DetallesComprobanteVenta(), function(item){
          return item.IdDetalleComprobanteVenta() == idDetalle;
        });
        if(detalleFiltrado.length > 0) {
          var newbuf = '' + parseFloatAvanzado(detalleFiltrado[0].PorcentajeDescuento().slice(0));
          newbuf = ValueNumber(input, newbuf, tecladovirtual);
          detalleFiltrado[0].PorcentajeDescuento(newbuf);
          detalleFiltrado[0].CalcularDescuentoUnitarioPorPorcentaje(data,event);
          detalleFiltrado[0].CalcularSubTotal(data,event);
          base.CalcularTotales(event);
        } else {
          return false;
        }
      } else {
        return false;
      }
      base.CalcularVuelto(data, event);
      if(newbuf != '' && ( tecladovirtual == "Cliente" ||  tecladovirtual == "ClientePreCuenta") && !$("#"+ tecladovirtual).hasClass("valid")){
        $("#eac-container-" + tecladovirtual + " ul").show();
      }
      return newbuf;
    }
  }

  function ValueNumber(input, newbuf, tecladovirtual) {
    var decimal_point = ".";
    if (input === 'POINT') {
      if (newbuf.indexOf(decimal_point) < 0) {
        newbuf = newbuf + decimal_point;
      }
      this.buffer = newbuf;
      this.point = 'true';
    } else if (input === 'BACKSPACE') {
      if (this.point == 'true') {
        this.buffer = this.buffer.substring(0, this.buffer.length - 1);
        newbuf = this.buffer;
      } else {
        newbuf = newbuf.substring(0, newbuf.length - 1);
      }
      if(typeof this.buffer != 'undefined') {
        if(this.buffer.indexOf(decimal_point) == -1){
          this.point = 'false';
        }
      }
      if(newbuf == '') {
        if(tecladovirtual != "Cliente" && tecladovirtual != "ClientePreCuenta"){
          newbuf = '0';
          this.point = 'false';
        } 
        else {
          $("#eac-container-" + tecladovirtual + " ul").hide(1000);
        }
      }
    } else if (!isNaN(parseInt(input))) {
      if (this.point == 'true') {
        var postclear = '' + parseFloatAvanzado(this.buffer.slice(0));
        if(postclear == newbuf){
          if(tecladovirtual != "Cantidad"){
          // if($(".detalle-punto-venta").is(":visible")){
            if((this.buffer.length - this.buffer.indexOf(decimal_point)) <= CANTIDAD_DECIMALES_VENTA.CANTIDAD){
              this.buffer += input;
              newbuf = this.buffer;
            }
          } else {
            if((this.buffer.length - this.buffer.indexOf(decimal_point)) <= NUMERO_DECIMALES_VENTA){
              this.buffer += input;
              newbuf = this.buffer;
            }
          }
        } else {
          newbuf += input;
          this.point = 'false';
        }
      } else {
        newbuf += input;
      }
    }
    return newbuf;
  }
}