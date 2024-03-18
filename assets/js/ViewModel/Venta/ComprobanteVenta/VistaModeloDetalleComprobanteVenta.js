VistaModeloDetalleComprobanteVenta = function (data, base) {
  var self = this;
  self.cabecera = base;
  ko.mapping.fromJS(data, MappingCatalogo, self);
  self.CodigoMercaderiaAnterior = ko.observable("");
  self.DecimalCantidad = ko.observable(CANTIDAD_DECIMALES_VENTA.CANTIDAD);
  self.DecimalPrecioUnitario = ko.observable(CANTIDAD_DECIMALES_VENTA.PRECIO_UNITARIO);
  self.DecimalPrecioUnitarioSolesDolares = ko.observable(CANTIDAD_DECIMALES_VENTA.PRECIO_UNITARIO);
  self.DecimalDescuentoUnitario = ko.observable(CANTIDAD_DECIMALES_VENTA.DESCUENTO_UNITARIO);
  self.DecimalDescuentoValorUnitario = ko.observable(CANTIDAD_DECIMALES_VENTA.DESCUENTO_VALOR_UNITARIO);

  self.NumeroItem = ko.observable(1);

  if (self.cabecera) {
    self.OptionsDetalle = ko.observable(self.cabecera.Options)
    var $form = $(self.OptionsDetalle().IDForm);
  }
  ModeloDetalleComprobanteVenta.call(this, self);

  self.InicializarVistaModelo = function (event) {
    if (event) {
      self.Producto.InicializarVistaModelo(data, event);
      var data = { id: self.InputProducto(), TipoVenta: self.cabecera.TipoVenta(), NombreLargoProducto: self.cabecera.ParametroRubroRepuesto() };

      if (base.IdTipoDocumento() == ID_TIPO_DOCUMENTO_FACTURA) {
        if (base.TipoVenta() == TIPO_VENTA.MERCADERIAS) {
          $form.find(self.InputProducto()).autoCompletadoProducto(data, event, self.ValidarAutocompletadoProducto, ORIGEN_MERCADERIA.GENERALVENTA);
        }
        else {
          $form.find(self.InputProducto()).autoCompletadoProducto(data, event, self.ValidarAutocompletadoProducto, ORIGEN_MERCADERIA.TODOS);
        }
      }
      else if (base.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA) {
        if (base.TipoVenta() == TIPO_VENTA.MERCADERIAS) {
          if ((base.IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_T || base.IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_Z)) {
            $form.find(self.InputProducto()).autoCompletadoProducto(data, event, self.ValidarAutocompletadoProducto, ORIGEN_MERCADERIA.ZOFRA);
          }
          else {
            $form.find(self.InputProducto()).autoCompletadoProducto(data, event, self.ValidarAutocompletadoProducto, ORIGEN_MERCADERIA.GENERALVENTA);
          }
        }
        else {
          $form.find(self.InputProducto()).autoCompletadoProducto(data, event, self.ValidarAutocompletadoProducto, ORIGEN_MERCADERIA.TODOS);
        }
      }
      else if (base.IdTipoDocumento() == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO) {
        if (base.TipoVenta() == TIPO_VENTA.MERCADERIAS) {
          if (base.ParametroOrdenPedidoDua() == 1) {
            $form.find(self.InputProducto()).autoCompletadoProducto(data, event, self.ValidarAutocompletadoProducto, ORIGEN_MERCADERIA.GENERALVENTA);
          } else {
            $form.find(self.InputProducto()).autoCompletadoProducto(data, event, self.ValidarAutocompletadoProducto, ORIGEN_MERCADERIA.GENERAL);
          }
        }
        else {
          $form.find(self.InputProducto()).autoCompletadoProducto(data, event, self.ValidarAutocompletadoProducto, ORIGEN_MERCADERIA.TODOS);
        }
      } else {
        $form.find(self.InputProducto()).autoCompletadoProducto(data, event, self.ValidarAutocompletadoProducto, ORIGEN_MERCADERIA.TODOS);
      }

      self.InicializarModelo(event);

      $(self.InputProducto()).on("focusout", function (event) {
        self.ValidarNombreProducto(undefined, event);
      });
    }
  }

  self.CalculoValorUnitario = function (data, event, callback) {
    if (event) {
      callback(data, event);
    }
  }

  self.ColorText = ko.computed(function () {
    var stock = parseFloatAvanzado(self.StockProducto());
    return parseFloatAvanzado(stock) < 0 ? 'text-danger' : "text-secondary"
  }, this);

  self.InputCodigoMercaderia = ko.computed(function () {
    if (self.IdDetalleComprobanteVenta != undefined) {
      return "#" + self.IdDetalleComprobanteVenta() + "_input_CodigoMercaderia";
    }
    else {
      return "";
    }
  }, this);

  self.InputProducto = ko.computed(function () {
    if (self.IdDetalleComprobanteVenta != undefined) {
      return "#" + self.IdDetalleComprobanteVenta() + "_input_NombreProducto";
    }
    else {
      return "";
    }
  }, this);

  self.InputDescuentoItem = ko.computed(function () {
    if (self.IdDetalleComprobanteVenta != undefined) {
      return "#" + self.IdDetalleComprobanteVenta() + "_span_DescuentoItem";
    }
    else {
      return "";
    }
  }, this);

  self.InputCantidad = ko.computed(function () {
    if (self.IdDetalleComprobanteVenta != undefined) {
      return "#" + self.IdDetalleComprobanteVenta() + "_input_Cantidad";
    }
    else {
      return "";
    }
  }, this);

  self.InputNumeroLote = ko.computed(function () {
    if (self.IdDetalleComprobanteVenta != undefined) {
      return "#" + self.IdDetalleComprobanteVenta() + "_input_NumeroLote";
    }
    else {
      return "";
    }
  }, this);

  self.InputNumeroDocumentoSalidaZofra = ko.computed(function () {
    if (self.IdDetalleComprobanteVenta != undefined) {
      return "#" + self.IdDetalleComprobanteVenta() + "_input_NumeroDocumentoSalidaZofra";
    }
    else {
      return "";
    }
  }, this);

  self.InputNumeroDua = ko.computed(function () {
    if (self.IdDetalleComprobanteVenta != undefined) {
      return "#" + self.IdDetalleComprobanteVenta() + "_input_NumeroDua";
    }
    else {
      return "";
    }
  }, this);

  self.InputPrecioUnitario = ko.computed(function () {
    if (self.IdDetalleComprobanteVenta != undefined) {
      return "#" + self.IdDetalleComprobanteVenta() + "_input_PrecioUnitario";
    }
    else {
      return "";
    }
  }, this);

  self.InputPrecioUnitarioSolesDolares = ko.computed(function () {
    if (self.IdDetalleComprobanteVenta != undefined) {
      return "#" + self.IdDetalleComprobanteVenta() + "_input_PrecioUnitarioSolesDolares";
    }
    else {
      return "";
    }
  }, this);

  self.InputValorUnitario = ko.computed(function () {
    if (self.IdDetalleComprobanteVenta != undefined) {
      return "#" + self.IdDetalleComprobanteVenta() + "_input_ValorUnitario";
    }
    else {
      return "";
    }
  }, this);

  self.InputCantidadCaja = ko.computed(function () {
    if (self.IdDetalleComprobanteVenta != undefined) {
      return "#" + self.IdDetalleComprobanteVenta() + "_input_CantidadCaja";
    }
    else {
      return "";
    }
  }, this);

  self.InputDescuentoUnitario = ko.computed(function () {
    if (self.IdDetalleComprobanteVenta != undefined) {
      return "#" + self.IdDetalleComprobanteVenta() + "_input_DescuentoUnitario";
    }
    else {
      return "";
    }
  }, this);

  self.InputDescuentoValorUnitario = ko.computed(function () {
    if (self.IdDetalleComprobanteVenta != undefined) {
      return "#" + self.IdDetalleComprobanteVenta() + "_input_DescuentoValorUnitario";
    }
    else {
      return "";
    }
  }, this);

  self.InputSubTotal = ko.computed(function () {
    if (self.IdDetalleComprobanteVenta != undefined) {
      return "#" + self.IdDetalleComprobanteVenta() + "_span_SubTotal";
    }
    else {
      return "";
    }
  }, this);

  self.InputValorVentaItem = ko.computed(function () {
    if (self.IdDetalleComprobanteVenta != undefined) {
      return "#" + self.IdDetalleComprobanteVenta() + "_span_ValorVentaItem";
    }
    else {
      return "";
    }
  }, this);

  self.InputOpcion = ko.computed(function () {
    if (self.IdDetalleComprobanteVenta != undefined) {
      return "#" + self.IdDetalleComprobanteVenta() + "_a_opcion";
    }
    else {
      return "";
    }
  }, this);

  self.InputOpcionMercaderia = ko.computed(function () {
    if (self.IdDetalleComprobanteVenta != undefined) {
      return "#" + self.IdDetalleComprobanteVenta() + "_a_opcion_mercaderia";
    }
    else {
      return "";
    }
  }, this);

  self.InputStockProducto = ko.computed(function () {
    if (self.IdDetalleComprobanteVenta != undefined) {
      return "#" + self.IdDetalleComprobanteVenta() + "_span_StockProducto";
    }
    else {
      return "";
    }
  }, this);

  self.InputFila = ko.computed(function () {
    if (self.IdDetalleComprobanteVenta != undefined) {
      return "#" + self.IdDetalleComprobanteVenta() + "_tr_detalle";
    }
    else {
      return "";
    }
  }, this);

  self.InputObservacion = ko.computed(function () {
    if (self.IdDetalleComprobanteVenta != undefined) {
      return "#" + self.IdDetalleComprobanteVenta() + "_input_Observacion";
    }
    else {
      return "";
    }
  }, this);


  self.OnEnableDua = ko.computed(function () {
    if (self.hasOwnProperty('IdOrigenMercaderia')) {
      if (self.IdOrigenMercaderia() == ORIGEN_MERCADERIA.GENERAL) {
        $(self.InputFila()).resetearValidaciones();
        return false;
      }
      else {
        return true;
      }
    }
    else {
      return true;
    }
  }, this);


  self.OnClickFila = function (data, event, callback) {
    if (event) {
      self.Seleccionar(data, event);
      callback(data, event, false);
    }
  }

  self.Seleccionar = function (data, event) {
    if (event) {
      var id = "#" + data.IdDetalleComprobanteVenta() + "_tr_detalle";
      if (!$(id).hasClass('enviado')) {
        $(id).addClass('active').siblings().removeClass('active');
      }
    }
  }

  self.OnFocus = function (data, event, callback) {
    if (event) {
      $(event.target).select();
      self.Seleccionar(data, event);
      callback(data, event, false);
    }

    return true;
  }

  self.OnFocusPrecioUnitario = function (data, event, callback) {
    if (event) {
      $(event.target).select();
      self.Seleccionar(data, event);
      callback(data, event, false);
      //$(event.target).click();
    }

    return true;
  }

  self.OnFocusPrecioUnitarioSolesDolares = function (data, event, callback) {
    if (event) {
      $(event.target).select();
      self.Seleccionar(data, event);
      callback(data, event, false);
    }

    return true;
  }

  self.OnFocusValorUnitario = function (data, event, callback) {
    if (event) {
      $(event.target).select();
      self.Seleccionar(data, event);
      callback(data, event, false);
    }

    return true;
  }

  self.OnChangeText = function (data, event, parent) {
    if (event) {
      // $(event.target).select();
      // alert(event);
      event.preventDefault();
      event.stopPropagation();
      // console.log(data.Cantidad());
      var texto = $(event.target).val();
      // console.log(texto);
      if (self.cabecera.ParametroCodigoBarras() == 0) { return false };

      if (texto.length >= CANTIDAD_DIGITOS_BARCODE) {
        data.CodigoMercaderia(texto);
        if (data.NombreProducto() == "" && (data.Cantidad() == "" || data.Cantidad() == 0.00)) {
          // data.CodigoMercaderiaAnterior(texto);
          var resultado = ko.utils.arrayFirst(parent.DetallesComprobanteVenta(), function (item) {
            return data.CodigoMercaderia() == item.CodigoMercaderiaAnterior();
          });
          if (resultado) {
            var cantidad = parseFloatAvanzado(resultado.Cantidad()) + 1;
            resultado.Cantidad(cantidad.toFixed(2).toString());

            parent.CalcularTotales(event);
          }
          else {
            self.OnInputBarCodeLector(data, event, function ($data, $event) {
              console.log($data);
              if (!('first' in $data)) {
                data.CodigoMercaderiaAnterior(texto);
                data.Cantidad('1.00');
                parent.CalcularTotales(event);
              }
              $(event.target).select();
            });
          }

          $(event.target).select();
        }
        else {
          var resultado = ko.utils.arrayFirst(parent.DetallesComprobanteVenta(), function (item) {
            return data.CodigoMercaderia() == item.CodigoMercaderiaAnterior();
          });
          // data.CodigoMercaderia(texto);
          // var resultado = self.BuscarCodigoDetalle(data, event);
          if (resultado) {
            console.log("Producto igual");
            if (data.NombreProducto() != "") {
              console.log(data.NombreProducto());
              var cantidad = parseFloatAvanzado(resultado.Cantidad()) + 1;
              resultado.Cantidad(cantidad.toFixed(2).toString());
              data.CodigoMercaderia(data.CodigoMercaderiaAnterior());
              parent.CalcularTotales(event);
            }
            $(event.target).select();
          }
          else {
            console.log("Producto difrerente");
            data.CodigoMercaderia(data.CodigoMercaderiaAnterior());
            var last_input = $(self.InputCodigoMercaderia()).closest('table').find("tr:last").find("input:first");
            $(last_input).click();
            setTimeout(function () {
              // var last_input = $(last_input).closest("tr");
              $(last_input).focus();
              console.log($(last_input));
            }, 300);
            // $(self.InputCodigoMercaderia()).closest('table').find("tr:last").find("input:first").focus();

            $(event.target).select();
          }


        }

      }
    }


    return true;
  }

  self.OnInputBarCodeLector = function (data, event, callback) {
    if (event) {
      data.CodigoMercaderia($(event.target).val());

      if (self.TipoVenta() == TIPO_VENTA.SERVICIOS) data.CodigoServicio(data.CodigoMercaderia());
      else if (self.TipoVenta() == TIPO_VENTA.ACTIVOS) data.CodigoActivoFijo(data.CodigoMercaderia());
      else if (self.TipoVenta() == TIPO_VENTA.OTRASVENTAS) data.CodigoOtraVenta(data.CodigoMercaderia());

      self.ValidarProductoPorCodigo(data, event, function ($data, $event, $valid) {
        var $evento = { target: self.InputProducto() };
        self.procesado = true;
        self.ValidarNombreProducto(data, $evento);
        callback($data, $event);
        self.procesado = false;
      });

    }
  }

  self.OnKeyEnter = function (data, event, $callbackParent) {
    if (event) {
      $callbackParent(data, event);
    }
    return true;
  }

  self.OnClickBtnOpcion = function (data, event, callback) {
    if (event) {
      var tr = $(data.InputProducto()).closest("tr");
      var $trnext = tr.next();
      if ($trnext.length > 0) {
        var btnOpcion = $trnext.find("button:visible");
        if (btnOpcion.length > 0) {//visible
          var $input = $trnext.find("input[id*=CodigoMercaderia]");
          setTimeout(function () {
            $input.focus();
          }, 250);
        }
        else {
          var $trprev = tr.prev();
          if ($trprev.length > 0) {
            var $input = $trprev.find("input[id*=CodigoMercaderia]");
            setTimeout(function () {
              $input.focus();
            }, 250);
          }
        }
      }

      callback(data, event);
    }
  }

  self.procesado = false;

  self.OnKeyEnterCodigoMercaderia = function (data, event, $callbackParent) {
    if (event) {
      if (event.keyCode === TECLA_ENTER) {

        data.CodigoMercaderia($(event.target).val());

        if (self.TipoVenta() == TIPO_VENTA.SERVICIOS) data.CodigoServicio(data.CodigoMercaderia());
        else if (self.TipoVenta() == TIPO_VENTA.ACTIVOS) data.CodigoActivoFijo(data.CodigoMercaderia());
        else if (self.TipoVenta() == TIPO_VENTA.OTRASVENTAS) data.CodigoOtraVenta(data.CodigoMercaderia());

        self.ValidarProductoPorCodigo(data, event, function ($data, $event, $valid) {
          var $evento = { target: self.InputProducto() };
          self.procesado = true;
          self.ValidarNombreProducto(data, $evento);
          $callbackParent($data, $event);
          self.procesado = false;
        });
      }
      self.CalculoSubTotal(data, event);

      return true;
    }
  }

  self.ValidarCodigoMercaderia = function (data, event) {
    if (event) {//focusout
      if (!self.procesado) {

        self.ValidarProductoPorCodigo(data, event, function ($data, $event, $valid) {
          var $evento = { target: self.InputProducto() };
          self.ValidarNombreProducto(data, $evento);
          $(event.relatedTarget).select();
        });
      }
    }
  }

  self.ValidarProductoPorCodigo = function (data, event, $callback) {
    if (event) {
      var TipoVenta = self.TipoVenta();
      var datajs = "";
      var codigo = "";
      var url_json = "";
      var _busqueda = "IdProducto";
      if (TipoVenta == TIPO_VENTA.MERCADERIAS) {
        datajs = { CodigoMercaderia: data.CodigoMercaderia(), IdGrupoProducto: TipoVenta };
        codigo = data.CodigoMercaderia();
        url_json = SERVER_URL + URL_JSON_MERCADERIAS;
        _busqueda = "CodigoMercaderia";
      }
      else if (TipoVenta == TIPO_VENTA.SERVICIOS) {
        datajs = { CodigoServicio: data.CodigoServicio(), IdGrupoProducto: TipoVenta };
        codigo = data.CodigoServicio();
        url_json = SERVER_URL + URL_JSON_SERVICIOS;
        _busqueda = "CodigoServicio";
      }
      else if (TipoVenta == TIPO_VENTA.ACTIVOS) {
        datajs = { CodigoActivoFijo: data.CodigoActivoFijo(), IdGrupoProducto: TipoVenta };
        codigo = data.CodigoActivoFijo();
        url_json = SERVER_URL + URL_JSON_ACTIVOSFIJOS;
        _busqueda = "CodigoActivoFijo";
      }
      else if (TipoVenta == TIPO_VENTA.OTRASVENTAS) {
        datajs = { IdProducto: data.CodigoOtraVenta(), IdGrupoProducto: TipoVenta };
        codigo = data.CodigoOtraVenta();
        url_json = SERVER_URL + URL_JSON_OTRASVENTAS;
        _busqueda = "CodigoOtraVenta";
      }

      var $input = $(self.InputCodigoMercaderia());
      var opcionExtra = "";

      if (base.IdTipoDocumento() == ID_TIPO_DOCUMENTO_FACTURA) {
        opcionExtra = ' and (IdOrigenMercaderia = "' + ORIGEN_MERCADERIA.GENERAL + '"' + ' or IdOrigenMercaderia = "' + ORIGEN_MERCADERIA.DUA + '")';
      }
      else if (base.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA) {
        if (base.IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_T || base.IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_Z) {
          opcionExtra = ' and IdOrigenMercaderia = "' + ORIGEN_MERCADERIA.ZOFRA + '"';
        }
        else {
          opcionExtra = ' and (IdOrigenMercaderia = "' + ORIGEN_MERCADERIA.GENERAL + '"' + ' or IdOrigenMercaderia = "' + ORIGEN_MERCADERIA.DUA + '")';
        }
      }
      else if (base.IdTipoDocumento() == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO) {
        if (base.ParametroOrdenPedidoDua() == 1) {
          opcionExtra = ' and (IdOrigenMercaderia = "' + ORIGEN_MERCADERIA.GENERAL + '"' + ' or IdOrigenMercaderia = "' + ORIGEN_MERCADERIA.DUA + '")';
        } else {
          opcionExtra = ' and IdOrigenMercaderia = "' + ORIGEN_MERCADERIA.GENERAL + '"';
        }
      }

      var json = ObtenerJSONCodificadoDesdeURL(url_json);
      codigo = codigo.toUpperCase();
      var queryBusqueda = "";
      if (TipoVenta == TIPO_VENTA.MERCADERIAS) {
        queryBusqueda = '//*[' + _busqueda + '="' + codigo + '" ' + opcionExtra + ']';
      } else {
        queryBusqueda = '//*[' + _busqueda + '="' + codigo + '"]';
      }

      var rpta = JSON.search(json, queryBusqueda);

      if (rpta.length > 0 && rpta[0].EstadoProducto == 0) {
        rpta = [];
      }

      if (rpta.length > 0) {
        var ruta_producto = SERVER_URL + URL_RUTA_PRODUCTOS + rpta[0].IdProducto + '.json';
        var producto = ObtenerJSONCodificadoDesdeURL(ruta_producto);
        var queryBusqueda = "";

        if (self.cabecera.TipoVenta() == TIPO_VENTA.MERCADERIAS ) {
          var precios = producto[0].ListaPrecios.filter(item => item.IndicadorPrecioVentaPorDefecto == 1);
          if (precios.length > 0) {
            producto[0].PrecioUnitario = precios[0].Precio;
          }
        }


        self.cabecera.CopiaIdProductosDetalle.push(producto[0].IdProducto);

        var stock = self.ObtenerStockProducto(producto[0], event);

        producto[0].StockProducto = stock ? stock.StockMercaderia : "0.00";
        //producto[0].IdSede = self.IdSede();

        var descripcionProducto = self.cabecera.ParametroRubroRepuesto() == 1 && self.TipoVenta() == TIPO_VENTA.MERCADERIAS ? producto[0].NombreLargoProducto : producto[0].NombreProducto;

        $input.attr("data-validation-found", "true");
        $input.attr("data-validation-text-found", descripcionProducto);

        var nombresede = $form.find("#combo-almacen option:selected").text();
        ko.utils.arrayForEach(self.cabecera.Sedes(), function (item) {
          if (item.NombreSede() == nombresede) {
            self.IdSede(item.IdSede());
          }
        });

        if (self.cabecera.IndicadorPermisoStockNegativo() == 0 && parseFloatAvanzado(producto[0].StockProducto) <= 0) {
          alertify.alert("Comprobante Venta", "Este producto no cuenta con Stock para proceder con la Venta..", function () { });
          $input.attr("data-validation-found", "false");
          $input.attr("data-validation-text-found", "");
          return false;
        }

        var item = self.Reemplazar(producto[0]);
      }
      else {
        var item = null;
        $input.attr("data-validation-found", "false");
        $input.attr("data-validation-text-found", "");
      }

      $(event.target).validate(function (valid, elem) {
        if ($callback) $callback(rpta[0], event, valid);
      });

    }
  }

  self.ObtenerStockProducto = function (data, event) {
    if (event) {
      if (self.cabecera) {
        var filtro = `//*[IdAsignacionSede="${self.cabecera.IdAsignacionSede()}" and IdProducto="${data.IdProducto}"]`;
        var resultado = data.ListaStock ? JSON.search(data.ListaStock, filtro) : "";
        return resultado.length > 0 ? resultado[0] : false;
      } else {
        return false
      }
    }
  }

  self.ValidarNombreProducto = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
      });
    }
  }
  self.ValidarObservacion = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
      });
    }
  }

  self.ValidarAutocompletadoProducto = function (data, event) {
    if (event) {
      if (data === -1) {
        if ($(self.InputCodigoMercaderia()).attr("data-validation-text-found") === $(self.InputProducto()).val()) {
          $(self.InputCodigoMercaderia()).attr("data-validation-found", "true");
          var $evento = { target: self.InputProducto() };
          self.ValidarNombreProducto(data, $evento);
        }
        else {
          $(self.InputCodigoMercaderia()).attr("data-validation-text-found", "");
          $(self.InputCodigoMercaderia()).attr("data-validation-found", "false");
          var $evento = { target: self.InputProducto() };
          self.ValidarNombreProducto(data, $evento);
        }

        if (self.cabecera.ParametroGuardarProductoVenta() == 1) {
          if (self.TipoVenta() == TIPO_VENTA.MERCADERIAS) {
            self.GuardarProductoEnVenta(self, event);
          }
          else if (self.TipoVenta() == TIPO_VENTA.SERVICIOS) {
            self.GuardarProductoServicioEnVenta(self, event);
          }
          else {
            self.FocusNextAutocompleteProducto(event);
          }
        } else {
          self.FocusNextAutocompleteProducto(event);
        }
      }
      else {
        if ($(self.InputCodigoMercaderia()).attr("data-validation-text-found") === $(self.InputProducto()).val() && $(self.InputProducto()).val() != "") {
          $(self.InputCodigoMercaderia()).attr("data-validation-found", "true");
          var $evento = { target: self.InputProducto() };
          self.ValidarNombreProducto(data, $evento);
          self.FocusNextAutocompleteProducto(event);
        }
        else {
          var descripcionProducto = self.cabecera.ParametroRubroRepuesto() == 1 && self.TipoVenta() == TIPO_VENTA.MERCADERIAS ? data.NombreLargoProducto : data.NombreProducto;

          $(self.InputCodigoMercaderia()).attr("data-validation-text-found", descripcionProducto);
          $(self.InputCodigoMercaderia()).attr("data-validation-found", "true");
          var $evento = { target: self.InputProducto() };
          self.ValidarNombreProducto(data, $evento);
          var $evento2 = { target: self.InputCodigoMercaderia() };
          var $data = Knockout.CopiarObjeto(data);

          var idProducto = self.IdProducto();
          if (idProducto != "" && idProducto != null && self.ProductoAnterior().IdProducto) {
            if (data.IdProducto != self.ProductoAnterior().IdProducto()) {
              self.cabecera.OnQuitarProductoBonificado(self.ProductoAnterior(), window)
            }
          }

          self.ValidarProductoPorCodigo($data, $evento2, function () {
            self.ProductoAnterior(Knockout.CopiarObjeto(self));
          });

          self.FocusNextAutocompleteProducto(event);
          self.OnChangeCantidad(data, event, self.cabecera)
        }
      }
    }
  }

  self.FocusNextAutocompleteProducto = function (event) {
    if (event) {
      var $inputProducto = $(self.InputProducto());
      var pos = $inputProducto.closest("tr").find("input").not(':disabled').index($inputProducto);
      $inputProducto.closest("tr").find("input").not(':disabled').eq(pos + 1).focus();
    }
  }

  self.ValidarCantidad = function (data, event) {
    if (event) {
      var alerta = "";
      if ($(self.InputNumeroLote()).is(":visible")) {
        var datalotes = ko.mapping.toJS(self._DataLotes());
        datalotes.forEach(function (entry, key) {
          if (entry.IdLoteProducto == data.IdLoteProducto()) {
            if (parseFloatAvanzado(data.Cantidad()) > parseFloatAvanzado(entry.StockProductoLote)) {
              alerta += '- La cantidad debe ser menor o igual al stock del Número de Lote seleccionado. </br> ';
            }
          }
        });
      }
      if ($(self.InputNumeroDocumentoSalidaZofra()).is(":visible")) {
        var datalistazofra = ko.mapping.toJS(self._DataListaZofra());
        datalistazofra.forEach(function (entry, key) {
          if (entry.IdDocumentoSalidaZofraProducto == data.IdDocumentoSalidaZofraProducto()) {
            if (parseFloatAvanzado(data.Cantidad()) > parseFloatAvanzado(entry.StockDocumentoSalidaZofra)) {
              alerta += '- La cantidad debe ser menor o igual a ' + entry.StockDocumentoSalidaZofra + ' de stock, del Documento de Zofra ' + entry.NumeroDocumentoSalidaZofra + ' seleccionado. </br> '
            }
          }
        });
      }
      if ($(self.InputNumeroDua()).is(":visible")) {
        var datalistadua = ko.mapping.toJS(self._DataListaDua());
        datalistadua.forEach(function (entry, key) {
          if (entry.IdDuaProducto == data.IdDuaProducto()) {
            if (parseFloatAvanzado(data.Cantidad()) > parseFloatAvanzado(entry.StockDua)) {

              alerta += '- La cantidad debe ser menor o igual a ' + entry.StockDua + ' de stock del Documento de DUA ' + entry.NumeroItemDua + '-' + entry.NumeroDua + ' seleccionado. </br> '
            }
          }
        });
      }
      if (self.ParametroVentaStockNegativo() == 0) {
        //if (self.TipoVenta() != TIPO_VENTA.SERVICIOS) {                  
        if (parseFloatAvanzado(data.Cantidad()) > parseFloatAvanzado(data.StockProducto())) {
          alerta += '- La cantidad debe ser menor o igual al stock del producto seleccionado. </br> '
        }
        //}
      }

      if (alerta != "") {
        alertify.alert('AVISO', alerta, function () {
          data.Cantidad("0.00");
          $(self.InputCantidad()).focus()
        })
      }

      data.Cantidad($(self.InputCantidad()).val());
      if (data.Cantidad() === "") data.Cantidad("0.00");
      $(event.target).validate(function (valid, elem) {
      });
      self.ValidarRaleoCantidad(data, event);
    }
  }

  self.ValidarNumeroLote = function (data, event) {
    if (event) {
      $(event.target).attr("data-validation-found", "false");

      var datalotes = ko.mapping.toJS(self._DataLotes());
      datalotes.forEach(function (entry, key) {
        if (entry.NumeroLote == data.NumeroLote()) {
          $(event.target).attr("data-validation-found", "true");
        }
      });

      $(event.target).validate(function (valid, elem) {
      });
    }
  }

  self.ValidarNumeroDocumentoSalidaZofra = function (data, event) {
    if (event) {
      $(event.target).attr("data-validation-found", "false");
      var datalistazofra = ko.mapping.toJS(self._DataListaZofra());
      datalistazofra.forEach(function (entry, key) {
        if ((entry.NumeroDocumentoSalidaZofra == data.NumeroDocumentoSalidaZofra())) {
          $(event.target).attr("data-validation-found", "true");
        }
      });

      $(event.target).validate(function (valid, elem) {
      });
    }
  }

  self.ValidarNumeroDua = function (data, event) {
    if (event) {
      $(event.target).attr("data-validation-found", "false");
      var datalistadua = ko.mapping.toJS(self._DataListaDua());
      datalistadua.forEach(function (entry, key) {
        if ((entry.NumeroDua == data.NumeroDua())) {
          $(event.target).attr("data-validation-found", "true");
        }
      });

      $(event.target).validate(function (valid, elem) {
      });
    }
  }

  self.ValidarRaleoCantidad = function (data, event) {
    if (event) {
      var raleo = ko.mapping.toJS(self.DataRaleo());
      if (raleo.length > 0) {
        var fila = null;
        raleo.forEach(function (entry, key) {
          if (parseFloatAvanzado(entry.NombreTipoListaRaleo) == parseFloatAvanzado(data.Cantidad())) {
            fila = entry;
          }
        });
        if (fila != null) {
          data.PrecioUnitario(fila.Precio);
        }
      }

    }
  }

  self.ValidarPrecioUnitario = function (data, event) {
    if (event) {
      if (data.PrecioUnitario() === "") data.PrecioUnitario("0.00");
      $(event.target).validate(function (valid, elem) {
      });
      
      self.CalcularPrecioSolesDolaresPorPrecioUnitario();
    }
  }

  self.ValidarPrecioUnitarioSolesDolares = function (data, event) {
    if (event) {
      if (data.PrecioUnitarioSolesDolares() === "") data.PrecioUnitarioSolesDolares("0.00");
      $(event.target).validate(function (valid, elem) {
      });

      self.CalcularPrecioUnitarioPorPrecioSolesDolares();
    }
  }

  self.ValidarValorUnitario = function (data, event) {
    if (event) {
      if (data.ValorUnitario() === "") data.ValorUnitario("0.00");
      $(event.target).validate(function (valid, elem) {
      });
    }
  }

  self.ValidarDescuentoUnitario = function (data, event) {
    if (event) {
      if (data.DescuentoUnitario() === "") data.DescuentoUnitario("0.00");
      $(event.target).validate(function (valid, elem) {
      });
    }
  }

  self.ValidarDescuentoValorUnitario = function (data, event) {
    if (event) {
      if (data.DescuentoValorUnitario() === "") data.DescuentoValorUnitario("0.00");
      $(event.target).validate(function (valid, elem) {
      });
    }
  }

  self.ValidarSubTotal = function (data, event) {
    if (event) {
      if (data.SubTotal() === "") data.PrecioUnitario("0.00");
      $(event.target).validate(function (valid, elem) {
      });
    }
  }

  self.ValidarValorVentaItem = function (data, event) {
    if (event) {
      if (data.ValorVentaItem() === "") data.ValorUnitario("0.00");
      $(event.target).validate(function (valid, elem) {
      });
    }
  }

  self.OnKeyEnterOpcion = function (data, event, $callbackParent) {
    if (event) {
      if (event.keyCode === TECLA_ENTER) {
        $callbackParent(data, event);
      }
      else if (event.keyCode === TECLA_TAB) {

        var ultimafila = $("tr:last").index();
        var fila = $(event.target).closest("tr").index();

        if (fila === ultimafila - 1) {
          $("#DescuentoGlobal").focus();
          event.preventDefault();
          event.stopPropagation();
        }
      }

      return true;
    }
  }

  self.GuardarProductoEnVenta = function (data, event) {
    if (event) {
      alertify.confirm("AVISO", "Este producto no se encuentra registrado en el sistema, ¿Desea regristrarlo ahora?", function () {
        self.GuardarProducto(data, event);
      }, function () {

      })
    }
  }

  self.GuardarProductoServicioEnVenta = function (data, event) {
    if (event) {
      if (self.cabecera.ParametroTransporteMercancia() == 1) {//Temporal
        self.GuardarProductoServicio(data, event);
      }
      else {
        alertify.confirm("AVISO", "Este servicio no se encuentra registrado en el sistema, ¿Desea regristrarlo ahora?", function () {
          self.GuardarProductoServicio(data, event);
        }, function () {

        })
      }

    }
  }

  self.OnFocusOutCantidad = function (data, event, $parent) {
    if (event) {
      self.CalcularSubTotal(data, event);
      if ($parent) {
        $parent.CalcularTotales(event);
        $parent.CantidadMontoRecibido(data, event);
        //$parent.CalcularVuelto(data, event);
      }
    }
  }

  self.OnFocusCantidad = function (data, event, $parent) {
    if (event) {
      $form.find("#input-teclado-virtual").val($(event.target).attr("name"));
      if ($parent) {
        $parent.CalcularTotales(event);
        $parent.CantidadMontoRecibido(data, event);
        //$parent.CalcularVuelto(data, event);
      }
    }
  }

  self.OnchangePorcentajeDescuento = function (data, event, $parent) {
    if (event) {
      $form.find("#input-teclado-virtual").val($(event.target).attr("name"));
      self.CalcularDescuentoUnitarioPorPorcentaje(data, event);
      self.CalculoSubTotal(data, event)
      if ($parent) {
        $parent.CalcularTotales(event);
        $parent.CantidadMontoRecibido(data, event);
        //$parent.CalcularVuelto(data, event);
      }
    }
  }

  self.OnFocusSubtotal = function (data, event, onRefrescar) {
    if (event) {
      if (self.cabecera.ParametroFormaCalculoVenta() == 0) {
        self.CalculoSubTotal(data, event);
      }

      if (onRefrescar) { onRefrescar(data, event) }
      $(event.target).select();
    }
  }

  self.OnFocusValorVentaItem = function (data, event, onRefrescar) {
    if (event) {
      self.CalculoSubTotal(data, event);
      if (onRefrescar) { onRefrescar(data, event) }
      $(event.target).select();
    }
  }


  self.CalculoSubTotal = function (data, event, callback) {
    if (event) {
      self.CalcularSubTotal(data, event);
      if (callback) {
        if (callback.OnRefrescar) {
          callback.OnRefrescar(data, event);
        }
        else {
          callback(data, event);
        }
      }
    }
  }

  self.CalculoPrecioUnitario = function (data, event, callback) {
    if (event) {
      self.CalcularPrecioUnitario(data, event);
      if (callback) {
        if (callback.OnRefrescar) {
          callback.OnRefrescar(data, event);
        }
        else {
          callback(data, event);
        }
      }
    }
  }

  self.CalculoCantidad = function (data, event, callback) {
    if (event) {
      self.CalcularCantidad(data, event);
      if (callback) {
        if (callback.OnRefrescar) {
          callback.OnRefrescar(data, event);
        }
        else {
          callback(data, event);
        }
      }
    }
  }

  self.FormaDeCalculoUno = function (data, event, parent, forma = undefined) {
    if (event) {
      switch (forma) {
        case 'Cantidad':
          self.CalculoSubTotal(data, event, parent);
          break;
        case 'PrecioUnitario':
          self.CalculoSubTotal(data, event, parent);
          break;
        case 'SubTotal':
          self.CalculoCantidad(data, event, parent);
          break;
        default:
          break;
      }
    }
  }

  self.FormaDeCalculoDos = function (data, event, parent, forma = undefined) {
    if (event) {
      switch (forma) {
        case 'Cantidad':
          self.CalculoPrecioUnitario(data, event, parent);
          break;
        case 'SubTotal':
          self.CalculoCantidad(data, event, parent);
          break;
        case 'PrecioUnitario':
          self.CalculoCantidad(data, event, parent);
          break;
        default:
          break;
      }
    }
  }

  self.FormaDeCalculoTres = function (data, event, parent, forma = undefined) {
    if (event) {
      switch (forma) {
        case 'Cantidad':
          self.CalculoSubTotal(data, event, parent);
          break;
        case 'SubTotal':
          self.CalculoPrecioUnitario(data, event, parent);
          break;
        case 'PrecioUnitario':
          self.CalculoSubTotal(data, event, parent);
          break;
        default:
          break;
      }
    }
  }

  self.OnChangeCantidad = function (data, event, parent) {
    if (event) {
      var bonificaciones = (self.ListaBonificaciones == undefined) ? [] : ko.mapping.toJS(self.ListaBonificaciones());
      var cantidad = parseFloatAvanzado(self.Cantidad())
      if (parent.ParametroBonificacion() == 1 && bonificaciones.length > 0 && cantidad > 0 && self.AfectoBonificacion() == 1 && self.ProductoBonificado() == false) {
        bonificaciones.forEach(function (item, key) {
          var cantidadItem = parseFloatAvanzado(item.Cantidad);
          if (cantidad >= cantidadItem) {
            var nuevaCantidad = Math.trunc(cantidad / cantidadItem) * parseFloatAvanzado(item.CantidadBonificacion || 1);
            var producto = ObtenerJSONCodificadoDesdeURL(SERVER_URL + URL_RUTA_PRODUCTOS + item.IdProductoBonificacion + '.json')[0];
            producto.Cantidad = nuevaCantidad;
            parent.AgregarProductoBonificado(producto, event)
          } else {
            parent.DetallesComprobanteVenta.remove(function (item2) { return item2.IdProducto() == item.IdProductoBonificacion && item2.IndicadorOperacionGratuita() == 1; })
          }
        });
      }

      switch (self.cabecera.ParametroFormaCalculoVenta()) {
        case '1':
          self.FormaDeCalculoUno(data, event, parent, 'Cantidad');
          break;
        case '2':
          self.FormaDeCalculoDos(data, event, parent, 'Cantidad');
          break;
        case '3':
          self.FormaDeCalculoTres(data, event, parent, 'Cantidad');
          break;
        default:
          self.CalculoSubTotal(data, event, parent);
          break;
      }

    }
  }

  self.OnChangePrecioUnitario = function (data, event, parent) {
    if (event) {
      switch (self.cabecera.ParametroFormaCalculoVenta()) {
        case '1':
          self.FormaDeCalculoUno(data, event, parent, 'PrecioUnitario');
          break;
        case '2':
          self.FormaDeCalculoDos(data, event, parent, 'PrecioUnitario');
          break;
        case '3':
          self.FormaDeCalculoTres(data, event, parent, 'PrecioUnitario');
          break;
        default:
          self.CalculoSubTotal(data, event, parent);
          break;
      }

      if (self.cabecera.ParametroCalculoSolesDolares() == 1) {
        self.CalcularPrecioSolesDolaresPorPrecioUnitario()
      }
    }
  }

  self.OnChangePrecioUnitarioSolesDolares = function (data, event, parent) {
    if (event) {
      self.CalcularPrecioUnitarioPorPrecioSolesDolares();
      // self.OnChangePrecioUnitario(data, event, parent);
    }
  }


  self.OnChangeSubTotal = function (data, event, parent) {
    if (event) {
      switch (self.cabecera.ParametroFormaCalculoVenta()) {
        case '1':
          self.FormaDeCalculoUno(data, event, parent, 'SubTotal');
          break;
        case '2':
          self.FormaDeCalculoDos(data, event, parent, 'SubTotal');
          break;
        case '3':
          self.FormaDeCalculoTres(data, event, parent, 'SubTotal');
          break;
        default:
          self.CalculoSubTotal(data, event, parent);
          break;
      }
    }
  }

  self.LimpiarProducto = function (data, event) {
    if (event) {
      self.IdProducto("");
    }
  }

  self.OnChangeNombreProducto = function (data, event) {
    if (event) {
      if (self.IdProducto() != "" && self.IdProducto() != null) {
        var descripcionProducto = self.NombreProducto();
        $(self.InputCodigoMercaderia()).attr("data-validation-text-found", descripcionProducto);
        $(self.InputCodigoMercaderia()).attr("data-validation-found", "true");
      }
    }
  }

  self.OnClickBtnNuevaMercaderia = function (data, event, dataMercaderia) {
    if (event) {
      dataMercaderia.OnNuevaMercaderia(dataMercaderia.MercaderiaNueva, event, self.PostCerrarMercaderia);
      return true;
    }
  }

  self.PostCerrarMercaderia = function (data, event) {
    if (event) {
      var producto = self.cabecera.ObtenerProductoPorIdProducto(Knockout.CopiarObjeto(data), event)
      self.ValidarAutocompletadoProducto(producto, event)
      $(self.InputProducto()).focus()
    }
  }

  self.OnDisableCantidad = ko.computed(function () {

    // Forma de calculo controlado desde mercaderia
    if (self.EstadoCampoCalculo) {
      if (self.EstadoCampoCalculo() == "3" || self.EstadoCampoCalculo() == "2") {
        return true
      }
    }

    return false;
  }, this)

  self.OnDisablePrecioUnitario = ko.computed(function () {

    if (self.cabecera) {
      if (self.cabecera.IndicadorEditarCampoPrecioUnitarioVenta() == 0) {
        return true;
      }
    }

    if (self.ProductoBonificado()) {
      return true;
    }

    // Forma de calculo controlado desde mercaderia
    if (self.EstadoCampoCalculo) {
      if (self.EstadoCampoCalculo() == "3" || self.EstadoCampoCalculo() == "1") {
        return true
      }
    }

    return false;
  }, this)

  self.OnDisableSubTotal = ko.computed(function () {

    if (self.cabecera) {
      if (self.cabecera.IndicadorEditarCampoPrecioUnitarioVenta() == 0) {
        return true;
      }
    }

    if (self.ProductoBonificado()) {
      return true;
    }

    // Forma de calculo controlado desde mercaderia
    if (self.EstadoCampoCalculo) { 
      if (self.EstadoCampoCalculo() == "1" || self.EstadoCampoCalculo() == "2") {
        return true
      }
    }

    return false;
  }, this)
}
