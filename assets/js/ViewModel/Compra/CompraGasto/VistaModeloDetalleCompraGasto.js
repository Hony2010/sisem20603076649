
VistaModeloDetalleCompraGasto = function (data) {
  var self = this;
  ko.mapping.fromJS(data, MappingCatalogo, self);
  ModeloDetalleCompraGasto.call(this, self);
  self.DecimalCantidad = ko.observable(CANTIDAD_DECIMALES_GASTO.CANTIDAD);
  self.DecimalPrecioUnitario = ko.observable(CANTIDAD_DECIMALES_GASTO.PRECIO_UNITARIO);
  self.DecimalCostoUnitario = ko.observable(CANTIDAD_DECIMALES_GASTO.COSTO_UNITARIO);

  self.InicializarVistaModelo = function (event) {
    if (event) {
      self.Producto.InicializarVistaModelo(data, event);
      var input = { id: self.InputProducto() };
      $(self.InputProducto()).autoCompletadoGasto(input, event, self.ValidarAutocompletadoProducto);
      self.InicializarModelo(event);

      $(self.InputProducto()).on("focusout", function (event) {
        self.ValidarNombreProducto(undefined, event);
      });
    }
  }

  self.InputProducto = ko.computed(function () {
    if (self.IdDetalleComprobanteCompra != undefined) {
      return "#" + self.IdDetalleComprobanteCompra() + "_input_NombreProducto";
    }
    else {
      return "";
    }
  }, this);

  self.InputPrecioUnitario = ko.computed(function () {
    if (self.IdDetalleComprobanteCompra != undefined) {
      return "#" + self.IdDetalleComprobanteCompra() + "_input_PrecioUnitario";
    }
    else {
      return "";
    }
  }, this);

  self.InputCantidad = ko.computed(function () {
    if (self.IdDetalleComprobanteCompra != undefined) {
      return "#" + self.IdDetalleComprobanteCompra() + "_input_Cantidad";
    }
    else {
      return "";
    }
  }, this);

  self.InputPrecioCosto = ko.computed(function () {
    if (self.IdDetalleComprobanteCompra != undefined) {
      return "#" + self.IdDetalleComprobanteCompra() + "_input_PrecioCosto";
    }
    else {
      return "";
    }
  }, this);

  self.InputCostoUnitario = ko.computed(function () {
    if (self.IdDetalleComprobanteCompra != undefined) {
      return "#" + self.IdDetalleComprobanteCompra() + "_input_CostoUnitario";
    }
    else {
      return "";
    }
  }, this);

  self.InputAfectoIGV = ko.computed(function () {
    if (self.IdDetalleComprobanteCompra != undefined) {
      return "#" + self.IdDetalleComprobanteCompra() + "_input_AfectoIGV";
    }
    else {
      return "";
    }
  }, this);

  self.InputOpcion = ko.computed(function () {
    if (self.IdDetalleComprobanteCompra != undefined) {
      return "#" + self.IdDetalleComprobanteCompra() + "_a_opcion";
    }
    else {
      return "";
    }
  }, this);

  self.InputIdProducto = ko.computed(function () {
    if (self.IdDetalleComprobanteCompra != undefined) {
      return "#" + self.IdDetalleComprobanteCompra() + "_input_IdProducto";
    }
    else {
      return "";
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
      var id = "#" + data.IdDetalleComprobanteCompra();
      $(id).addClass('active').siblings().removeClass('active');
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

  self.ValidarProductoPorIdProducto = function (data, event, $callback) {
    if (event) {

      var codigo = data.IdProducto();
      var url_json = SERVER_URL + URL_JSON_GASTOS;
      var _busqueda = "IdProducto";

      var $input = $(self.IdProducto());
      // var json = JSON.parse($.getJSON({'url': url_json, 'async': false}).responseText);
      var json = ObtenerJSONCodificadoDesdeURL(url_json);
      var rpta = JSON.search(json, '//*[' + _busqueda + '="' + codigo + '"]');

      if (rpta.length > 0) {
        // var ruta_producto = SERVER_URL + URL_RUTA_PRODUCTOS + rpta[0].IdProducto + '.json';
        // var producto = ObtenerJSONCodificadoDesdeURL(ruta_producto);
        $input.attr("data-validation-found", "true");
        $input.attr("data-validation-text-found", rpta[0].NombreProducto);
        var item = self.Reemplazar(rpta[0]);
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
        if ($(self.InputIdProducto()).attr("data-validation-text-found") === $(self.InputProducto()).val()) {
          $(self.InputIdProducto()).attr("data-validation-found", "true");
          var $evento = { target: self.InputProducto() };
          self.ValidarNombreProducto(data, $evento);
        }
        else {
          $(self.InputIdProducto()).attr("data-validation-text-found", "");
          $(self.InputIdProducto()).attr("data-validation-found", "false");
          var $evento = { target: self.InputProducto() };
          self.ValidarNombreProducto(data, $evento);
        }
      } else {
        if ($(self.InputIdProducto()).attr("data-validation-text-found") === $(self.InputProducto()).val()) {
          var $evento = { target: self.InputProducto() };
          self.ValidarNombreProducto(data, $evento);

        }
        else {

          $(self.InputIdProducto()).attr("data-validation-text-found", data.NombreProducto);
          $(self.InputIdProducto()).attr("data-validation-found", "true");
          var $evento = { target: self.InputProducto() };
          self.ValidarNombreProducto(data, $evento);
          var $evento2 = { target: self.InputIdProducto() };
          var $data = Knockout.CopiarObjeto(data);

          self.ValidarProductoPorIdProducto($data, $evento2, function ($data3, $evento3) {

          });
        }
      }
      $(self.InputCantidad()).focus();
    }
  }

  self.ValidarPrecioUnitario = function (data, event) {
    if (event) {
      if (data.PrecioUnitario() === "") data.PrecioUnitario("0.0000");
      $(event.target).validate(function (valid, elem) {

      });
    }
  }

  self.ValidarCostoUnitario = function (data, event) {
    if (event) {
      if (data.CostoUnitario() === "") data.CostoUnitario("0.0000");
      $(event.target).validate(function (valid, elem) {

      });
    }
  }

  self.ValidarAfectoIGV = function (data, event) {
    if (event) {
      if (data.AfectoIGV() === "") data.AfectoIGV("0.00");

      $(event.target).validate(function (valid, elem) {
      });

    }
  }

  self.ValidarCantidad = function (data, event) {
    if (event) {
      if (data.Cantidad() === "") data.Cantidad("0.00");

      $(event.target).validate(function (valid, elem) {
      });
    }
  }

  self.ValidarSubTotal = function (data, event) {
    if (event) {
      if (data.SubTotal() === "") data.SubTotal("0.00");
      $(event.target).validate(function (valid, elem) {
      });
    }
  }

  self.ValidarPrecioCosto = function (data, event) {
    if (event) {
      if ($(self.InputPrecioUnitario()).length == 1) {
        if (data.CostoItem() === "") data.PrecioUnitario("0.00");
        $(event.target).validate(function (valid, elem) {
        });
      }
      else {
        if (data.PrecioItem() === "") data.CostoUnitario("0.00");
        $(event.target).validate(function (valid, elem) {
        });
      }

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

  self.CalculoSubTotal = function (data, event, callback) {
    if (event) {
      self.CalcularSubTotal(data, event);
      callback(data, event);
    }
  }

  self.OnFocusPrecioUnitario = function (data, event, callback) {
    if (event) {
      $(event.target).select();
      self.Seleccionar(data, event);
      callback(data, event, false);
    }
    return true;
  }

  self.CalculoPrecioCosto = function (data, event, callback) {
    if (event) {
      self.CalcularPrecioCosto(data, event);
      callback(data, event);
    }
  }

}
