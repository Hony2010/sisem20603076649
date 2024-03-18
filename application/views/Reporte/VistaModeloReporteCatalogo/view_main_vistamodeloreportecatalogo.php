<div class="main__scroll scrollbar-macosx">
  <div class="main__cont">
    <div class="col-md-12 bhoechie-tab-container">
      <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 bhoechie-tab-menu">
        <div id="tab-general" class="list-group">
          <a href="#" rel="reporte_de_lista_clientes" class="list-group-item active">Listado de Clientes</a>
          <a href="#" rel="reporte_de_lista_mercaderias" class="list-group-item">Listado de Mercaderías</a>
          <a href="#" rel="reporte_de_lista_activosfijos" class="list-group-item">Listado de Activos Fijos</a>
          <a href="#" rel="reporte_de_lista_gastos" class="list-group-item">Listado de Gastos</a>
          <a href="#" rel="reporte_de_lista_empleados" class="list-group-item">Listado de Empleados</a>
          <a href="#" rel="reporte_de_lista_proveedores" class="list-group-item">Listado de Proveedores</a>
          <a href="#" rel="reporte_de_lista_costos_agregados" class="list-group-item">Listado de Costos Agregados</a>
          <a href="#" rel="reporte_de_lista_otras_ventas" class="list-group-item">Listado de Otras Ventas</a>
          <a href="#" rel="reporte_de_lista_familas_subfamilias" class="list-group-item">Listado de Familias y SubFamilias</a>
          <a href="#" rel="reporte_de_lista_marcas_modelos" class="list-group-item">Listado de Marcas y Modelos</a>
          <a href="#" rel="reporte_de_clientes_por_zona" class="list-group-item">Clientes por Zona</a>
        </div>
      </div>
      <div id="cont-general" class="col-lg-10 col-md-10 col-sm-10 col-xs-9 bhoechie-tab" style="padding: 0px;">
        <div id="contenedor_reporte_de_lista_clientes" class="bhoechie-tab-content active"><?php echo $view_content_listaclientes?></div>
        <div id="contenedor_reporte_de_lista_mercaderias" class="bhoechie-tab-content "><?php echo $view_content_listamercaderias?></div>
        <div id="contenedor_reporte_de_lista_activosfijos" class="bhoechie-tab-content "><?php echo $view_content_listaactivosfijos?></div>
        <div id="contenedor_reporte_de_lista_gastos" class="bhoechie-tab-content "><?php echo $view_content_listagastos?></div>
        <div id="contenedor_reporte_de_lista_empleados" class="bhoechie-tab-content "><?php echo $view_content_listaempleados?></div>
        <div id="contenedor_reporte_de_lista_proveedores" class="bhoechie-tab-content "><?php echo $view_content_listaproveedores?></div>
        <div id="contenedor_reporte_de_lista_costos_agregados" class="bhoechie-tab-content "><?php echo $view_content_listacostosagregados?></div>
        <div id="contenedor_reporte_de_lista_otras_ventas" class="bhoechie-tab-content "><?php echo $view_content_listaotrasventas?></div>
        <div id="contenedor_reporte_de_lista_familias_subfamilias" class="bhoechie-tab-content "><?php echo $view_content_listafamiliassubfamilias?></div>
        <div id="contenedor_reporte_de_lista_marcas_modelos" class="bhoechie-tab-content "><?php echo $view_content_listamarcasmodelos?></div>
        <div id="contenedor_reporte_de_clientes_por_zona" class="bhoechie-tab-content "><?php echo $view_content_clientesporzona?></div>
      </div>
    </div>
    <?php echo $view_mainpanel_subcontent_modal_reportevistaprevia; ?>
  </div>
</div>
