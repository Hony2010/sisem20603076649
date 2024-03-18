
VistaModeloDetalleNotaEntrada = function (data) {
  var self = this;
  ko.mapping.fromJS(data, MappingCatalogo, self);
  self.IdsDetalle = ko.observable([]);
  self.Cantidad = ko.observable(data.Cantidad !== null && data.Cantidad !== "" ? data.Cantidad : data.SaldoPendienteEntrada);
  self.DecimalCantidad = ko.observable(CANTIDAD_DECIMALES_VENTA.CANTIDAD);
  self.DecimalValorUnitario = ko.observable(CANTIDAD_DECIMALES_VENTA.PRECIO_UNITARIO);
  self.EstadoInputOpcion = ko.observable(true)

  ModeloDetalleNotaEntrada.call(this, self);

  self.InicializarVistaModelo = function (event, callback, callback2) {
    if (event) {
      self.Producto.InicializarVistaModelo(data, event);
      // var input = {id : self.InputProducto() };
      var input = { id: self.InputProducto(), TipoVenta: TIPO_VENTA.MERCADERIAS };
      $(self.InputProducto()).autoCompletadoProducto(input, event, self.ValidarAutocompletadoProducto, ORIGEN_MERCADERIA.TODOS);
      self.InicializarModelo(event, callback, callback2);

      $(self.InputProducto()).on("focusout", function (event) {
        self.ValidarNombreProducto(undefined, event);
      });
    }
  }

  self.InputCodigoMercaderia = ko.computed(function () {
    if (self.IdDetalleNotaEntrada != undefined) {
      return "#" + self.IdDetalleNotaEntrada() + "_input_CodigoMercaderia";
    }
    else {
      return "";
    }
  }, this);

  self.InputProducto = ko.computed(function () {
    if (self.IdDetalleNotaEntrada != undefined) {
      return "#" + self.IdDetalleNotaEntrada() + "_input_NombreProducto";
    }
    else {
      return "";
    }
  }, this);

  self.InputCantidad = ko.computed(function () {
    if (self.IdDetalleNotaEntrada != undefined) {
      return "#" + self.IdDetalleNotaEntrada() + "_input_Cantidad";
    }
    else {
      return "";
    }
  }, this);

  self.InputValorUnitario = ko.computed(function () {
    if (self.IdDetalleNotaEntrada != undefined) {
      return "#" + self.IdDetalleNotaEntrada() + "_input_ValorUnitario";
    }
    else {
      return "";
    }
  }, this);

  self.InputDescuentoItem = ko.computed(function () {
    if (self.IdDetalleNotaEntrada != undefined) {
      return "#" + self.IdDetalleNotaEntrada() + "_input_DescuentoItem";
    }
    else {
      return "";
    }
  }, this);

  self.InputOpcion = ko.computed(function () {
    if (self.IdDetalleNotaEntrada != undefined) {
      return "#" + self.IdDetalleNotaEntrada() + "_a_opcion";
    }
    else {
      return "";
    }
  }, this);

  self.OnFocus = function (data, event, $callbackParent, $callback) {
    if (event) {
      $callbackParent(data, event);
      $callback(data, event);
    }

    return true;
  }

  self.OnKeyEnter = function (data, event, $callbackParent) {
    if (event) {
      $callbackParent(data, event);
    }
    return true;
  }

  self.procesado = false;

  self.OnKeyEnterCodigoMercaderia = function (data, event, $callbackParent) {
    if (event) {
      if (event.keyCode === TECLA_ENTER) {

        data.CodigoMercaderia($(event.target).val());
        self.ValidarProductoPorCodigo(data, event, function ($data, $event, $valid) {
          var $evento = { target: self.InputProducto() };
          self.procesado = true;
          self.ValidarNombreProducto(data, $evento);
          $callbackParent($data, $event);
          self.procesado = false;
        });
      }
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

      var $input = $(self.InputCodigoMercaderia());
      var datajs = { CodigoMercaderia: data.CodigoMercaderia(), IdGrupoProducto: TIPO_VENTA.MERCADERIAS };

      var codigo = String(data.CodigoMercaderia());
      var url_json = SERVER_URL + URL_JSON_MERCADERIAS;
      codigo = codigo.toUpperCase();
      var json = ObtenerJSONCodificadoDesdeURL(url_json);
      var rpta = JSON.search(json, '//*[CodigoMercaderia="' + codigo + '"]');

      if (rpta.length > 0) {
        var ruta_producto = SERVER_URL + URL_RUTA_PRODUCTOS + rpta[0].IdProducto + '.json';
        var producto = ObtenerJSONCodificadoDesdeURL(ruta_producto);
        $input.attr("data-validation-found", "true");
        $input.attr("data-validation-text-found", producto[0].NombreProducto);
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
        $(self.InputCantidad()).focus();
      }
      else {
        if ($(self.InputCodigoMercaderia()).attr("data-validation-text-found") === $(self.InputProducto()).val()) {
          $(self.InputCodigoMercaderia()).attr("data-validation-found", "true");
          var $evento = { target: self.InputProducto() };
          self.ValidarNombreProducto(data, $evento);
          $(self.InputCantidad()).focus();
        }
        else {

          $(self.InputCodigoMercaderia()).attr("data-validation-text-found", data.NombreProducto);
          $(self.InputCodigoMercaderia()).attr("data-validation-found", "true");
          var $evento = { target: self.InputProducto() };
          self.ValidarNombreProducto(data, $evento);
          var $evento2 = { target: self.InputCodigoMercaderia() };
          var $data = Knockout.CopiarObjeto(data);

          self.ValidarProductoPorCodigo($data, $evento2, function ($data3, $evento3) {
            $(self.InputCantidad()).focus();
          });
        }
      }
    }
  }

  self.ValidarCantidad = function (data, event) {
    if (event) {
      if (data.Cantidad() === "") data.Cantidad("0.00");
      if (window.Motivo.Reglas.DocumentoReferencia == 1) {
        if (parseFloat(data.Cantidad()) > parseFloat(self.SaldoPendienteEntrada())) {
          data.Cantidad(self.SaldoPendienteEntrada());
          alertify.alert("La Cantidad no puede ser Mayor al Saldo del producto.", function () {
            $(event.target).focus();
          });
        }
      }

      $(event.target).validate(function (valid, elem) {
      });


    }
  }

  self.ValidarValorUnitario = function (data, event) {
    if (event) {
      if (data.ValorUnitario() === "") data.ValorUnitario("0.00");
      $(event.target).validate(function (valid, elem) {

      });
    }
  }

  self.ValidarDescuentoItem = function (data, event) {
    if (event) {
      if (data.DescuentoItem() === "") data.DescuentoItem("0.00");

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
}
