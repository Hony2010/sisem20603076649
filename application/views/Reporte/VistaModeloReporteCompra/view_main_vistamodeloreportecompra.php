<!-- ko with : vistaModeloReporte -->
<div class="main__scroll scrollbar-macosx">
  <div class="main__cont">
    <div class="col-md-12 bhoechie-tab-container">
      <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 bhoechie-tab-menu">
        <div id="tab-general" class="list-group">
          <a href="#" rel="reporte_de_compra_general" class="list-group-item active">Compra General</a>
          <a href="#" rel="reporte_de_compra_detallado" class="list-group-item">Compra Detallado</a>
          <a href="#" rel="reporte_productos_mas_comprados" class="list-group-item">Productos más Comprados</a>
          <a href="#" rel="reporte_compras_mensuales" class="list-group-item">Resumen de Compras Mensuales</a>
          <a href="#" rel="reporte_compras_por_mercaderia" class="list-group-item">Compras por Mercadería</a>
          <!-- ko if:(parametros().ParametroCodigoProductoProveedor == "1" ) -->
          <a href="#" rel="reporte_producto_por_proveedor" class="list-group-item">Producto por Proveedor</a>
          <!-- /ko -->
          <a href="#" rel="reporte_saldo_al_proveedor" class="list-group-item">Saldos por Proveedor</a>
          <a href="#" rel="reporte_formato_8_1_compra" class="list-group-item">Formato 8.1</a>
        </div>
      </div>
      <div id="cont-general" class="col-lg-10 col-md-10 col-sm-10 col-xs-9 bhoechie-tab" style="padding: 0px;">
        <div id="contenedor_reporte_de_compra_general" class="bhoechie-tab-content active"><?php echo $view_content_comprageneral?></div>
        <div id="contenedor_reporte_de_compra_detallado" class="bhoechie-tab-content "><?php echo $view_content_compradetallado?></div>
        <div id="contenedor_reporte_productos_mas_comprados" class="bhoechie-tab-content "><?php echo $view_content_productosmascomprados?></div>
        <div id="contenedor_reporte_compras_mensuales" class="bhoechie-tab-content "><?php echo $view_content_comprasmensuales?></div>
        <div id="contenedor_reporte_compras_por_mercaderia" class="bhoechie-tab-content "><?php echo $view_content_compraspormercaderia?></div>
        <!-- ko if:(parametros().ParametroCodigoProductoProveedor == "1" ) -->
        <div id="contenedor_reporte_producto_por_proveedor" class="bhoechie-tab-content "><?php echo $view_content_productoporproveedor?></div>
        <!-- /ko -->
        <div id="contenedor_reporte_saldo_al_proveedor" class="bhoechie-tab-content "><?php echo $view_content_reportesaldoproveedor?></div>
        <div id="contenedor_reporte_formato_8_1_compra" class="bhoechie-tab-content "><?php echo $view_content_formato8_1compra?></div>
      </div>
    </div>
    <?php echo $view_mainpanel_subcontent_modal_reportevistaprevia; ?>
  </div>
</div>
<!-- /ko -->
