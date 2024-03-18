<div class="main__scroll scrollbar-macosx">
  <div class="main__cont">
    <div class="col-md-12 bhoechie-tab-container">
      <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 bhoechie-tab-menu">
        <div id="tab-general" class="list-group">
          <a href="#" rel="empresa" class="list-group-item  text-center active">Empresa</a>
          <a href="#" rel="forma_pago" class="list-group-item  text-center">Forma de Pago</a>
          <a href="#" rel="giro_del_negocio" class="list-group-item  text-center">Giro del Negocio</a>
          <a href="#" rel="grupo_de_parametro" class="list-group-item  text-center">Grupo de Parámetro</a>
          <a href="#" rel="medio_de_pago" class="list-group-item  text-center">Medio de Pago</a>
          <a href="#" rel="moneda" class="list-group-item  text-center">Moneda</a>
          <a href="#" rel="regimen_tributario" class="list-group-item  text-center">Régimen Tributario</a>
          <a href="#" rel="sede" class="list-group-item  text-center">Sede</a>
          <a href="#" rel="tipo_de_cambio" class="list-group-item  text-center">Tipo de Cambio</a>
          <a href="#" rel="tipo_de_documento" class="list-group-item  text-center">Tipo de Documento</a>
          <a href="#" rel="tipo_de_sede" class="list-group-item  text-center">Tipo de Sede</a>
          <a href="#" rel="unidad_de_medida" class="list-group-item  text-center">Unidad de Medida</a>
          <a href="#" rel="correlativo_de_documento" class="list-group-item  text-center">Correlativo de Documento</a>
        </div>
      </div>
      <div id="cont-general" class="col-lg-10 col-md-10 col-sm-10 col-xs-9 bhoechie-tab" style="padding: 0px;">
        <div id="contenedor_empresa" class="bhoechie-tab-content active"><?php echo $view_content_empresa?></div>
        <div id="contenedor_forma_de_pago" class="bhoechie-tab-content "><?php echo $view_content_formapago?></div>
        <div id="contenedor_giro_del_negocio" class="bhoechie-tab-content "><?php echo $view_content_gironegocio?></div>
        <div id="contenedor_grupo_de_parametro" class="bhoechie-tab-content "><?php echo $view_content_grupoparametro?></div>
        <div id="contenedor_medio_de_pago" class="bhoechie-tab-content "><?php echo $view_content_mediopago?></div>
        <div id="contenedor_moneda" class="bhoechie-tab-content "><?php echo $view_content_moneda?></div>
        <div id="contenedor_regimen_tributario" class="bhoechie-tab-content "><?php echo $view_content_regimentributario?></div>
        <div id="contenedor_sede" class="bhoechie-tab-content "><?php echo $view_content_sede?></div>
        <div id="contenedor_tipo_de_cambio" class="bhoechie-tab-content "><?php  echo $view_content_tipocambio ?></div>
        <div id="contenedor_tipo_de_documento" class="bhoechie-tab-content "><?php  echo $view_content_tipodocumento ?></div>
        <div id="contenedor_tipo_de_sede" class="bhoechie-tab-content "><?php  echo $view_content_tiposede ?></div>
        <div id="contenedor_unidad_de_medida" class="bhoechie-tab-content "><?php  echo $view_content_unidadmedida ?></div>
        <div id="contenedor_correlativo_de_documento" class="bhoechie-tab-content "><?php  echo $view_content_correlativodocumento ?></div>
      </div>
      </div>

      <!-- ko with : vmgUnidadMedida.dataUnidadMedida-->
          <?php echo $view_subcontent_otraunidadmedida; ?>
      <!-- /ko -->
    </div>
  </div>
</div>
