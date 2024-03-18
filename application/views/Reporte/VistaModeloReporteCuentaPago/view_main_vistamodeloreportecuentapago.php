  <div class="main__cont">
    <div class="col-md-12 bhoechie-tab-container">
      <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 bhoechie-tab-menu">
        <div id="tab-general" class="list-group">
          <a href="#" rel="reporte_modelo_mivimiento_cuentas_por_pagar" class="list-group-item active">Movim. de Cuentas por Pagar</a>
          <a href="#" rel="reporte_detallado_cuentas_por_pagar" class="list-group-item">Movim. Detallado de Cuentas por Pagar</a>
          <a href="#" rel="reporte_documentos_por_pagar" class="list-group-item">Documentos por Pagar</a>
          <a href="#" rel="reporte_saldo_por_proveedor" class="list-group-item">Saldos por Proveedor</a>
        </div>
      </div>
      <div id="cont-general" class="col-lg-10 col-md-10 col-sm-10 col-xs-9 bhoechie-tab" style="padding: 0px;">
        <div id="contenedor_reporte_modelo_mivimiento_cuentas_por_pagar" class="bhoechie-tab-content active"><?php echo $view_content_reportemodelomovimientocuentasporpagar?></div>
        <div id="contenedor_reporte_detallado_cuentas_por_pagar" class="bhoechie-tab-content"><?php echo $view_content_reportedetalladocuentasporpagar?></div>
        <div id="contenedor_reporte_documentos_por_pagar" class="bhoechie-tab-content"><?php echo $view_content_reportedocumentosporpagar?></div>
        <div id="contenedor_reporte_saldo_por_proveedor" class="bhoechie-tab-content"><?php echo $view_content_reportesaldoporproveedor?></div>
      </div>
    </div>
  </div>
  <?php echo $view_mainpanel_subcontent_modal_reportevistaprevia; ?>
