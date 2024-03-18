ModeloBusquedaProforma = function () {
  var self = this;

  self.ConsultarVentasProformas = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + '/Venta/ComprobanteVenta/cComprobanteVenta/ConsultarVentasProformas',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR) {
          callback({ error: { msg: jqXHR.responseText } }, event);
        }
      });
    }
  }

  self.ConsultarVentasProformasPorPagina = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + '/Venta/ComprobanteVenta/cComprobanteVenta/ConsultarVentasProformasPorPagina',
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
