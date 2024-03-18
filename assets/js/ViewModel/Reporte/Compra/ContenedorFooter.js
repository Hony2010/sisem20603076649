
$(document).ready(function (event) {

  $("#TextoBuscar_General").easyAutocomplete(new optionsAutoCompletadoProveedor("#TextoBuscar_General", window));
  $('#TextoBuscar_General').hide();
  $("#TextoBuscar_Detallado").easyAutocomplete(new optionsAutoCompletadoProveedor("#TextoBuscar_Detallado", window));
  $('#TextoBuscar_Detallado').hide();
  $("#TextoBuscar_Vendedor").easyAutocomplete(new optionsAutoCompletadoVendedor("#TextoBuscar_Vendedor", window));
  $('#TextoBuscar_Vendedor').hide();
  $('#TextoBuscarProveedor_ProductoProveedor').easyAutocomplete(new optionsAutoCompletadoProveedor("#TextoBuscarProveedor_ProductoProveedor", window));
  $('#TextoBuscarProveedor_ProductoProveedor').hide();
  $('#TextoBuscar_SaldoProveedor').easyAutocomplete(new optionsAutoCompletadoProveedor("#TextoBuscar_SaldoProveedor", window));
  $('#TextoBuscar_SaldoProveedor').hide();

  var mostrarCodigoProducto = 1; 

  var datafiltrosAutoCompletadoProducto1 = { id: "#TextoBuscar_Mercaderia", TipoVenta: TIPO_VENTA.MERCADERIAS, NombreLargoProducto: 0 };
  $(datafiltrosAutoCompletadoProducto1.id).autoCompletadoProducto(datafiltrosAutoCompletadoProducto1, event, function (elemento) {
    $(datafiltrosAutoCompletadoProducto1.id).val(elemento.CodigoMercaderia + ' - ' + elemento.NombreProducto);
    vistaModeloReporte.vmgReporteComprasPorMercaderia.dataReporteComprasPorMercaderia.Buscador.TextoMercaderia_Mercaderia(elemento.IdProducto);
  }, ORIGEN_MERCADERIA.TODOS, mostrarCodigoProducto);
  $(datafiltrosAutoCompletadoProducto1.id).hide();

  var datafiltrosAutoCompletadoProducto2 = { id: "#TextoBuscarMercaderia_ProductoProveedor", TipoVenta: TIPO_VENTA.MERCADERIAS, NombreLargoProducto: 0 };
  $(datafiltrosAutoCompletadoProducto2.id).autoCompletadoProducto(datafiltrosAutoCompletadoProducto2, event, function (elemento) {
    $(datafiltrosAutoCompletadoProducto2.id).val(elemento.CodigoMercaderia + ' - ' + elemento.NombreProducto);
    vistaModeloReporte.vmgReporteProductoPorProveedor.dataReporteProductoPorProveedor.Buscador.TextoMercaderia_ProductoProveedor(elemento.IdProducto);
  }, ORIGEN_MERCADERIA.TODOS, mostrarCodigoProducto);
  $(datafiltrosAutoCompletadoProducto2.id).hide();

  $(".fecha-reporte").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });
});
