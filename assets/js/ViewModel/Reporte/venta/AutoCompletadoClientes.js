var RUTA_DATA_CLIENTES = SERVER_URL + URL_JSON_CLIENTES;

function optionsAutoCompletadoCliente(data, evento) {
  var data = data;
  var evento = evento;

  this.options = {
    url: RUTA_DATA_CLIENTES,
    getValue: function (element) {
      if (element == undefined) {
        return "";
      }
      else {
        if (element.EstadoCliente == ESTADO_CLIENTE.VISIBLE) {
          return element.NumeroDocumentoIdentidad + " - " + element.RazonSocial;
        } else {
          return "";
        }
      }
    },
    list: {
      match: {
        enabled: true
      },
      onChooseEvent: function () {
        var elemento = $(data).getSelectedItemData();
        if ($('#contenedor_reporte_de_venta_detallado').hasClass('active')) {
          $('#TextoBuscar_D').val(elemento.NumeroDocumentoIdentidad + " - " + elemento.RazonSocial);
          vistaModeloReporte.vmgReporteVentaDetallado.dataReporteVentaDetallado.Buscador.IdPersona(elemento.IdPersona);
        }
        else if ($('#contenedor_reporte_de_venta_general').hasClass('active')) {
          $('#TextoBuscar_R').val(elemento.NumeroDocumentoIdentidad + " - " + elemento.RazonSocial);
          vistaModeloReporte.vmgReporteVentaGeneral.dataReporteVentaGeneral.Buscador.IdPersona(elemento.IdPersona);
        }
        else if ($('#contenedor_reporte_saldo_al_cliente').hasClass('active')) {
          $('#TextoBuscar_SaldoCliente').val(elemento.RazonSocial);
          $('#TextoBuscarOculto_SaldoCliente').val(elemento.IdPersona);
        }
      },
    },
    theme: "square"
  };

  return this.options;
}
