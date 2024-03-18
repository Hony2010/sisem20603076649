VistaModeloDetalleGuiaRemisionRemitente = function (data, parent) {
  var self = this;
  self.parent = parent;
  ko.mapping.fromJS(data, {}, self);

  self.UltimoItem = ko.observable(false);
  self.DecimalCantidad = ko.observable(CANTIDAD_DECIMALES_GUIA_REMISION_REMITENTE.CANTIDAD);
  self.DecimalPeso = ko.observable(CANTIDAD_DECIMALES_GUIA_REMISION_REMITENTE.PESO);
  self.DecimalPendiente = ko.observable(CANTIDAD_DECIMALES_GUIA_REMISION_REMITENTE.PENDIENTE);

  ModeloDetalleGuiaRemisionRemitente.call(this, self);

  self.InicializarVistaModelo = function (event) {
    if (event) {
      var data = { id: self.InputProducto(), TipoVenta: TIPO_VENTA.MERCADERIAS };
      $(data.id).autoCompletadoProducto(data, event, self.ValidarAutocompletadoProducto, ORIGEN_MERCADERIA.GENERALVENTA);
    }
  }
  self.InputCodigoMercaderia = ko.computed(function () {
    return self.IdDetalleGuiaRemisionRemitente ? "#input_CodigoMercaderia_" + self.IdDetalleGuiaRemisionRemitente() : "";
  }, this);

  self.InputProducto = ko.computed(function () {
    return self.IdDetalleGuiaRemisionRemitente ? "#input_NombreProducto_" + self.IdDetalleGuiaRemisionRemitente() : "";
  }, this);

  self.InputCantidad = ko.computed(function () {
    return self.IdDetalleGuiaRemisionRemitente ? "#input_Cantidad_" + self.IdDetalleGuiaRemisionRemitente() : "";
  }, this);

  self.InputNumeroLote = ko.computed(function () {
    return self.IdDetalleGuiaRemisionRemitente ? "#" + self.IdDetalleGuiaRemisionRemitente() + "_input_NumeroLote" : "";
  }, this);

  self.InputPrecioUnitario = ko.computed(function () {
    return self.IdDetalleGuiaRemisionRemitente ? "#input_PrecioUnitario_" + self.IdDetalleGuiaRemisionRemitente() : "";
  }, this);

  self.InputSubTotal = ko.computed(function () {
    return self.IdDetalleGuiaRemisionRemitente ? "#input_SubTotal_" + self.IdDetalleGuiaRemisionRemitente() : "";
  }, this);

  self.InputFila = ko.computed(function () {
    return self.IdDetalleGuiaRemisionRemitente ? "#tr_Detalle_" + self.IdDetalleGuiaRemisionRemitente() : "";
  }, this);

  self.OnClickFila = function (data, event, callback) {
    if (event) {
      self.Seleccionar(data, event);
      callback(data, event, false);
    }
  }

  self.Seleccionar = function (data, event) {
    if (event) {
      var id = "#" + data.IdDetalleGuiaRemisionRemitente() + "_tr_detalle";
      if (!$(id).hasClass('enviado')) {
        $(id).addClass('active').siblings().removeClass('active');
      }
    }
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


  self.OnKeyEnterCodigoMercaderia = function (data, event) {
    if (event) {
      if (event.keyCode === TECLA_ENTER) {
        data.CodigoMercaderia($(event.target).val());

        self.ValidarProductoPorCodigo(data, event, function ($data, $event, $valid) {
          var $evento = { target: self.InputProducto() };
          self.procesado = true;
          self.ValidarNombreProducto(data, $evento);
          self.parent.OnKeyEnter(data, event)
          self.procesado = false;
        });
      }
      return true;
    }
  }

  self.ValidarCodigoMercaderia = function (data, event) {
    if (event) {
      if (!self.procesado) {
        self.ValidarProductoPorCodigo(data, event, function ($data, $event, $valid) {
          self.ValidarNombreProducto(data, { target: self.InputProducto() });
          $(event.relatedTarget).select();
        });
      }
    }
  }

  self.ValidarProductoPorCodigo = function (data, event, $callback) {
    if (event) {
      var codigo = data.CodigoMercaderia().toUpperCase();
      var _busqueda = "CodigoMercaderia";

      var $input = $(self.InputCodigoMercaderia());
      var json = ObtenerJSONCodificadoDesdeURL(SERVER_URL + URL_JSON_MERCADERIAS);

      var queryBusqueda = '//*[' + _busqueda + '="' + codigo + '" and IdOrigenMercaderia = "' + ORIGEN_MERCADERIA.GENERAL + '"]';

      var rpta = JSON.search(json, queryBusqueda);

      if (rpta.length > 0) {
        var ruta_producto = SERVER_URL + URL_RUTA_PRODUCTOS + rpta[0].IdProducto + '.json';
        var producto = ObtenerJSONCodificadoDesdeURL(ruta_producto);
        if (producto.length > 0) {
          $input.attr("data-validation-found", "true");
          $input.attr("data-validation-text-found", producto[0].NombreProducto);
          ko.mapping.fromJS(producto[0], {}, self);
          self.parent.OnChangePesoBrutoTotal(data,event);
        }
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

  self.ValidarNombreProducto = function (data, event) {
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
        self.FocusNextAutocompleteProducto(event);
      }
      else {
        if ($(self.InputCodigoMercaderia()).attr("data-validation-text-found") === $(self.InputProducto()).val()) {
          $(self.InputCodigoMercaderia()).attr("data-validation-found", "true");
          var $evento = { target: self.InputProducto() };
          self.ValidarNombreProducto(data, $evento);
          self.FocusNextAutocompleteProducto(event);
        }
        else {

          $(self.InputCodigoMercaderia()).attr("data-validation-text-found", data.NombreProducto);
          $(self.InputCodigoMercaderia()).attr("data-validation-found", "true");
          var $evento = { target: self.InputProducto() };
          self.ValidarNombreProducto(data, $evento);
          var $evento2 = { target: self.InputCodigoMercaderia() };
          var $data = Knockout.CopiarObjeto(data);

          self.ValidarProductoPorCodigo($data, $evento2, function ($data3, $evento3) {
          });
          self.FocusNextAutocompleteProducto(event);
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
      if (data.Cantidad() === "") data.Cantidad("0.00");
      $(event.target).validate(function (valid, elem) {
        if (valid) {

        }
      });
    }
  }

  self.ValidarPrecioUnitario = function (data, event) {
    if (event) {
      if (data.PrecioUnitario() === "") data.PrecioUnitario("0.00");
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

  self.AgregarNuevoDetalle = function (data, event) {
    if (event) {
      if (self.UltimoItem()) {
        self.parent.OnNuevoDetalleGuiaRemisionRemitente(data, event);
      }
    }
  }

  self.OnFocusCodigoMercaderia = function (data, event) {
    if (event) {
      self.parent.OnFocus(data, event);
      self.AgregarNuevoDetalle(data, event);
    }
  }

  self.OnFocusNombreProducto = function (data, event) {
    if (event) {
      self.parent.OnFocus(data, event);
      self.AgregarNuevoDetalle(data, event);
    }
  }

  self.OnFocusPrecioUnitario = function (data, event) {
    if (event) {
      self.parent.OnFocus(data, event);
      self.AgregarNuevoDetalle(data, event);
    }
  }

  self.OnFocusOutPrecioUnitario = function (data, event) {
    if (event) {
      self.ValidarPrecioUnitario(data, event);
    }
  }

  self.OnFocusCantidad = function (data, event) {
    if (event) {
      self.parent.OnFocus(data, event);
      self.AgregarNuevoDetalle(data, event);
    }
  }

  self.OnFocusOutCantidad = function (data, event) {
    if (event) {
      self.ValidarCantidad(data, event);
    }
  }

  self.OnChangeCantidad = function (data, event) {
    if (event) {
      self.CalcularSaldoPendienteGuiaRemision(data, event)
    }
  }

  self.CalcularSaldoPendienteGuiaRemision = function (data, event) {
    if (event) {
      var cantidadPrevia = parseFloatAvanzado(self.CantidadPrevia());
      var cantidadActual = parseFloatAvanzado(self.Cantidad());

      self.SaldoPendienteGuiaRemision(cantidadPrevia - cantidadActual);
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

  self.OnKeyEnter = function (data, event, $callbackParent) {
    if (event) {
      $callbackParent(data, event);
    }
    return true;
  }

  self.OnFocus = function (data, event, callback) {
    if (event) {
      self.parent.OnFocus(data, event);
      self.AgregarNuevoDetalle(data, event);
      //$(event.target).select();
      //self.Seleccionar(data, event);
      //callback(data, event, false);
    }

    return true;
  }

  self.InputOpcion = ko.computed(function () {
    if (self.IdDetalleComprobanteVenta != undefined) {
      return "#" + self.IdDetalleGuiaRemisionRemitente() + "_a_opcion";
    }
    else {
      return "";
    }
  }, this);

  self.OnKeyEnterOpcion = function (data, event, $callbackParent) {
    if (event) {
      if (event.keyCode === TECLA_ENTER) {
        $callbackParent(data, event);
      }
      else if (event.keyCode === TECLA_TAB) {

        var ultimafila = $("tr:last").index();
        var fila = $(event.target).closest("tr").index();

        if (fila === ultimafila - 1) {
          //$("#DescuentoGlobal").focus();
          event.preventDefault();
          event.stopPropagation();
        }
      }

      return true;
    }
  }

  self.OnChangeNombreProducto = function (data, event) {
    if (event) {
      if (self.IdProducto() != "" && self.IdProducto() != null) {
        var descripcionProducto = self.NombreProducto();
        $(self.InputCodigoMercaderia()).attr("data-validation-text-found", descripcionProducto);
        $(self.InputCodigoMercaderia()).attr("data-validation-found", "true");
      }
      self.ValidarNombreProducto(data, event);
    }
  }
}
