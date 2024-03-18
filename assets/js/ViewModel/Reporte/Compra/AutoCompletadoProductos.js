var RUTA_DATA_MERCADERIAS = SERVER_URL + 'assets/data/mercaderia/mercaderias.json';

function optionsAutoCompletadoProducto(data, event) {
  var data = data;
  var evento = evento;

  this.options = {
    url: RUTA_DATA_MERCADERIAS,
    getValue: function (element) {
      if (element == undefined) {
        return "";
      } else {
        if (element.EstadoProducto == ESTADO_PRODUCTO.VISIBLE) {
          return element.CodigoMercaderia + ' - ' + element.NombreProducto;
        } else {
          return ""
        }
      }
    },
    list: {
      match: {
        enabled: true
      },
      onChooseEvent: function () {
        var elemento = $(data).getSelectedItemData();

        if ($('#contenedor_reporte_compras_por_mercaderia').hasClass('active')) {
          $('#TextoBuscar_Mercaderia').val(elemento.NombreProducto);
          vistaModeloReporte.vmgReporteComprasPorMercaderia.dataReporteComprasPorMercaderia.Buscador.TextoMercaderia_Mercaderia(elemento.IdProducto);
        }
        else if ($('#contenedor_reporte_producto_por_proveedor').hasClass('active')) {
          $('#TextoBuscarMercaderia_ProductoProveedor').val(elemento.NombreProducto);
          vistaModeloReporte.vmgReporteProductoPorProveedor.dataReporteProductoPorProveedor.Buscador.TextoMercaderia_ProductoProveedor(elemento.IdProducto);
        }
      },
    },
    theme: "square"
  };

  return this.options;
}
