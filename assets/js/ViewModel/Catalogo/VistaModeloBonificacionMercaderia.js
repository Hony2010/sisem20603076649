VistaModeloBonificacionMercaderia = function (data, $parent) {
  var self = this;
  self.parent = $parent;
  ko.mapping.fromJS(data, {}, self);


  self.InicializarVistaModelo = function () {
      var target = { id: self.InputProductoBonificacion(), TipoVenta : TIPO_VENTA.MERCADERIAS};
      $(target.id).autoCompletadoProducto(target, event, self.ValidarAutoCompletadoProducto,ORIGEN_MERCADERIA.TODOS);
  }

  self.OnFocus = function (data, event, callback) {
    if (event) {
      $(event.target).select();
      if (callback) callback(data, event);
    }
  }

  self.OnKeyEnter = function (data, event) {
    if (event) {
      var resultado = $(event.target).enterToTab(event);
      return resultado;
    }
  }
  self.OnClickBtnRemoverBonificacionMercaderia = function (data, event) {
    if (event) {
      self.parent.Bonificaciones.remove(data);
      event.stopPropagation();
    }
  };

  self.ValidarProductoBonificacion = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
        if (!valid) { self.IdProducto(""); }
        self.OnKeyEnter(data, event)
      });
    }
  }

  self.InputProductoBonificacion = ko.computed(function () {
    return self.IdBonificacion ? "#inputProductoBonificacion_" + self.IdBonificacion() : "";
  }, this);

  self.ProductoBonificacion = ko.computed(function () {
    return self.NombreProducto ? self.NombreProducto() : "";
  }, this);


  self.ValidarAutoCompletadoProducto = function (data, event) {
    if (event) {
      var $inputProductoBonificacion = $(self.InputProductoBonificacion());
      $inputProductoBonificacion[0].value = $inputProductoBonificacion.val().trim();
      $inputProductoBonificacion.attr("data-validation-error-msg", "No se han encontrado resultados para tu búsqueda");

      var $evento = { target: self.InputProductoBonificacion() };

      if (data === -1) {
        if ($inputProductoBonificacion.attr("data-validation-text-found") === $inputProductoBonificacion.val()) {
          self.ValidarProductoBonificacion(data, $evento);
        } else {
          $inputProductoBonificacion.attr("data-validation-text-found", "");
          self.ValidarProductoBonificacion(data, $evento);
        }
      } else {
        var ruta_producto = SERVER_URL + URL_RUTA_PRODUCTOS + data.IdProducto + '.json';
        var producto = ObtenerJSONCodificadoDesdeURL(ruta_producto)[0];

        if (($inputProductoBonificacion.attr("data-validation-text-found") !== $inputProductoBonificacion.val()) || ($inputProductoBonificacion.val() == "")) {
          $inputProductoBonificacion.attr("data-validation-text-found", producto.NombreProducto);
        }

        var obj = {
          // Cantidad: '1.00',
          IdProducto: self.parent.IdProducto(),
          IdProductoBonificacion: producto.IdProducto,
          NombreProducto : producto.NombreProducto
        }

        ko.mapping.fromJS(obj, {}, self);
        self.ValidarProductoBonificacion(data, $evento);

        var pos = $inputProductoBonificacion.closest("Form").find("input, select").not(':disabled').index($inputProductoBonificacion);
        $inputProductoBonificacion.closest("Form").find("input, select").not(':disabled').eq(pos + 1).focus();
      }
    }
  }

}
