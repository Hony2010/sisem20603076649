VistaModeloRegistrarMetaVentaProducto = function (data) {
    var self = this;
    ko.mapping.fromJS(data, MappingMeta, self);

    self.titulo = "Metas por productos";

    ModeloRegistrarMetaVentaProducto.call(this, self);

    self.Inicializar = function () {
        self.InicializarValidaciones();
    }

    self.InicializarValidaciones = function () {
        $.formUtils.addValidator({
            name: 'autocompletado_producto',
            validatorFunction: function (value, $el, config, language, $form) {
                var texto = $el.attr("data-validation-text-found");
                var resultado = (value.toUpperCase() === texto.toUpperCase() && value.toUpperCase() !== "") ? true : false;
                return resultado;
            },
            errorMessageKey: 'badautocompletado_producto'
        });
    }

    self.OnFocus = function (data, event) {
        if (event) {
            $(event.target).select();
        }
    }

    self.OnKeyEnter = function (data, event) {
        var resultado = $(event.target).enterToTab(event);
        return resultado;
    }

    self.OnClickBtnNuevaMetaProducto = function (data, event) {
        if (event) {
            var nuevaMetaProducto = new VistaModeloMetaVentaProducto(ko.mapping.toJS(self.data.MetaVentaProducto), self);
            self.data.MetasVentaProducto.push(nuevaMetaProducto);
            var idMaximo = Math.max.apply(null, ko.utils.arrayMap(self.data.MetasVentaProducto(), function (e) { return e.IdMetaVentaProducto(); }));
            nuevaMetaProducto.IdMetaVentaProducto(idMaximo == '-Infinity' ? 1 : idMaximo + 1);
            nuevaMetaProducto.Inicializar();
            $(nuevaMetaProducto.InputProductoMeta()).focus();
        }
    }

    self.OnClickBtnRemoverMetaProducto = function (data, event) {
        if (event) {
            self.data.MetasVentaProducto.remove(data);
        }
    };



    self.OnClickBtnGuargarMetaVentaProducto = function (data, event) {
        if (event) {
            if ($("#TablaMetasProducto").isValid() === false) {
                alertify.alert(self.titulo, "Existe aun datos inválidos , por favor de corregirlo.", function () {
                    setTimeout(function () { $("#TablaMetasProducto").find('.has-error').find('input').first().focus(); }, 300);
                });
                return false;
            }
            var obj = self.data.MetasVentaProducto();
            $("#loader").show();
            self.RegistrarMetaVentaProducto(obj, event, function ($data, $event) {
                $("#loader").hide();
                if (!$data.error) {
                    alertify.alert(self.titulo, 'Se guardo correctamente las metas por productos', function () { });
                } else {
                    alertify.alert(self.titulo, $data.error.msg, function () { });
                }
            });
        }
    }
}

var MappingMeta = {
    'MetasVentaProducto': {
        create: function (options) {
            if (options)
                return new VistaModeloMetaVentaProducto(options.data, ViewModels);
        }
    }
}
