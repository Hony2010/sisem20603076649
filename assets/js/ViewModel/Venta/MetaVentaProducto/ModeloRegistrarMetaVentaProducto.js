ModeloRegistrarMetaVentaProducto = function (data) {
    var self = this;
    var base = data;

    self.RegistrarMetaVentaProducto = function (data, event, callback) {
        if (event) {
            var datajs = { Data: JSON.stringify(ko.mapping.toJS(data, mappingIgnore)) };

            $.ajax({
                type: 'POST',
                data: datajs,
                dataType: "json",
                url: SITE_URL + '/Venta/cMetaVentaProducto/AgregarMetasVentaProducto',
                success: function (data) {
                    callback(data, event);
                },
                error: function (jqXHR) {
                    callback({ error: { msg: jqXHR.responseText } }, event);
                }
            });
        }
    }
}
