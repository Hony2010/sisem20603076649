
$(document).ready(function (event) {

  $("#TextoBuscar_Vendedor").easyAutocomplete(new optionsAutoCompletadoVendedor("#TextoBuscar_Vendedor", window));
  $('#TextoBuscar_SaldoCliente').easyAutocomplete(new optionsAutoCompletadoCliente("#TextoBuscar_SaldoCliente", window));
  $("#TextoBuscar_R").easyAutocomplete(new optionsAutoCompletadoCliente("#TextoBuscar_R", window));
  $("#TextoBuscar_D").easyAutocomplete(new optionsAutoCompletadoCliente("#TextoBuscar_D", window));

  var mostrarCodigoProducto = 1; 

  var datafiltrosAutoCompletadoProducto1 = { id: "#TextoBuscar_Mercaderia", TipoVenta: TIPO_VENTA.MERCADERIAS, NombreLargoProducto: 0 };
  $(datafiltrosAutoCompletadoProducto1.id).autoCompletadoProducto(datafiltrosAutoCompletadoProducto1, event, function (elemento) {
    $(datafiltrosAutoCompletadoProducto1.id).val(elemento.CodigoMercaderia + ' - ' + elemento.NombreProducto);
    vistaModeloReporte.vmgReporteVentasPorMercaderia.dataReporteVentasPorMercaderia.Buscador.TextoMercaderia_Mercaderia(elemento.IdProducto);
  }, ORIGEN_MERCADERIA.TODOS, mostrarCodigoProducto);

  var datafiltrosAutoCompletadoProducto2 = { id: "#TextoBuscar_Gananciaporproducto", TipoVenta: TIPO_VENTA.MERCADERIAS, NombreLargoProducto: 0 };
  $(datafiltrosAutoCompletadoProducto2.id).autoCompletadoProducto(datafiltrosAutoCompletadoProducto2, event, function (elemento) {
    $(datafiltrosAutoCompletadoProducto2.id).val(elemento.CodigoMercaderia + ' - ' + elemento.NombreProducto);
    vistaModeloReporte.vmgReporteGananciaPorProducto.dataReporteGananciaPorProducto.Buscador.TextoMercaderia_Gananciaporproducto(elemento.IdProducto);
  }, ORIGEN_MERCADERIA.TODOS, mostrarCodigoProducto);

  var datafiltrosAutoCompletadoProducto3 = { id: "#TextoBuscar_GananciaPorPrecioBaseProducto", TipoVenta: TIPO_VENTA.MERCADERIAS, NombreLargoProducto: 0 };
  $(datafiltrosAutoCompletadoProducto3.id).autoCompletadoProducto(datafiltrosAutoCompletadoProducto3, event, function (elemento) {
    $(datafiltrosAutoCompletadoProducto3.id).val(elemento.CodigoMercaderia + ' - ' + elemento.NombreProducto);
    vistaModeloReporte.vmgReporteGananciaPorPrecioBaseProducto.dataReporteGananciaPorPrecioBaseProducto.Buscador.TextoMercaderia_GananciaPorPrecioBaseProducto(elemento.IdProducto);
  }, ORIGEN_MERCADERIA.TODOS, mostrarCodigoProducto);

  var datafiltrosAutoCompletadoProducto4 = { id: "#TextoBuscar_Gananciaporvendedor", TipoVenta: TIPO_VENTA.MERCADERIAS, NombreLargoProducto: 0 };
  $(datafiltrosAutoCompletadoProducto4.id).autoCompletadoProducto(datafiltrosAutoCompletadoProducto4, event, function (elemento) {
    $(datafiltrosAutoCompletadoProducto4.id).val(elemento.CodigoMercaderia + ' - ' + elemento.NombreProducto);
    vistaModeloReporte.vmgReporteGananciaPorVendedor.dataReporteGananciaPorVendedor.Buscador.TextoMercaderia_Gananciaporvendedor(elemento.IdProducto);
  }, ORIGEN_MERCADERIA.TODOS, mostrarCodigoProducto);


  // $("#TextoBuscar_Gananciaporproducto").easyAutocomplete(new optionsAutoCompletadoProducto("#TextoBuscar_Gananciaporproducto", window));
  // $("#TextoBuscar_GananciaPorPrecioBaseProducto").easyAutocomplete(new optionsAutoCompletadoProducto("#TextoBuscar_GananciaPorPrecioBaseProducto", window));
  // $("#TextoBuscar_Gananciaporvendedor").easyAutocomplete(new optionsAutoCompletadoProducto("#TextoBuscar_Gananciaporvendedor", window));

  $(".fecha-reporte").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });
  $(".año-reporte").inputmask({ "mask": "9999", positionCaretOnTab: false });
  $(".hora-reporte").inputmask({ "mask": "99:99", positionCaretOnTab: false });

  $("#SelectorTipoDocumentos_D").click()
  $("#SelectorTipoDocumentos_R").click()
  $("#SelectorTipoDocumentos_RL").click()
  $("#SelectorTipoDocumentos_14").click()
  $("#SelectorVendedores").click()
  $("#SelectorVendedoresMetaMesual").click()


  $('body').keydown(function () {
    var code = event.keyCode;
    if (code == 88) {
      if ($("#contenedor_reporte_de_venta_general").hasClass('active')) {
        $("#btnexcel_R").click()
      }
      else if ($("#contenedor_reporte_de_venta_detallado").hasClass('active')) {
        $("#btnexcel_D").click()
      }
      else if ($("#contenedor_reporte_ventas_mensuales").hasClass('active')) {
        $("#btnexcel_Mensual").click()
      }
      else if ($("#contenedor_reporte_venta_diaria").hasClass('active')) {
        $("#btnexcel_Diario").click()
      }
      else if ($("#contenedor_reporte_productos_mas_vendidos").hasClass('active')) {
        $("#btnexcel_MAS").click()
      }
      else if ($("#contenedor_reporte_familias_mas_vendidos").hasClass('active')) {
        $("#btnexcel_Familia").click()
      }
      else if ($("#contenedor_reporte_marcas_mas_vendidos").hasClass('active')) {
        $("#btnexcel_Marca").click()
      }
      else if ($("#contenedor_reporte_ventas_por_vendedor").hasClass('active')) {
        $("#btnexcel_Vendedor").click()
      }
      else if ($("#contenedor_reporte_productos_por_familia").hasClass('active')) {
        $("#btnexcel_PF").click()
      }
      else if ($("#contenedor_reporte_ventas_por_mercaderia").hasClass('active')) {
        $("#btnexcel_Mercaderia").click()
      }
      else if ($("#contenedor_reporte_ganancia_por_producto").hasClass('active')) {
        $("#btnexcel_Gananciaporproducto").click()
      }
      else if ($("#contenedor_reporte_ganancia_por_vendedor").hasClass('active')) {
        $("#btnexcel_Gananciaporvendedor").click()
      }
      else if ($("#contenedor_reporte_ganancia_por_precio_base_producto").hasClass('active')) {
        $("#btnexcel_GananciaPorPrecioBaseProducto").click()
      }
      else if ($("#contenedor_reporte_saldo_al_cliente").hasClass('active')) {
        $("#btnexcel_SaldoCliente").click()
      }
      else if ($("#contenedor_reporte_formato_14_1_ventas").hasClass('active')) {
        $("#btnexcel_Formato14").click()
      }
    }
  });

  // INICIALIZAR VISTA MODELO
  vistaModeloReporte.vmgReporteListaPrecios.Inicializar();

});
