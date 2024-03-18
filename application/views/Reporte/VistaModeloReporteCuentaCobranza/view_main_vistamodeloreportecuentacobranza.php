  <div class="main__cont">
    <div class="col-md-12 bhoechie-tab-container">
      <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 bhoechie-tab-menu">
        <div id="tab-general" class="list-group">
          <a href="#" rel="reporte_modelo_mivimiento_cuentas_por_cobrar" class="list-group-item active">Movim. de Cuentas por Cobrar</a>
          <a href="#" rel="reporte_detallado_cuentas_por_cobrar" class="list-group-item">Movim. Detallado de Cuentas por Cobrar</a>
          <a href="#" rel="reporte_documentos_por_cobrar" class="list-group-item">Documentos por Cobrar</a>
          <a href="#" rel="reporte_saldo_por_clientes" class="list-group-item">Saldos por Clientes</a>
          <a href="#" rel="reporte_deudas_cliente" class="list-group-item">Deudas por Clientes</a>
          <a href="#" rel="reporte_cobros_por_cobrador" class="list-group-item">Cobros por Cobrador</a>
        </div>
      </div>
      <div id="cont-general" class="col-lg-10 col-md-10 col-sm-10 col-xs-9 bhoechie-tab" style="padding: 0px;">
        <div id="contenedor_reporte_modelo_mivimiento_cuentas_por_cobrar" class="bhoechie-tab-content active"><?php echo $view_content_reportemodelomovimientocuentasporcobrar?></div>
        <div id="contenedor_reporte_detallado_cuentas_por_cobrar" class="bhoechie-tab-content"><?php echo $view_content_reportedetalladocuentasporcobrar?></div>
        <div id="contenedor_reporte_documentos_por_cobrar" class="bhoechie-tab-content"><?php echo $view_content_reportedocumentosporcobrar?></div>
        <div id="contenedor_reporte_saldo_por_clientes" class="bhoechie-tab-content"><?php echo $view_content_reportesaldoporclientes?></div>
        <div id="contenedor_reporte_deudas_cliente" class="bhoechie-tab-content"><?php echo $view_content_reportedeudascliente?></div>
        <div id="contenedor_reporte_cobros_por_cobrador" class="bhoechie-tab-content"><?php echo $view_content_reportecobrosporcobrador?></div>
      </div>
    </div>
  </div>
  <?php echo $view_mainpanel_subcontent_modal_reportevistaprevia; ?>
