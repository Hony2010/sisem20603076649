VistaModeloMetaVentaVendedor = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.titulo = "Metas de vendedores";

    ModeloMetaVentaVendedor.call(this, self);

    self.Inicializar = function () {

    }

    self.OnClickBtnGuargarMetaVentaVendedor = function (data, event) {
        if (event) {
            var obj = self.data.MetasVentaVendedor();
            $("#loader").show();
            self.RegistrarMetaVentaVendedor(obj, event, function ($data, $event) {
                $("#loader").hide();
                if (!$data.error) {
                    alertify.alert(self.titulo, 'Se guardo correctamente las metas de vendedores', function () { });
                } else {
                    alertify.alert(self.titulo, $data.error.msg, function () { });
                }
            })
        }
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



}
