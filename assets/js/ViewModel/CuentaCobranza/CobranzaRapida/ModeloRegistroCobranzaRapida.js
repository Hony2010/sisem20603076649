ModeloCobranzaRapida = function (data) {
  var self = this;
  var base = data;

  self.InsertarCobranzaRapida = function (data, event, callback) {
    if (event) {
      var datajs = { Data: JSON.stringify(ko.mapping.toJS(data, mappingIgnore)) }
      $.ajax({
        type: 'POST',
        dataType: 'json',
        data: datajs,
        cache: false,
        url: SITE_URL + '/CuentaCobranza/cCobranzaRapida/InsertarCobranzaRapida',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          callback({error: jqXHR.responseText}, event);
        }
      });
    }
  }
  
  self.ConsultarComprobantesVentaClientesConDeudaPorVendedor = function (data, event, callback) {
    if (event) {
      var datajs = { Data: JSON.stringify(ko.mapping.toJS(data, mappingIgnore)) }
      $.ajax({
        type: 'POST',
        dataType: 'json',
        data: datajs,
        cache: false,
        url: SITE_URL + '/CuentaCobranza/cCobranzaRapida/ConsultarComprobantesVentaClientesConDeudaPorVendedor',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          callback({error: jqXHR.responseText}, event);
        }
      });
    }
  }

  self.ConsultarCuentaCobranzaPorIdComprobanteVenta = function (data, event, callback) {
    if (event) {
      var datajs = { Data: JSON.stringify(ko.mapping.toJS(data, mappingIgnore)) }
      $.ajax({
        type: 'POST',
        dataType: 'json',
        data: datajs,
        cache: false,
        url: SITE_URL + '/CuentaCobranza/cCobranzaRapida/ObtenerPendienteCobranzaClientePorIdComprobanteVenta',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          callback({ error: jqXHR.responseText }, event);
        }
      });
    }
  }

  self.ObtenerComprobanteVentaPorId = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'GET',
        dataType: 'json',
        data: data,
        cache: false,
        url: SITE_URL + '/Venta/cVenta/ObtenerComprobanteVentaPorId',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          callback({ error: jqXHR.responseText }, event);
        }
      });
    }
  }

  self.ObtenerComprobanteVentaPorId = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'GET',
        dataType: 'json',
        data: data,
        cache: false,
        url: SITE_URL + '/Venta/cVenta/ObtenerComprobanteVentaPorId',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          callback({ error: jqXHR.responseText }, event);
        }
      });
    }
  }

  self.ConsultarDetallesComprobanteVenta = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'GET',
        dataType: 'json',
        data: data,
        cache: false,
        url: SITE_URL + '/Venta/ComprobanteVenta/cDetalleComprobanteVenta/ConsultarDetallesComprobanteVenta',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          callback({ error: jqXHR.responseText }, event);
        }
      });
    }
  }
  
}
