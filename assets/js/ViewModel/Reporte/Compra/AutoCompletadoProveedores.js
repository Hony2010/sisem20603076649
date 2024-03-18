var RUTA_DATA_PROVEEDOR = SERVER_URL + URL_JSON_PROVEEDORES;

function optionsAutoCompletadoProveedor(data, evento) {
  var data = data;
  var evento = evento;

  this.options = {
    url: RUTA_DATA_PROVEEDOR,
    getValue: function (element) {
      if (element == undefined) {
        return "";
      }
      else {
        if (element.EstadoProveedor == ESTADO_PROVEEDOR.VISIBLE) {
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

        if ($('#contenedor_reporte_de_compra_detallado').hasClass('active')) {
          $('#TextoBuscar_Detallado').val(elemento.NumeroDocumentoIdentidad + " - " + elemento.RazonSocial);
          vistaModeloReporte.vmrReporteCompraDetallado.dataReporteCompraDetallado.Buscador.TextoCliente_Detallado(elemento.NumeroDocumentoIdentidad);
        }
        else if ($('#contenedor_reporte_de_compra_general').hasClass('active')) {
          $('#TextoBuscar_General').val(elemento.NumeroDocumentoIdentidad + " - " + elemento.RazonSocial);
          vistaModeloReporte.vmrReporteCompraGeneral.dataReporteCompraGeneral.Buscador.TextoCliente_General(elemento.NumeroDocumentoIdentidad);
        }
        else if ($('#contenedor_reporte_producto_por_proveedor').hasClass('active')) {
          $('#TextoBuscarProveedor_ProductoProveedor').val(elemento.RazonSocial);
          vistaModeloReporte.vmgReporteProductoPorProveedor.dataReporteProductoPorProveedor.Buscador.TextoProveedor_ProductoProveedor(elemento.IdPersona);
        }
        else if ($('#contenedor_reporte_saldo_al_proveedor').hasClass('active')) {
          $('#TextoBuscar_SaldoProveedor').val(elemento.RazonSocial);
          $('#TextoBuscarOculto_SaldoProveedor').val(elemento.IdPersona);
        }
      },
    },
    theme: "square"
  };

  return this.options;
}
