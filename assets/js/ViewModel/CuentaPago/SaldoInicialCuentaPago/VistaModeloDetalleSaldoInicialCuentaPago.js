VistaModeloDetalleSaldoInicialCuentaPago = function (data, parent) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);
  self.parent = parent;

  self.DecimalPrecioUnitario = ko.observable(CANTIDAD_DECIMALES_VENTA.PRECIO_UNITARIO);
  self.DecimalCantidad = ko.observable(CANTIDAD_DECIMALES_VENTA.CANTIDAD);
  self.DecimalDescuentoUnitario = ko.observable(CANTIDAD_DECIMALES_VENTA.DESCUENTO_UNITARIO);
  self.UltimoItem = ko.observable(false);

  self.InicializarVistaModelo =  function (event) {
    if(event) {
      var data = {id: self.InputProducto(), TipoVenta : TIPO_VENTA.MERCADERIAS};
      $(data.id).autoCompletadoProducto(data, event, self.ValidarAutocompletadoProducto, ORIGEN_MERCADERIA.GENERALVENTA);
    }
  }

  self.CalcularSubTotal = function (data,event) {
    if (event) {
      var total = 0.00;
      var cantidad = parseFloatAvanzado(self.Cantidad());
      var precioUnitario = parseFloatAvanzado(self.PrecioUnitario());

      if (cantidad > 0 && precioUnitario > 0) {
        total = cantidad * precioUnitario;
      }

      self.SubTotal(total);
    }
  }

  self.InputCodigoMercaderia = ko.computed( function() {
    return self.IdDetalleSaldoInicialCuentaPago ? "#input_CodigoMercaderia_" + self.IdDetalleSaldoInicialCuentaPago() : "";
  },this);

  self.InputProducto = ko.computed( function() {
    return self.IdDetalleSaldoInicialCuentaPago ? "#input_NombreProducto_" + self.IdDetalleSaldoInicialCuentaPago() : "";
  },this);

  self.InputCantidad = ko.computed( function() {
    return self.IdDetalleSaldoInicialCuentaPago ? "#input_Cantidad_" + self.IdDetalleSaldoInicialCuentaPago() : "";
  },this);

  self.InputPrecioUnitario = ko.computed( function() {
    return self.IdDetalleSaldoInicialCuentaPago ? "#input_PrecioUnitario_" + self.IdDetalleSaldoInicialCuentaPago() : "";
  },this);

  self.InputSubTotal = ko.computed( function() {
    return self.IdDetalleSaldoInicialCuentaPago ? "#input_SubTotal_" + self.IdDetalleSaldoInicialCuentaPago() : "";
  },this);

  self.InputFila = ko.computed( function() {
    return self.IdDetalleSaldoInicialCuentaPago ? "#tr_Detalle_" + self.IdDetalleSaldoInicialCuentaPago() : "";
  },this);

  self.OnClickFila = function(data, event, callback) {
    if(event) {
      self.Seleccionar(data,event);
      callback(data,event,false);
    }
  }

  self.Seleccionar = function (data, event) {
    if(event) {
      var id = "#"+ data.IdDetalleSaldoInicialCuentaPago()+"_tr_detalle";
      if (!$(id).hasClass('enviado')) {
        $(id).addClass('active').siblings().removeClass('active');
      }
    }
  }


  self.OnClickBtnOpcion = function (data,event,callback) {
    if(event) {
      var tr = $(data.InputProducto()).closest("tr");
      var $trnext = tr.next();
      if($trnext.length > 0 ) {
        var btnOpcion =$trnext.find("button:visible");
        if(btnOpcion.length > 0) {//visible
          var $input =$trnext.find("input[id*=CodigoMercaderia]");
          setTimeout(function()  {
            $input.focus();
          },250);
        }
        else {
          var $trprev = tr.prev();
          if($trprev.length > 0) {
            var $input =$trprev.find("input[id*=CodigoMercaderia]");
            setTimeout(function()  {
              $input.focus();
            },250);
          }
        }
      }
      self.parent.OnQuitarFila(data, event);
    }
  }

  self.OnKeyEnterCodigoMercaderia = function(data,event) {
    if(event) {
      if(event.keyCode === TECLA_ENTER) {
        data.CodigoMercaderia($(event.target).val());

        self.ValidarProductoPorCodigo(data,event,function($data,$event,$valid){
          var $evento = { target : self.InputProducto() };
          self.procesado = true;
          self.ValidarNombreProducto(data,$evento);
          self.parent.base.OnKeyEnter(data, event)
          self.procesado = false;
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
      var TipoVenta = TIPO_VENTA.MERCADERIAS;
      var datajs = "";
      var codigo = "";
      var url_json = "";
      var _busqueda = "IdProducto";

      if (TipoVenta == TIPO_VENTA.MERCADERIAS) {
        datajs = { CodigoMercaderia : data.CodigoMercaderia(), IdGrupoProducto : TipoVenta };
        codigo = data.CodigoMercaderia();
        url_json = SERVER_URL + URL_JSON_MERCADERIAS;
        _busqueda = "CodigoMercaderia";
      }

      var opcionExtra = ' and IdOrigenMercaderia = "' + ORIGEN_MERCADERIA.GENERAL + '"';

      var $input = $(self.InputCodigoMercaderia());
      var json = ObtenerJSONCodificadoDesdeURL(url_json);

      codigo = codigo.toUpperCase();

      var queryBusqueda  = '//*['+_busqueda+'="'+codigo+'" '+ opcionExtra +']';

      var rpta = JSON.search(json, queryBusqueda);

      if (rpta.length > 0)  {
        var ruta_producto = SERVER_URL + URL_RUTA_PRODUCTOS + rpta[0].IdProducto + '.json';
        var producto = ObtenerJSONCodificadoDesdeURL(ruta_producto);
        if (producto.length > 0) {
          $input.attr("data-validation-found","true");
          $input.attr("data-validation-text-found",producto[0].NombreProducto);
          ko.mapping.fromJS(producto[0],  {}, self);

        }
      }
      else {
        var item = null;
        $input.attr("data-validation-found","false");
        $input.attr("data-validation-text-found","");
      }

      $(event.target).validate(function(valid, elem) {
        if ($callback) $callback(rpta[0],event,valid);
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
      if (data === -1) {
        if($(self.InputCodigoMercaderia()).attr("data-validation-text-found") ===  $(self.InputProducto()).val())  {
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

        if (self.TipoVenta() == TIPO_VENTA.MERCADERIAS && cabecera.ParametroGuardarProductoVenta() == 1) {
          self.GuardarProductoEnVenta(self,event);
        } else {
          self.FocusNextAutocompleteProducto(event);
        }
      }
      else {
        if($(self.InputCodigoMercaderia()).attr("data-validation-text-found") === $(self.InputProducto()).val())  {
          $(self.InputCodigoMercaderia()).attr("data-validation-found","true");
          var $evento = { target : self.InputProducto() };
          self.ValidarNombreProducto(data,$evento);
          self.FocusNextAutocompleteProducto(event);
        }
        else {

          $(self.InputCodigoMercaderia()).attr("data-validation-text-found",data.NombreProducto);
          $(self.InputCodigoMercaderia()).attr("data-validation-found","true");
          var $evento = { target : self.InputProducto() };
          self.ValidarNombreProducto(data,$evento);
          var $evento2 = { target : self.InputCodigoMercaderia() };
          var $data = Knockout.CopiarObjeto(data);

          self.ValidarProductoPorCodigo($data,$evento2,function($data3,$evento3){
          });
          self.FocusNextAutocompleteProducto(event);
        }
      }
    }
  }

  self.FocusNextAutocompleteProducto = function(event) {
    if (event) {
      var $inputProducto =  $(self.InputProducto());
      var pos = $inputProducto.closest("tr").find("input").not(':disabled').index($inputProducto);
      $inputProducto.closest("tr").find("input").not(':disabled').eq(pos+1).focus();
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

  self.ValidarSubTotal = function(data,event) {
    if(event) {
      if(data.SubTotal() === "") data.PrecioUnitario("0.00");
      $(event.target).validate(function(valid, elem) {
      });
    }
  }

  self.OnKeyEnterOpcion = function(data,event,$callbackParent) {
    if(event) {
      if(event.keyCode === TECLA_ENTER) {
        $callbackParent(data,event);
      }
      else if (event.keyCode === TECLA_TAB) {

        var ultimafila =$("tr:last").index();
        var fila = $(event.target).closest("tr").index();

        if (fila === ultimafila -1 ) {
          $("#DescuentoGlobal").focus();
          event.preventDefault();
          event.stopPropagation();
        }
      }
      return true;
    }
  }
  self.AgregarNuevoDetalle = function (data, event) {
    if (event) {
      if (self.UltimoItem()) {
        self.parent.NuevoDetalle(data, event);
      }
    }
  }

  self.OnFocusCodigoMercaderia = function (data, event) {
    if (event) {
      self.parent.base.OnFocus(data, event);
      self.AgregarNuevoDetalle(data, event);
    }
  }

  self.OnFocusNombreProducto = function (data, event) {
    if (event) {
      self.parent.base.OnFocus(data, event);
      self.AgregarNuevoDetalle(data, event);
    }
  }

  self.OnFocusBtnRemove = function (data, event) {
    if (event) {

    }
  }

  self.OnFocusPrecioUnitario = function(data, event) {
    if(event)  {
      self.parent.base.OnFocus(data, event);
      self.AgregarNuevoDetalle(data, event);
      self.CalcularSubTotal(data, event);

    }
  }

  self.OnFocusOutPrecioUnitario = function (data, event) {
    if (event) {
      self.ValidarPrecioUnitario(data, event);
      self.CalcularSubTotal(data, event);

    }
  }

  self.OnFocusCantidad = function (data,event, $parent) {
    if (event) {
      self.parent.base.OnFocus(data, event);
      self.AgregarNuevoDetalle(data, event);
      self.CalcularSubTotal(data, event);
    }
  }

  self.OnFocusOutCantidad = function (data, event) {
    if (event) {
      self.ValidarCantidad(data, event);

    }
  }


  self.OnFocusSubtotal = function (data, event ) {
    if (event) {
      self.parent.base.OnFocus(data, event);
      self.AgregarNuevoDetalle(data, event);

    }
  }

  self.OnFocusOutSubTotal = function (data, event ) {
    if (event) {
      self.ValidarSubTotal(data, event);

    }
  }

  self.OnChangeSubtotal = function (data, event ) {
    if (event) {

    }
  }

}
