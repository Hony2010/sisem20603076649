VistaModeloDetalleNotaDebitoCompra = function (data, base) {
    var self = this;
    var cabecera = base;
    var copia_filadetalle = null;
    ko.mapping.fromJS(data, MappingCatalogo , self);
    // self.IdReferenciaDCV = ko.observable(self.IdDetalleComprobanteCompra());
    self.AjusteValor = ko.observable("0.00");
    self.CostoUnitarioReferencia = ko.observable(self.CostoUnitario());

    ModeloDetalleNotaDebitoCompra.call(this,self);

    self.AjustesValor = ko.computed(function() {
      var costoItem = parseFloatAvanzado(self.CostoItem());
      var cantidad = parseFloatAvanzado(self.Cantidad());
      var value = costoItem / cantidad;
      self.AjusteValor(value.toFixed(NUMERO_DECIMALES_COMPRA));
    }, this);

    self.DescuentoUnitario = ko.observable("0.00");
    // self.IdReferenciaDCV = ko.observable()

    self.InicializarVistaModelo =  function (event,callback,callback2) {
      if(event) {
          self.Producto.InicializarVistaModelo(data,event);
          var input = {id : self.InputProducto() };
          $(self.InputProducto()).autoCompletadoProducto(input,event,self.ValidarAutocompletadoProducto);
          self.InicializarModelo(event,callback,callback2);

          $(self.InputProducto()).on("focusout",function(event){
            self.ValidarNombreProducto(undefined,event);
          });
      }
    }

    self.InputCodigoMercaderia = ko.computed( function() {
      if(self.IdDetalleComprobanteCompra != undefined) {
        return "#"+self.IdDetalleComprobanteCompra()+"_input_CodigoMercaderia";
      }
      else {
        return "";
      }
    },this);

    self.SpanAbreviaturaUnidadMedida = ko.computed( function() {
      if(self.IdDetalleComprobanteCompra != undefined) {
        return "#"+self.IdDetalleComprobanteCompra()+"_span_AbreviaturaUnidadMedida";
      }
      else {
        return "";
      }
    },this);

    self.InputProducto = ko.computed( function() {
      if(self.IdDetalleComprobanteCompra != undefined) {
        return "#"+self.IdDetalleComprobanteCompra()+"_input_NombreProducto";
      }
      else {
        return "";
      }
    },this);

    self.InputCantidad = ko.computed( function() {
      if(self.IdDetalleComprobanteCompra != undefined) {
        return "#"+self.IdDetalleComprobanteCompra()+"_input_Cantidad";
      }
      else {
        return "";
      }
    },this);

    self.InputCostoUnitario = ko.computed( function() {
      if(self.IdDetalleComprobanteCompra != undefined) {
        return "#"+self.IdDetalleComprobanteCompra()+"_input_CostoUnitario";
      }
      else {
        return "";
      }
    },this);

    self.InputCostoItem = ko.computed( function() {
      if(self.IdDetalleComprobanteCompra != undefined) {
        return "#"+self.IdDetalleComprobanteCompra()+"_input_CostoItem";
      }
      else {
        return "";
      }
    },this);

    self.InputDescuentoUnitario = ko.computed( function() {
      if(self.IdDetalleComprobanteCompra != undefined) {
        return "#"+self.IdDetalleComprobanteCompra()+"_input_DescuentoUnitario";
      }
      else {
        return "";
      }
    },this);

    self.InputAfectoIGV = ko.computed( function() {
      if(self.IdDetalleComprobanteCompra != undefined) {
        return "#"+self.IdDetalleComprobanteCompra()+"_input_AfectoIGV";
      }
      else {
        return "";
      }
    },this);

    self.InputAfectoISC = ko.computed( function() {
      if(self.IdDetalleComprobanteCompra != undefined) {
        return "#"+self.IdDetalleComprobanteCompra()+"_input_AfectoISC";
      }
      else {
        return "";
      }
    },this);

    self.InputAjusteValor = ko.computed( function() {
      if(self.IdDetalleComprobanteCompra != undefined) {
        return "#"+self.IdDetalleComprobanteCompra()+"_input_AjusteValor";
      }
      else {
        return "";
      }
    },this);

    self.InputValorVentaItem = ko.computed( function() {
      if(self.IdDetalleComprobanteCompra != undefined) {
        return "#"+self.IdDetalleComprobanteCompra()+"_input_ValorVentaItem";
      }
      else {
        return "";
      }
    },this);

    self.InputISCPorcentaje = ko.computed( function() {
      if(self.IdDetalleComprobanteCompra != undefined) {
        return "#"+self.IdDetalleComprobanteCompra()+"_input_ISCPorcentaje";
      }
      else {
        return "";
      }
    },this);

    self.InputOpcion = ko.computed( function() {
      if(self.IdDetalleComprobanteCompra != undefined) {
        return "#"+self.IdDetalleComprobanteCompra()+"_a_opcion";
      }
      else {
        return "";
      }
    },this);

    // self.InputOpcion = ko.computed( function() {
    //   if(self.IdDetalleComprobanteCompra != undefined) {
    //     return "#"+self.IdDetalleComprobanteCompra()+"_a_opcion";
    //   }
    //   else {
    //     return "";
    //   }
    // },this);

    self.OnFocus = function(data,event,$callbackParent,$callback) {
      if(event)  {
        self.CopiaDetalle(data, event);
        $callbackParent(data,event);
        // $callback(data,event);
      }

      return true;
    }

    self.OnKeyEnter = function(data,event,$callbackParent) {
      if(event) {
        $callbackParent(data,event);
      }
      return true;
    }

    self.procesado = false;

    self.OcultarCamposTipoCompra = function(data, event)
    {
      if(event)
      {
        if (data.TipoCompra() == TIPO_COMPRA.MERCADERIAS) {
          // $(self.InputValorVentaItem()).closest('td').addClass('ocultar');
          // $(self.InputValorVentaItem()).closest('td').addClass('ocultar');
          // $(self.InputCodigoMercaderia()).addClass('no-tab');
          // $(self.InputCodigoMercaderia()).attr('tabIndex','-1');
          $(self.InputValorVentaItem()).closest('td').addClass('ocultar');
          $(self.InputValorVentaItem()).addClass('no-tab');
          $(self.InputValorVentaItem()).attr('tabIndex','-1');
        }
        else if (data.TipoCompra() == TIPO_COMPRA.GASTOS) {
          $(self.InputCodigoMercaderia()).closest('td').addClass('ocultar');
          $(self.SpanAbreviaturaUnidadMedida()).closest('td').addClass('ocultar');
          $(self.InputCantidad()).closest('td').addClass('ocultar');
          $(self.InputCostoUnitario()).closest('td').addClass('ocultar');
          $(self.InputDescuentoUnitario()).closest('td').addClass('ocultar');
          $(self.InputCostoItem()).closest('td').addClass('ocultar');
          $(self.InputISCPorcentaje()).closest('td').addClass('ocultar');
          $(self.InputAfectoISC()).closest('td').addClass('ocultar');

          $(self.InputCodigoMercaderia()).addClass('no-tab');
          $(self.InputCodigoMercaderia()).attr('tabIndex','-1');
          $(self.InputCantidad()).addClass('no-tab');
          $(self.InputCantidad()).attr('tabIndex','-1');
          $(self.InputCostoUnitario()).addClass('no-tab');
          $(self.InputCostoUnitario()).attr('tabIndex','-1');
          $(self.InputDescuentoUnitario()).addClass('no-tab');
          $(self.InputDescuentoUnitario()).attr('tabIndex','-1');
          $(self.InputCostoItem()).addClass('no-tab');
          $(self.InputCostoItem()).attr('tabIndex','-1');
          $(self.InputISCPorcentaje()).addClass('no-tab');
          $(self.InputISCPorcentaje()).attr('tabIndex','-1');
          $(self.InputAfectoISC()).addClass('no-tab');
          $(self.InputAfectoISC()).attr('tabIndex','-1');
        }
        else if (data.TipoCompra() == TIPO_COMPRA.COSTOSAGREGADO) {
          $(self.InputCodigoMercaderia()).closest('td').addClass('ocultar');
          $(self.SpanAbreviaturaUnidadMedida()).closest('td').addClass('ocultar');
          $(self.InputCantidad()).closest('td').addClass('ocultar');
          $(self.InputCostoUnitario()).closest('td').addClass('ocultar');
          $(self.InputDescuentoUnitario()).closest('td').addClass('ocultar');
          $(self.InputCostoItem()).closest('td').addClass('ocultar');
          $(self.InputISCPorcentaje()).closest('td').addClass('ocultar');
          $(self.InputAfectoISC()).closest('td').addClass('ocultar');

          $(self.InputCodigoMercaderia()).addClass('no-tab');
          $(self.InputCodigoMercaderia()).attr('tabIndex','-1');
          $(self.InputCantidad()).addClass('no-tab');
          $(self.InputCantidad()).attr('tabIndex','-1');
          $(self.InputCostoUnitario()).addClass('no-tab');
          $(self.InputCostoUnitario()).attr('tabIndex','-1');
          $(self.InputDescuentoUnitario()).addClass('no-tab');
          $(self.InputDescuentoUnitario()).attr('tabIndex','-1');
          $(self.InputCostoItem()).addClass('no-tab');
          $(self.InputCostoItem()).attr('tabIndex','-1');
          $(self.InputISCPorcentaje()).addClass('no-tab');
          $(self.InputISCPorcentaje()).attr('tabIndex','-1');
          $(self.InputAfectoISC()).addClass('no-tab');
          $(self.InputAfectoISC()).attr('tabIndex','-1');
          // $(self.InputCodigoMercaderia()).addClass('no-tab');
          // $(self.InputCodigoMercaderia()).attr('tabIndex','-1');
        }
      }
    }

    self.OnKeyEnterCodigoMercaderia = function(data,event,$callbackParent) {
        if(event) {
          if(event.keyCode == TECLA_ENTER) {

            self.ValidarProductoPorCodigo(data,event,function($data,$event,$valid){
                self.procesado = true;
                $callbackParent($data,$event);
                self.procesado = false;
                var $evento = { target : self.InputProducto() };
                self.ValidarNombreProducto(data,$evento);
            });
          }
          return true;
        }
    }

    self.ValidarAjusteValor = function(data,event) {
      if(event) {
        $(event.target).validate(function(valid, elem) {
           //console.log('Element '+elem.name+' is '+( valid ? 'valid' : 'invalid'));
        });
      }
    }

    self.ValidarCodigoMercaderia = function(data,event) {
      if(event) {//focusout
        if(!self.procesado) {
            self.ValidarProductoPorCodigo(data,event,function($data,$event,$valid){
              var $evento = { target : self.InputProducto() };
              self.ValidarNombreProducto(data,$evento);
              $(event.relatedTarget).select();
          });
        }
      }
    }

    self.ValidarProductoPorCodigo = function(data,event,$callback) {
        if(event) {
          var datajs = { CodigoMercaderia : data.CodigoMercaderia() ,IdGrupoProducto : TIPO_VENTA.MERCADERIAS };
          self.Producto.ObtenerProductoPorCodigo(datajs,event,function($data,$event){
            var $input = $(self.InputCodigoMercaderia());
            if ($data != null)  {
              $input.attr("data-validation-found","true");
              $input.attr("data-validation-text-found",$data.NombreProducto);
              var item =self.Reemplazar($data);
            }
            else {
              var item = null;
              $input.attr("data-validation-found","false");
              $input.attr("data-validation-text-found","");
            }

            $(event.target).validate(function(valid, elem) {
               self.callback(item,$event,function($data3,$event3) {
                 if ($callback) $callback($data3,$event3,valid);
               });
            });
          });
        }
    }

    self.ValidarNombreProducto = function(data,event) {
      if(event) {
        $(event.target).validate(function(valid, elem) {
        });
      }
    }

    self.ValidarAutocompletadoProducto = function(data,event) {
      if(event) {
        if (data == -1) {
          if($(self.InputCodigoMercaderia()).attr("data-validation-text-found") ==  $(self.InputProducto()).val())  {
              $(self.InputCodigoMercaderia()).attr("data-validation-found","true");
              var $evento = { target : self.InputProducto() };
              self.ValidarNombreProducto(data,$evento);
          }
          else {
            $(self.InputCodigoMercaderia()).attr("data-validation-text-found","");
            $(self.InputCodigoMercaderia()).attr("data-validation-found","false");
            var $evento = { target : self.InputProducto() };
            self.ValidarNombreProducto(data,$evento);
          }
          // $(self.InputCantidad()).focus();
        }
        else {

          if($(self.InputCodigoMercaderia()).attr("data-validation-text-found") == $(self.InputProducto()).val())  {
              $(self.InputCodigoMercaderia()).attr("data-validation-found","true");
              var $evento = { target : self.InputProducto() };
              self.ValidarNombreProducto(data,$evento);
              // $(self.InputCantidad()).focus();
          }
          else {

            $(self.InputCodigoMercaderia()).attr("data-validation-text-found",data.NombreProducto);
            $(self.InputCodigoMercaderia()).attr("data-validation-found","true");
            var $evento = { target : self.InputProducto() };
            self.ValidarNombreProducto(data,$evento);
            var $evento2 = { target : self.InputCodigoMercaderia() };
            var $data = Knockout.CopiarObjeto(data);

            self.ValidarProductoPorCodigo($data,$evento2,function($data3,$evento3){

              var id_combo = "#" + self.IdDetalleComprobanteCompra() + "_input_NombreProducto";
              // if($(id_combo).closest("tr").next().length > 0)
              // {
              //   $(id_combo).closest("tr").next().find("input")[0].focus();
              // }
              // else {
              //   $("#btn_Grabar").focus();
              // }
            });
          }
        }
      }
    }

    self.ValidarCantidad = function(data,event) {
      if(event) {
        if(data.Cantidad() === "") data.Cantidad("0.00");

        $(event.target).validate(function(valid, elem) {
        });


      }
    }

    self.ValidarValorVentaItem = function(data,event) {
      if(event) {
        if(data.CostoItem() === "") data.ValorVentaItem("1");
        $(event.target).validate(function(valid, elem) {

        });
      }
    }

    // self.ValidarAfectoIGV = function(data,event) {
    //   if(event) {
    //     if(data.AfectoIGV() ==="") data.AfectoIGV("1");
    //
    //     $(event.target).validate(function(valid, elem) {
    //     });
    //
    //   }
    // }

    self.ValidarCostoUnitario = function(data,event) {
      if(event) {
        if(data.CostoUnitario() === "") data.CostoUnitario("0.00");
        $(event.target).validate(function(valid, elem) {

        });
      }
    }

    self.ValidarDescuentoUnitario = function(data,event) {
      if(event) {
        if(data.DescuentoUnitario() ==="") data.DescuentoUnitario("0.00");

        $(event.target).validate(function(valid, elem) {
        });

      }
    }

    self.OnKeyEnterOpcion = function(data,event,$callbackParent) {
      if(event) {
        if(event.keyCode == TECLA_ENTER) {
          $callbackParent(data,event);
        }
        else if (event.keyCode == TECLA_TAB) {

          var ultimafila =$("tr:last").index();
          var fila = $(event.target).closest("tr").index();

          if (fila == ultimafila -1 ) {
              $("#DescuentoGlobal").focus();
              event.preventDefault();
              event.stopPropagation();
          }
        }

        return true;
    }
  }

  //NUEVAS VALIDACIONES
  self.ValidarDescuentoUnitario = function(data,event) {
    if(event) {
      $(event.target).validate(function(valid, elem) {
         //console.log('Element '+elem.name+' is '+( valid ? 'valid' : 'invalid'));
      });
    }
  }

  self.ValidarImporte = function(data,event) {
    if(event) {
      $(event.target).validate(function(valid, elem) {
         //console.log('Element '+elem.name+' is '+( valid ? 'valid' : 'invalid'));
      });
    }
  }

  //NUEVAS FUNCIONES
  self.CopiaDetalle = function(data, event)
  {
    if(event)
    {
      var desUnit = data.DescuentoUnitario();
      var ajuVal = data.AjusteValor();
      copia_filadetalle = ko.mapping.toJS(data);
      copia_filadetalle.DescuentoUnitario = desUnit;
      copia_filadetalle.AjusteValor = ajuVal;
    }
  }

  self.CalcularCostoItemDetalle = function(data, event)
  {
    if(event)
    {
      var resultado  = 0;
      var saldo_item = parseFloatAvanzado(data.SaldoPendienteNotaDebito());
      // saldo_item = 100; //borrar

      if(data.Cantidad != undefined  && data.NombreProducto != undefined) {
        var cantidad = parseFloatAvanzado(data.Cantidad());
        var costoUnitarioCalculado = parseFloatAvanzado(data.CostoUnitarioCalculado());
        resultado = (cantidad * costoUnitarioCalculado);

        if(resultado > saldo_item)
        {
          data.CostoItem("0.00");
          data.Cantidad("0.00");
          alertify.alert("VALIDACION NOTA DE DEBITO EN COMPRA!", "El Saldo no puede ser superado.", function(){
            $(self.InputCantidad()).focus();
          });
        }
        else{
          data.CostoItem(resultado.toFixed(NUMERO_DECIMALES_COMPRA));
        }
      }
      else {
        //return resultado;
        data.CostoItem(resultado)
      }

      // self.CalcularCostoItemElegido(data, event);
      // self.CalcularTotales(data, event);
      cabecera.CalcularTotales(data, event);
    }
  }

  self.CalcularImporteByAjusteValor = function(data, event)
  {
    if(event)
    {
      var cantidad = parseFloatAvanzado(data.Cantidad());
      var ajustevalor = parseFloatAvanzado(data.AjusteValor());

      var importe = cantidad * ajustevalor;

      data.CostoItem(importe.toFixed(NUMERO_DECIMALES_COMPRA));
      data.CostoUnitario(ajustevalor.toFixed(NUMERO_DECIMALES_COMPRA));

      cabecera.ActualizarTotales(data, event);
    }
  }

}
