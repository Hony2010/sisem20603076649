var RUTA_DATA_EMPLEADOS = SERVER_URL + URL_JSON_EMPLEADOS;

function optionsAutoCompletadoVendedor(data, evento) {
  var data = data;
  var evento = evento;

  this.options = {
    url: RUTA_DATA_EMPLEADOS,
    getValue: function (element) {
      if (element == undefined) {
        return "";
      }
      else {
        if (element.EstadoEmpleado == EMPLEADO.VISIBLE) {
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

        if ($('#contenedor_reporte_ventas_por_vendedor').hasClass('active')) {
          $('#TextoBuscar_Vendedor').val(elemento.NumeroDocumentoIdentidad + " - " + elemento.NombrePersona);
          vistaModeloReporte.vmgReporteVentasPorVendedor.dataReporteVentasPorVendedor.Buscador.TextoVendedor_Vendedor(elemento.NombrePersona);
        }
      },
    },
    theme: "square"
  };

  return this.options;
}
