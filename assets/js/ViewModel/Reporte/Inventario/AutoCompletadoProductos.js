var RUTA_DATA_MERCADERIAS = SERVER_URL + URL_JSON_MERCADERIAS;

function optionsAutoCompletadoProducto(data, event) {
  var data = data;
  var evento = evento;

  this.options = {
    url: RUTA_DATA_MERCADERIAS,
    getValue: function (element) {
      if (element == undefined) {
        return "";
      }
      else {
        if (element.EstadoProducto == ESTADO_PRODUCTO.VISIBLE) {
          if ($('#contenedor_reporte_de_movimiento_documento_dua').hasClass('active') || $('#contenedor_reporte_de_stock_producto_por_dua').hasClass('active')) {
            if (element.IdOrigenMercaderia == ORIGEN_MERCADERIA.DUA) {
              return element.CodigoMercaderia + ' - ' + element.NombreProducto;
            }
            else {
              return "";
            }
          }
          else if ($('#contenedor_reporte_de_movimiento_documento_zofra').hasClass('active') || $('#contenedor_reporte_de_stock_producto_por_documento_zofra').hasClass('active')) {
            if (element.IdOrigenMercaderia == ORIGEN_MERCADERIA.ZOFRA) {
              return element.CodigoMercaderia + ' - ' + element.NombreProducto;
            }
            else {
              return "";
            }
          }
          else {
            return element.CodigoMercaderia + ' - ' + element.NombreProducto;
          }

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

        if ($('#contenedor_reporte_de_stock_producto').hasClass('active')) {
          $('#TextoBuscar_StockProducto').val(elemento.CodigoMercaderia + ' - ' + elemento.NombreProducto);
          $('#IdProducto_StockProducto').val(elemento.IdProducto);
        }
        else if ($('#contenedor_reporte_de_stock_productolote').hasClass('active')) {
          $('#TextoBuscar_StockProductoLote').val(elemento.CodigoMercaderia + ' - ' + elemento.NombreProducto);
          $('#IdProducto_StockProductoLote').val(elemento.IdProducto);
        }
        else if ($('#contenedor_reporte_de_stock_producto_por_marca').hasClass('active')) {
          $('#TextoBuscar_StockProductoMarca').val(elemento.CodigoMercaderia + ' - ' + elemento.NombreProducto);
          $('#IdProducto_StockProductoMarca').val(elemento.IdProducto);
        }
        else if ($('#contenedor_reporte_de_movimiento_almacen_valorado').hasClass('active')) {
          $('#TextoBuscar_MovimientoAlmacenValorado').val(elemento.CodigoMercaderia + ' - ' + elemento.NombreProducto);
          $('#IdProducto_MovimientoAlmacenValorado').val(elemento.IdProducto);
        }
        else if ($('#contenedor_reporte_de_movimiento_mercaderia').hasClass('active')) {
          $('#TextoBuscar_MovimientoMercaderia').val(elemento.CodigoMercaderia + ' - ' + elemento.NombreProducto);
          $('#IdProducto_MovimientoMercaderia').val(elemento.IdProducto);
        }
        else if ($('#contenedor_reporte_de_stock_producto_por_dua').hasClass('active')) {
          $('#TextoBuscar_StockProductoDua').val(elemento.CodigoMercaderia + ' - ' + elemento.NombreProducto);
          $('#IdProducto_StockProductoDua').val(elemento.IdProducto);
        }
        else if ($('#contenedor_reporte_de_movimiento_documento_dua').hasClass('active')) {
          $('#TextoBuscar_MovimientoDocumentoDua').val(elemento.CodigoMercaderia + ' - ' + elemento.NombreProducto);
          $('#IdProducto_MovimientoDocumentoDua').val(elemento.IdProducto);
        }
        else if ($('#contenedor_reporte_de_stock_producto_por_documento_zofra').hasClass('active')) {
          $('#TextoBuscar_StockProductoDocumentoZofra').val(elemento.CodigoMercaderia + ' - ' + elemento.NombreProducto);
          $('#IdProducto_StockProductoDocumentoZofra').val(elemento.IdProducto);
        }
        else if ($('#contenedor_reporte_de_movimiento_documento_zofra').hasClass('active')) {
          $('#TextoBuscar_MovimientoDocumentoZofra').val(elemento.CodigoMercaderia + ' - ' + elemento.NombreProducto);
          $('#IdProducto_MovimientoDocumentoZofra').val(elemento.IdProducto);
        }
        else if ($('#contenedor_reporte_de_movimiento_almacen_documento_de_ingreso').hasClass('active')) {
          $('#TextoBuscar_MovimientoAlmacenDocumentoIngreso').val(elemento.CodigoMercaderia + ' - ' + elemento.NombreProducto);
          $('#IdProducto_MovimientoAlmacenDocumentoIngreso').val(elemento.IdProducto);
        }
      },
    },
    theme: "square"
  };

  return this.options;
}
