
$(document).ready(function (event) {

  var mostrarCodigoProducto = 1; 
  var datafiltrosAutoCompletadoProducto = { id: "#TextoBuscar_Mercaderia", TipoVenta: TIPO_VENTA.MERCADERIAS, NombreLargoProducto: 0 };
  datafiltrosAutoCompletadoProducto.id = "#TextoBuscar_StockProducto";
  $(datafiltrosAutoCompletadoProducto.id).autoCompletadoProducto(datafiltrosAutoCompletadoProducto, event, function (elemento, $event) {
    $('#TextoBuscar_StockProducto').val(elemento.CodigoMercaderia + ' - ' + elemento.NombreProducto);
    $('#IdProducto_StockProducto').val(elemento.IdProducto);
  }, ORIGEN_MERCADERIA.TODOS, mostrarCodigoProducto);
  $('#TextoBuscar_StockProducto').hide();

  //$("#TextoBuscar_StockProductoMarca").easyAutocomplete(new optionsAutoCompletadoProducto("#TextoBuscar_StockProductoMarca",window));
  var datafiltrosAutoCompletadoProducto2 = { id: "#TextoBuscar_StockProductoMarca", TipoVenta: TIPO_VENTA.MERCADERIAS, NombreLargoProducto: 0 };
  $(datafiltrosAutoCompletadoProducto2.id).autoCompletadoProducto(datafiltrosAutoCompletadoProducto2, event, function (elemento) {
    $('#TextoBuscar_StockProductoMarca').val(elemento.CodigoMercaderia + ' - ' + elemento.NombreProducto);
    $('#IdProducto_StockProductoMarca').val(elemento.IdProducto);
  }, ORIGEN_MERCADERIA.TODOS, mostrarCodigoProducto);
  $('#TextoBuscar_StockProductoMarca').hide();

  //$("#TextoBuscar_StockProductoLote").easyAutocomplete(new optionsAutoCompletadoProducto("#TextoBuscar_StockProductoLote",window));
  var datafiltrosAutoCompletadoProducto3 = { id: "#TextoBuscar_StockProductoLote", TipoVenta: TIPO_VENTA.MERCADERIAS, NombreLargoProducto: 0 };
  $(datafiltrosAutoCompletadoProducto3.id).autoCompletadoProducto(datafiltrosAutoCompletadoProducto3, event, function (elemento) {
    $('#TextoBuscar_StockProductoLote').val(elemento.CodigoMercaderia + ' - ' + elemento.NombreProducto);
    $('#IdProducto_StockProductoLote').val(elemento.IdProducto);
  }, ORIGEN_MERCADERIA.TODOS, mostrarCodigoProducto);
  $('#TextoBuscar_StockProductoLote').hide();

  //$("#TextoBuscar_StockNegativo").easyAutocomplete(new optionsAutoCompletadoProducto("#TextoBuscar_StockNegativo",window));
  var datafiltrosAutoCompletadoProducto4 = { id: "#TextoBuscar_StockNegativo", TipoVenta: TIPO_VENTA.MERCADERIAS, NombreLargoProducto: 0 };
  $(datafiltrosAutoCompletadoProducto4.id).autoCompletadoProducto(datafiltrosAutoCompletadoProducto4, event, function (elemento) { }, ORIGEN_MERCADERIA.TODOS, mostrarCodigoProducto);
  $('#TextoBuscar_StockNegativo').hide();

  //$("#TextoBuscar_MovimientoAlmacenValorado").easyAutocomplete(new optionsAutoCompletadoProducto("#TextoBuscar_MovimientoAlmacenValorado",window));
  var datafiltrosAutoCompletadoProducto5 = { id: "#TextoBuscar_MovimientoAlmacenValorado", TipoVenta: TIPO_VENTA.MERCADERIAS, NombreLargoProducto: 0 };
  $(datafiltrosAutoCompletadoProducto5.id).autoCompletadoProducto(datafiltrosAutoCompletadoProducto5, event, function (elemento) {
    $('#TextoBuscar_MovimientoAlmacenValorado').val(elemento.CodigoMercaderia + ' - ' + elemento.NombreProducto);
    $('#IdProducto_MovimientoAlmacenValorado').val(elemento.IdProducto);
  }, ORIGEN_MERCADERIA.TODOS, mostrarCodigoProducto);
  $('#TextoBuscar_MovimientoAlmacenValorado').hide();

  //$("#TextoBuscar_MovimientoMercaderia").easyAutocomplete(new optionsAutoCompletadoProducto("#TextoBuscar_MovimientoMercaderia",window));
  var datafiltrosAutoCompletadoProducto6 = { id: "#TextoBuscar_MovimientoMercaderia", TipoVenta: TIPO_VENTA.MERCADERIAS, NombreLargoProducto: 0 };
  $(datafiltrosAutoCompletadoProducto6.id).autoCompletadoProducto(datafiltrosAutoCompletadoProducto6, event, function (elemento) {
    $('#TextoBuscar_MovimientoMercaderia').val(elemento.CodigoMercaderia + ' - ' + elemento.NombreProducto);
    $('#IdProducto_MovimientoMercaderia').val(elemento.IdProducto);
  }, ORIGEN_MERCADERIA.TODOS, mostrarCodigoProducto);
  $('#TextoBuscar_MovimientoMercaderia').hide();

  $('#Item_StockProductoDua').easyAutocomplete(new optionsAutoCompletadoDuaProducto("#Item_StockProductoDua", window));
  $('#Item_StockProductoDua').hide();

  //$("#TextoBuscar_StockProductoDua").easyAutocomplete(new optionsAutoCompletadoProducto("#TextoBuscar_StockProductoDua",window));  
  var datafiltrosAutoCompletadoProducto7 = { id: "#TextoBuscar_StockProductoDua", TipoVenta: TIPO_VENTA.MERCADERIAS, NombreLargoProducto: 0 };
  $(datafiltrosAutoCompletadoProducto7.id).autoCompletadoProducto(datafiltrosAutoCompletadoProducto7, event, function (elemento) {
    $('#TextoBuscar_StockProductoDua').val(elemento.CodigoMercaderia + ' - ' + elemento.NombreProducto);
    $('#IdProducto_StockProductoDua').val(elemento.IdProducto);
  }, ORIGEN_MERCADERIA.TODOS, mostrarCodigoProducto);
  $('#TextoBuscar_StockProductoDua').hide();

  $('#Item_MovimientoDocumentoDua').easyAutocomplete(new optionsAutoCompletadoDuaProducto("#Item_MovimientoDocumentoDua", window));
  $('#Item_MovimientoDocumentoDua').hide();

  //$("#TextoBuscar_MovimientoDocumentoDua").easyAutocomplete(new optionsAutoCompletadoProducto("#TextoBuscar_MovimientoDocumentoDua",window));
  var datafiltrosAutoCompletadoProducto8 = { id: "#TextoBuscar_MovimientoDocumentoDua", TipoVenta: TIPO_VENTA.MERCADERIAS, NombreLargoProducto: 0 };
  $(datafiltrosAutoCompletadoProducto8.id).autoCompletadoProducto(datafiltrosAutoCompletadoProducto8, event, function (elemento) {
    $('#TextoBuscar_MovimientoDocumentoDua').val(elemento.CodigoMercaderia + ' - ' + elemento.NombreProducto);
    $('#IdProducto_MovimientoDocumentoDua').val(elemento.IdProducto);
  }, ORIGEN_MERCADERIA.TODOS, mostrarCodigoProducto);
  $('#TextoBuscar_MovimientoDocumentoDua').hide();

  $('#Item_StockProductoDocumentoZofra').easyAutocomplete(new optionsAutoCompletadoDocumentoZofra("#Item_StockProductoDocumentoZofra", window));
  $('#Item_StockProductoDocumentoZofra').hide();

  //$("#TextoBuscar_StockProductoDocumentoZofra").easyAutocomplete(new optionsAutoCompletadoProducto("#TextoBuscar_StockProductoDocumentoZofra",window));
  var datafiltrosAutoCompletadoProducto9 = { id: "#TextoBuscar_StockProductoDocumentoZofra", TipoVenta: TIPO_VENTA.MERCADERIAS, NombreLargoProducto: 0 };
  $(datafiltrosAutoCompletadoProducto9.id).autoCompletadoProducto(datafiltrosAutoCompletadoProducto9, event, function (elemento) {
    $('#TextoBuscar_StockProductoDocumentoZofra').val(elemento.CodigoMercaderia + ' - ' + elemento.NombreProducto);
    $('#IdProducto_StockProductoDocumentoZofra').val(elemento.IdProducto);
  }, ORIGEN_MERCADERIA.TODOS, mostrarCodigoProducto);
  $('#TextoBuscar_StockProductoDocumentoZofra').hide();


  $('#Item_MovimientoDocumentoZofra').easyAutocomplete(new optionsAutoCompletadoDocumentoZofra("#Item_MovimientoDocumentoZofra", window));
  $('#Item_MovimientoDocumentoZofra').hide();

  //$("#TextoBuscar_MovimientoDocumentoZofra").easyAutocomplete(new optionsAutoCompletadoProducto("#TextoBuscar_MovimientoDocumentoZofra",window));  
  var datafiltrosAutoCompletadoProducto10 = { id: "#TextoBuscar_MovimientoDocumentoZofra", TipoVenta: TIPO_VENTA.MERCADERIAS, NombreLargoProducto: 0 };
  $(datafiltrosAutoCompletadoProducto10.id).autoCompletadoProducto(datafiltrosAutoCompletadoProducto10, event, function (elemento) {
    $('#TextoBuscar_MovimientoDocumentoZofra').val(elemento.CodigoMercaderia + ' - ' + elemento.NombreProducto);
    $('#IdProducto_MovimientoDocumentoZofra').val(elemento.IdProducto);
  }, ORIGEN_MERCADERIA.TODOS, mostrarCodigoProducto);
  $('#TextoBuscar_MovimientoDocumentoZofra').hide();

  $('#Item_DocumentoIngreso').easyAutocomplete(new optionsAutoCompletadoDocumentoIngreso("#Item_DocumentoIngreso", window));
  $('#Item_DocumentoIngreso').hide();

  //$('#TextoBuscar_MovimientoAlmacenDocumentoIngreso').easyAutocomplete(new optionsAutoCompletadoProducto("#TextoBuscar_MovimientoAlmacenDocumentoIngreso",window));  
  var datafiltrosAutoCompletadoProducto11 = { id: "#TextoBuscar_MovimientoAlmacenDocumentoIngreso", TipoVenta: TIPO_VENTA.MERCADERIAS, NombreLargoProducto: 0 };
  $(datafiltrosAutoCompletadoProducto11.id).autoCompletadoProducto(datafiltrosAutoCompletadoProducto11, event, function (elemento) {
    $('#TextoBuscar_MovimientoAlmacenDocumentoIngreso').val(elemento.CodigoMercaderia + ' - ' + elemento.NombreProducto);
    $('#IdProducto_MovimientoAlmacenDocumentoIngreso').val(elemento.IdProducto);
  }, ORIGEN_MERCADERIA.TODOS, mostrarCodigoProducto);
  $('#TextoBuscar_MovimientoAlmacenDocumentoIngreso').hide();

  $(".fecha-reporte").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });

  $("#SelectorTipoDocumentos_StockProducto").click()
  $("#SelectorTipoDocumentos_StockProductoLote").click()
  $("#SelectorTipoDocumentos_StockProductoMarca").click()
  $("#SelectorTipoDocumentos_MovimientoMercaderia").click()
  $("#SelectorTipoDocumentos_MovimientoAlmacenValorado").click()
});
