VistaModeloDetalleNotaCredito = function (data) {
    var self = this;
    var copia_filadetalle = null;
    ko.mapping.fromJS(data, MappingCatalogo , self);
    // self.IdReferenciaDCV = ko.observable(self.IdDetalleComprobanteVenta());

    ModeloDetalleNotaCredito.call(this,self);

    // self.DescuentoUnitario = ko.observable("0.00");
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
      if(self.IdDetalleComprobanteVenta != undefined) {
        return "#"+self.IdDetalleComprobanteVenta()+"_input_CodigoMercaderia";
      }
      else {
        return "";
      }
    },this);

    self.InputProducto = ko.computed( function() {
      if(self.IdDetalleComprobanteVenta != undefined) {
        return "#"+self.IdDetalleComprobanteVenta()+"_input_NombreProducto";
      }
      else {
        return "";
      }
    },this);

    self.InputCantidad = ko.computed( function() {
      if(self.IdDetalleComprobanteVenta != undefined) {
        return "#"+self.IdDetalleComprobanteVenta()+"_input_Cantidad";
      }
      else {
        return "";
      }
    },this);

    self.InputPrecioUnitario = ko.computed( function() {
      if(self.IdDetalleComprobanteVenta != undefined) {
        return "#"+self.IdDetalleComprobanteVenta()+"_input_PrecioUnitario";
      }
      else {
        return "";
      }
    },this);

    self.InputDescuentoItem = ko.computed( function() {
      if(self.IdDetalleComprobanteVenta != undefined) {
        return "#"+self.IdDetalleComprobanteVenta()+"_input_DescuentoItem";
      }
      else {
        return "";
      }
    },this);

    self.InputOpcion = ko.computed( function() {
      if(self.IdDetalleComprobanteVenta != undefined) {
        return "#"+self.IdDetalleComprobanteVenta()+"_a_opcion";
      }
      else {
        return "";
      }
    },this);

    self.InputOpcion = ko.computed( function() {
      if(self.IdDetalleComprobanteVenta != undefined) {
        return "#"+self.IdDetalleComprobanteVenta()+"_a_opcion";
      }
      else {
        return "";
      }
    },this);

    self.OnFocus = function(data,event,$callbackParent,$callback) {
      if(event)  {
        $callbackParent(data,event);
        $callback(data,event);
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
          $(self.InputCantidad()).focus();
        }
        else {

          if($(self.InputCodigoMercaderia()).attr("data-validation-text-found") == $(self.InputProducto()).val())  {
              $(self.InputCodigoMercaderia()).attr("data-validation-found","true");
              var $evento = { target : self.InputProducto() };
              self.ValidarNombreProducto(data,$evento);
              $(self.InputCantidad()).focus();
          }
          else {

            $(self.InputCodigoMercaderia()).attr("data-validation-text-found",data.NombreProducto);
            $(self.InputCodigoMercaderia()).attr("data-validation-found","true");
            var $evento = { target : self.InputProducto() };
            self.ValidarNombreProducto(data,$evento);
            var $evento2 = { target : self.InputCodigoMercaderia() };
            var $data = Knockout.CopiarObjeto(data);

            self.ValidarProductoPorCodigo($data,$evento2,function($data3,$evento3){
              $(self.InputCantidad()).focus();
              $(self.InputCodigoMercaderia()).next().focus();
              $(event.target).enterToTab(event)
              var id_combo = "#" + self.IdDetalleComprobanteVenta() + "_input_NombreProducto";
              if($(id_combo).closest("tr").next().length > 0)
              {
                $(id_combo).closest("tr").next().find("input")[0].focus();
              }
              else {
                $("#btn_Grabar").focus();
              }
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

    self.ValidarPrecioUnitario = function(data,event) {
      if(event) {
        if(data.PrecioUnitario() === "") data.PrecioUnitario("0.00");
        $(event.target).validate(function(valid, elem) {

        });
      }
    }

    self.ValidarDescuentoItem = function(data,event) {
      if(event) {
        if(data.DescuentoItem() ==="") data.DescuentoItem("0.00");

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
      var a = data.DescuentoUnitario();
      copia_filadetalle = ko.mapping.toJS(data);
      copia_filadetalle.DescuentoUnitario = a;
      $(event.target).select();
      // copia_filadetalle = Knockout.CopiarObjeto(data);
    }
  }

  self.CalcularSubTotalItem = function(data, event)
  {
    if(event)
    {
      var resultado  = 0;
      var saldo_item = parseFloatAvanzado(data.SaldoPendienteNotaCredito());
      
      if(data.Cantidad != undefined  && data.NombreProducto != undefined) {
        // resultado = (data.Cantidad() * data.PrecioUnitario()) - data.DescuentoItem();
        resultado = (parseFloatAvanzado(data.Cantidad()) * parseFloatAvanzado(data.PrecioUnitario()));

        if(resultado > saldo_item)
        {
          data.SubTotal("0.00");
          data.Cantidad("0");
          alertify.alert("VALIDACION NOTA DE CREDITO EN VENTA","El Saldo no puede ser superado.", function(){
            $(self.InputCantidad()).focus();
          });
        }
        else{
          // if ( data.NombreProducto() == "" || data.NombreProducto() == null) resultado = 0;
          data.SubTotal(resultado.toFixed(NUMERO_DECIMALES_VENTA));
          //return data.SubTotal();
        }
      }
      else {
        //return resultado;
        data.SubTotal(resultado)
      }

      // self.CalcularSubTotalItemElegido(data, event);
      ViewModels.data.NotaCredito.ActualizarTotales(data, event);
      // self.FuncionRetornadora(event, data);
    }
  }


  self.CalcularDescuentoUnitarioByImporte = function(data, event)
  {
    if(event)
    {
      var cantidad = parseFloatAvanzado(data.Cantidad());
      var importe = parseFloatAvanzado(data.SubTotal());
      var saldo_item = parseFloatAvanzado(data.SaldoPendienteNotaCredito());

      var descuentounitario = importe / cantidad;

      if(importe > saldo_item){
        data.SubTotal(saldo_item.toFixed(NUMERO_DECIMALES_VENTA));
        self.CalcularImporteByDescuentoUnitario(data, event);
        alertify.alert("VALIDACION NOTA DE CREDITO EN VENTA","El Saldo no puede ser superado.", function(){
          $("#btn_Grabar").focus();
        });
      }
      else {
        data.DescuentoUnitario(descuentounitario.toFixed(NUMERO_DECIMALES_VENTA));
      }

      ViewModels.data.NotaCredito.ActualizarTotales(data, event);

    }
  }

  self.CalcularImporteByDescuentoUnitario = function(data, event)
  {
    if(event)
    {
      var cantidad = parseFloatAvanzado(data.Cantidad());
      var descuentounitario = parseFloatAvanzado(data.DescuentoUnitario());
      var importe = cantidad * descuentounitario;
      var saldo_item = parseFloatAvanzado(data.SaldoPendienteNotaCredito());
      if(importe > saldo_item)
      {
        alertify.alert("VALIDACION NOTA DE CREDITO EN VENTA","El Saldo no puede ser superado.", function(){
          $("#btn_Grabar").focus();
        });
        data.SubTotal(saldo_item.toFixed(NUMERO_DECIMALES_VENTA));
        self.CalcularDescuentoUnitarioByImporte(data, event);
      }
      else{
        data.SubTotal(importe.toFixed(NUMERO_DECIMALES_VENTA));
      }

      ViewModels.data.NotaCredito.ActualizarTotales(data, event);

    }
  }

}
