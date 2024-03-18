VistaModeloBusquedaProforma = function (data, comprobante) {
  var self = this;

  ko.mapping.fromJS(data, {}, self);

  self.Comprobante = comprobante;

  self.Inicializar = function () {

  }

  self.OnFocus = function (data, event) {
    if (event) {
      $(event.target).select();
    }
  }

  self.OnKeyEnter = function (data, event) {
    if (event) {
      var resultado = $(event.target).enterToTab(event);
      return resultado;
    }
  }

  self.ObtenerFiltro = function (data, event) {
    if (event) {
      var filtro = ko.mapping.toJS(data);
      filtro.TextoFiltro = filtro.TextoFiltro == '' ? '%' : filtro.TextoFiltro;
      filtro.IdUsuarioVendedor = filtro.IdUsuarioVendedor == undefined ? '%' : datajs.IdUsuarioVendedor;

      return filtro;
    }
  }

  self.OnClickBtnBuscarProformas = function (data, event) {
    if (event) {
      var datajs = { Data: self.ObtenerFiltro(data, event) };
      $("#loader").show();
      self.ConsultarComprobantesVentaProforma(datajs, event, function ($data) {
        $("#loader").hide();
        if (!$data.error) {
        } else {
          alertify.alert('ha ocurrido un error', $data.error.msg, function () { });
        }
      })
    }
  }

  self.ConsultarComprobantesVentaProforma = function (data, event, callback) {
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


}
