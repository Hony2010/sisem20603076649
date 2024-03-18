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

        if ($('#contenedor_reporte_ventas_por_mercaderia').hasClass('active')) {
          $('#TextoBuscar_Mercaderia').val(elemento.CodigoMercaderia + ' - ' + elemento.NombreProducto);
          vistaModeloReporte.vmgReporteVentasPorMercaderia.dataReporteVentasPorMercaderia.Buscador.TextoMercaderia_Mercaderia(elemento.IdProducto);
        }

        if ($('#contenedor_reporte_ganancia_por_producto').hasClass('active')) {
          $('#TextoBuscar_Gananciaporproducto').val(elemento.CodigoMercaderia + ' - ' + elemento.NombreProducto);
          vistaModeloReporte.vmgReporteGananciaPorProducto.dataReporteGananciaPorProducto.Buscador.TextoMercaderia_Gananciaporproducto(elemento.IdProducto);
        }
        if ($('#contenedor_reporte_ganancia_por_vendedor').hasClass('active')) {
          $('#TextoBuscar_Gananciaporvendedor').val(elemento.CodigoMercaderia + ' - ' + elemento.NombreProducto);
          vistaModeloReporte.vmgReporteGananciaPorVendedor.dataReporteGananciaPorVendedor.Buscador.TextoMercaderia_Gananciaporvendedor(elemento.IdProducto);
        }
        if ($('#contenedor_reporte_ganancia_por_precio_base_producto').hasClass('active')) {
          $('#TextoBuscar_GananciaPorPrecioBaseProducto').val(elemento.CodigoMercaderia + ' - ' + elemento.NombreProducto);
          vistaModeloReporte.vmgReporteGananciaPorPrecioBaseProducto.dataReporteGananciaPorPrecioBaseProducto.Buscador.TextoMercaderia_GananciaPorPrecioBaseProducto(elemento.IdProducto);
        }
      },
    },
    theme: "square"
  };

  return this.options;
}
