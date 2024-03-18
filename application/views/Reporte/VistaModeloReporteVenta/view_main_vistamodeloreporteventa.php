<!-- ko with : vistaModeloReporte -->
<div class="main__scroll scrollbar-macosx">
  <div class="main__cont">
    <div class="col-md-12 bhoechie-tab-container">
      <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 bhoechie-tab-menu">
        <div id="tab-general" class="list-group">
          <a href="#" rel="reporte_de_venta_general" class="list-group-item active">Venta General</a>
          <!-- ko if:(parametros().ParametroLubricante == "1" ) -->
          <a href="#" rel="reporte_de_venta_general_lubricante" class="list-group-item">Venta General Lubricante</a>
          <!-- /ko -->
          <a href="#" rel="reporte_de_venta_detallado" class="list-group-item">Venta Detallado</a>
          <a href="#" rel="reporte_ventas_mensuales" class="list-group-item">Resumen Ventas Mensuales</a>
          <a href="#" rel="reporte_venta_diaria" class="list-group-item">Resumen Ventas Diarias</a>
          <a href="#" rel="reporte_productos_mas_vendidos" class="list-group-item">Productos más Vendidos</a>
          <a href="#" rel="reporte_familias_mas_vendidos" class="list-group-item">Familias más Vendidas</a>
          <a href="#" rel="reporte_marcas_mas_vendidos" class="list-group-item">Marcas más Vendidas</a>
          <a href="#" rel="reporte_ventas_por_vendedor" class="list-group-item">Ventas por Vendedor</a>
          <a href="#" rel="reporte_productos_por_familia" class="list-group-item">Productos por Familias</a>
          <a href="#" rel="reporte_ventas_por_mercaderia" class="list-group-item">Ventas por Producto</a>
          <a href="#" rel="reporte_ganancia_por_producto" class="list-group-item">Ganancia por Producto</a>
          <a href="#" rel="reporte_ganancia_por_vendedor" class="list-group-item">Ganancia por Vendedor</a>
          <a href="#" rel="reporte_ganancia_por_precio_base_producto" class="list-group-item">Ganancia por Precio Base de Producto</a>
          <a href="#" rel="reporte_saldo_al_cliente" class="list-group-item">Saldos por Cliente</a>
          <a href="#" rel="reporte_formato_14_1_ventas" class="list-group-item">Formato 14.1 Venta</a>
          <a href="#" rel="reporte_resumen_ventas" class="list-group-item">Resumen de Ventas</a>
          <a href="#" rel="reporte_remuneracion_empleados_meta_mensual" class="list-group-item">Remuneracion de Empleados por Meta Mensual</a>
          <a href="#" rel="reporte_lista_precios" class="list-group-item">Lista de Precios</a>
          <a href="#" rel="reporte_productos_por_familia_consolidado" class="list-group-item">Productos por Familias Consolidado</a>
        </div>
      </div>
      <div id="cont-general" class="col-lg-10 col-md-10 col-sm-10 col-xs-9 bhoechie-tab" style="padding: 0px;">
        <div id="contenedor_reporte_de_venta_general" class="bhoechie-tab-content active"><?php echo $view_content_ventageneral?></div>
        <!-- ko if:(parametros().ParametroLubricante == "1" ) -->
        <div id="contenedor_reporte_de_venta_general_lubricante" class="bhoechie-tab-content"><?php echo $view_content_ventagenerallubricante?></div>
        <!-- /ko -->
        <div id="contenedor_reporte_de_venta_detallado" class="bhoechie-tab-content"><?php echo $view_content_ventadetallado?></div>
        <div id="contenedor_reporte_ventas_mensuales" class="bhoechie-tab-content "><?php echo $view_content_ventasmensuales?></div>
        <div id="contenedor_reporte_venta_diaria" class="bhoechie-tab-content "><?php echo $view_content_ventadiaria?></div>
        <div id="contenedor_reporte_productos_mas_vendidos" class="bhoechie-tab-content "><?php echo $view_content_productosmasvendidos?></div>
        <div id="contenedor_reporte_familias_mas_vendidos" class="bhoechie-tab-content "><?php echo $view_content_familiasmasvendidos?></div>
        <div id="contenedor_reporte_marcas_mas_vendidos" class="bhoechie-tab-content "><?php echo $view_content_marcasmasvendidos?></div>
        <div id="contenedor_reporte_ventas_por_vendedor" class="bhoechie-tab-content "><?php echo $view_content_ventasporvendedor?></div>
        <div id="contenedor_reporte_productos_por_familia" class="bhoechie-tab-content "><?php echo $view_content_productosporfamilia?></div>
        <div id="contenedor_reporte_ventas_por_mercaderia" class="bhoechie-tab-content "><?php echo $view_content_ventaspormercaderia?></div>
        <div id="contenedor_reporte_ganancia_por_producto" class="bhoechie-tab-content "><?php echo $view_content_gananciaporproducto?></div>
        <div id="contenedor_reporte_ganancia_por_vendedor" class="bhoechie-tab-content "><?php echo $view_content_gananciaporvendedor?></div>
        <div id="contenedor_reporte_ganancia_por_precio_base_producto" class="bhoechie-tab-content "><?php echo $view_content_gananciaporpreciobaseproducto?></div>
        <div id="contenedor_reporte_saldo_al_cliente" class="bhoechie-tab-content "><?php echo $view_content_reportesaldocliente?></div>
        <div id="contenedor_reporte_formato_14_1_ventas" class="bhoechie-tab-content "><?php echo $view_content_Reporteformato14_1venta?></div>
        <div id="contenedor_reporte_resumen_ventas" class="bhoechie-tab-content "><?php echo $view_content_resumen_ventas?></div>
        <div id="contenedor_reporte_remuneracion_empleados_meta_mensual" class="bhoechie-tab-content "><?php echo $view_content_reporteremuneracionempleadosmetamensual?></div>
        <div id="contenedor_reporte_lista_precios" class="bhoechie-tab-content "><?php echo $view_content_reportelistaprecios?></div>
        <div id="contenedor_reporte_productos_por_familia_consolidado" class="bhoechie-tab-content "><?php echo $view_content_productosporfamiliaconsolidado?></div>
      </div>
    </div>
    <?php echo $view_mainpanel_subcontent_modal_reportevistaprevia; ?>
  </div>
</div>
<!-- /ko -->