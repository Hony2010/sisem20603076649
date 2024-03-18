VistaModeloMetaVentaProducto = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.Inicializar = function () {
        var target = { id: self.InputProductoMeta(), TipoVenta: TIPO_VENTA.MERCADERIAS };
        $(target.id).autoCompletadoProducto(target, event, self.ValidarAutoCompletadoProducto, ORIGEN_MERCADERIA.TODOS);
    }

    self.ValidarProductoMeta = function (data, event) {
        if (event) {
            $(event.target).validate(function (valid, elem) {
                if (!valid) { self.IdProducto(""); }
            });
        }
    }

    self.InputProductoMeta = ko.computed(function () {
        return self.IdMetaVentaProducto ? "#inputProductoMeta_" + self.IdMetaVentaProducto() : "";
    }, this);

    self.ProductoMeta = ko.computed(function () {
        return self.NombreProducto ? self.NombreProducto() : "";
    }, this);


    self.ValidarAutoCompletadoProducto = function (data, event) {
        if (event) {
            var $inputProductoMeta = $(self.InputProductoMeta());
            $inputProductoMeta[0].value = $inputProductoMeta.val().trim();
            $inputProductoMeta.attr("data-validation-error-msg", "No se han encontrado resultados para tu búsqueda");

            var $evento = { target: self.InputProductoMeta() };

            if (data === -1) {
                if ($inputProductoMeta.attr("data-validation-text-found") === $inputProductoMeta.val()) {
                    self.ValidarProductoMeta(data, $evento);
                } else {
                    $inputProductoMeta.attr("data-validation-text-found", "");
                    self.ValidarProductoMeta(data, $evento);
                }
            } else {
                var ruta_producto = SERVER_URL + URL_RUTA_PRODUCTOS + data.IdProducto + '.json';
                var producto = ObtenerJSONCodificadoDesdeURL(ruta_producto)[0];

                if (($inputProductoMeta.attr("data-validation-text-found") !== $inputProductoMeta.val()) || ($inputProductoMeta.val() == "")) {
                    $inputProductoMeta.attr("data-validation-text-found", producto.NombreProducto);
                }

                ko.mapping.fromJS(producto, {}, self);
                self.ValidarProductoMeta(data, $evento);
                self.FocusNextAutocompleteProducto(event);
            }
        }
    }

    self.FocusNextAutocompleteProducto = function (event) {
        if (event) {
          var $inputProducto = $(self.InputProductoMeta());
          var pos = $inputProducto.closest("tr").find("input").not(':disabled').index($inputProducto);
          $inputProducto.closest("tr").find("input").not(':disabled').eq(pos + 1).focus();
        }
      }
}
