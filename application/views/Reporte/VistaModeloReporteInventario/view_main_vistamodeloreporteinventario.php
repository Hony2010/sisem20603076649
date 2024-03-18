<!-- ko with : VistaModeloReporteInventario -->
<div class="main__scroll scrollbar-macosx">
  <div class="main__cont">
    <div class="col-md-12 bhoechie-tab-container">
      <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 bhoechie-tab-menu">
        <div id="tab-general" class="list-group">
          <a href="#" rel="reporte_de_stock_producto" class="list-group-item active">Stock de Producto</a>
          <!-- ko if:(parametros().ParametroLote == "1" ) -->
          <a href="#" rel="reporte_de_stock_productolote" class="list-group-item">Stock de Producto por Lote</a>
          <!-- /ko -->
          <a href="#" rel="reporte_de_stock_producto_por_marca" class="list-group-item">Stock de Producto por Marca</a>
          <a href="#" rel="reporte_de_movimiento_mercaderia" class="list-group-item">Movimiento Kardex Físico</a>
          <a href="#" rel="reporte_de_movimiento_almacen_valorado" class="list-group-item">Movimiento Kardex Físico - Valorado</a>
          <a href="#" rel="reporte_de_inventario" class="list-group-item">Inventario Inicial</a>
          <!-- ko if:(parametros().ParametroDua == "1" ) -->
          <a href="#" rel="reporte_de_stock_producto_por_dua" class="list-group-item">Stock de Producto por DUA</a>
          <!-- /ko -->
          <!-- ko if:(parametros().ParametroDua == "1" ) -->
          <a href="#" rel="reporte_de_movimiento_documento_dua" class="list-group-item">Movimiento Documento por DUA</a>
          <!-- /ko -->
          <!-- ko if:(parametros().ParametroZofra == "1" ) -->
          <a href="#" rel="reporte_de_stock_producto_por_documento_zofra" class="list-group-item">Stock de Producto por Zofra</a>
          <!-- /ko -->
          <!-- ko if:(parametros().ParametroZofra == "1" ) -->
          <a href="#" rel="reporte_de_movimiento_documento_zofra" class="list-group-item">Movimiento Documento por Zofra</a>
          <!-- /ko -->
          <!-- ko if:(parametros().ParametroDocumentoIngreso == "1" ) -->
          <a href="#" rel="reporte_de_documento_de_ingreso" class="list-group-item"> Saldos Documento de Ingreso / Control</a>
          <!-- /ko -->
          <!-- ko if:(parametros().ParametroDocumentoIngreso == "1" ) -->
          <a href="#" rel="reporte_de_movimiento_almacen_documento_de_ingreso" class="list-group-item">Movimiento Kardex Físico Documento Ingreso / Control</a>
          <!-- /ko -->
        </div>
      </div>
      <div id="cont-general" class="col-lg-10 col-md-10 col-sm-10 col-xs-9 bhoechie-tab" style="padding: 0px;">
        <div id="contenedor_reporte_de_stock_producto" class="bhoechie-tab-content active"><?php echo $view_content_stockproducto?></div>
        <!-- ko if:(parametros().ParametroLote == "1" ) -->
        <div id="contenedor_reporte_de_stock_productolote" class="bhoechie-tab-content"><?php echo $view_content_stockproductolote?></div>
        <!-- /ko -->
        <div id="contenedor_reporte_de_stock_producto_por_marca" class="bhoechie-tab-content "><?php echo $view_content_stockproductomarca?></div>
        <div id="contenedor_reporte_de_movimiento_mercaderia" class="bhoechie-tab-content "><?php echo $view_content_movimientomercaderia?></div>
        <div id="contenedor_reporte_de_movimiento_almacen_valorado" class="bhoechie-tab-content "><?php echo $view_content_movimientoalmacenvalorado?></div>
        <div id="contenedor_reporte_de_inventario" class="bhoechie-tab-content "><?php echo $view_content_reporteinventario?></div>
        <!-- ko if:(parametros().ParametroDua == "1" ) -->
        <div id="contenedor_reporte_de_stock_producto_por_dua" class="bhoechie-tab-content "><?php echo $view_content_stockproductodua?></div>
        <!-- /ko -->
        <!-- ko if:(parametros().ParametroDua == "1" ) -->
        <div id="contenedor_reporte_de_movimiento_documento_dua" class="bhoechie-tab-content "><?php echo $view_content_movimientodocumentodua?></div>
        <!-- /ko -->
        <!-- ko if:(parametros().ParametroZofra == "1" ) -->
        <div id="contenedor_reporte_de_stock_producto_por_documento_zofra" class="bhoechie-tab-content "><?php echo $view_content_stockproductodocumentozofra?></div>
        <!-- /ko -->
        <!-- ko if:(parametros().ParametroZofra == "1" ) -->
        <div id="contenedor_reporte_de_movimiento_documento_zofra" class="bhoechie-tab-content "><?php echo $view_content_movimientodocumentozofra?></div>
        <!-- /ko -->
        <!-- ko if:((parametros().ParametroDocumentoIngreso == "1" ) -->
        <div id="contenedor_reporte_de_documento_de_ingreso" class="bhoechie-tab-content "><?php echo $view_content_reportedocumentoingreso?></div>
        <!-- /ko -->
        <!-- ko if:((parametros().ParametroDocumentoIngreso == "1" ) -->
        <div id="contenedor_reporte_de_movimiento_almacen_documento_de_ingreso" class="bhoechie-tab-content "><?php echo $view_content_movimientoalmacendocumentoingreso?></div>

        <!-- /ko -->
      </div>
    </div>
    <?php echo $view_mainpanel_subcontent_modal_reportevistaprevia; ?>
  </div>
</div>
<!-- /ko -->
