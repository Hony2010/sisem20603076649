BusquedaFacturasGuiaModel = function () {
  var self = this;

  
  self.ConsultarComprobantesGuia = function (data, event, callback) {
    var $data = { Data : data };
    if (event) {
      $.ajax({
        type: 'GET',
        data: $data,
        dataType: "json",
        url: SITE_URL + '/Venta/cVenta/ConsultarComprobantesGuia',
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
