<div class="main__scroll scrollbar-macosx">
  <div class="main__cont">
    <div class="col-md-12 bhoechie-tab-container">
      <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 bhoechie-tab-menu">
        <div id="tab-configuracion" class="list-group">
          <a href="#" rel="familia" class="list-group-item  text-center active">Familia y SubFamilia</a>
          <a href="#" rel="linea" class="list-group-item  text-center">Línea de Producto</a>
          <a href="#" rel="marca" class="list-group-item  text-center">Marca y Modelo</a>
          <a href="#" rel="tipo_de_existencia" class="list-group-item  text-center">Tipo de Existencia</a>
          <a href="#" rel="fabricante" class="list-group-item  text-center">Fabricante</a>
          <a href="#" rel="tipo_de_servicio" class="list-group-item  text-center">Tipo de Servicio</a>
          <a href="#" rel="tipo_doc_identidad" class="list-group-item  text-center">Tipo Doc. Identidad</a>
        </div>
      </div>
      <div id="cont-configuracion" class="col-lg-10 col-md-10 col-sm-10 col-xs-9 bhoechie-tab" style="padding: 0px;">
        <div id="contenedor_familia" class="bhoechie-tab-content active"><?php echo $view_content_familia?></div>
        <div id="contenedor_linea" class="bhoechie-tab-content "><?php echo $view_content_linea_producto?></div>
        <div id="contenedor_marca" class="bhoechie-tab-content "><?php echo $view_content_marca?></div>
        <div id="contenedor_tipo_de_existencia" class="bhoechie-tab-content "><?php echo $view_content_tipo_existencia?></div>
        <div id="contenedor_fabricante" class="bhoechie-tab-content "><?php echo $view_content_fabricante?></div>
        <div id="contenedor_tipo_de_servicio" class="bhoechie-tab-content "><?php echo $view_content_tipo_servicio?></div>
        <div id="contenedor_tipo_doc_identidad" class="bhoechie-tab-content "><?php echo $view_content_tipo_documento_identidad?></div>
      </div>
      </div>
    </div>

    <!-- ko with : vmcFamilia.dataFamiliaProducto-->
        <?php echo $view_subcontent_subfamiliaproducto; ?>
    <!-- /ko -->

    <!-- ko with : vmcMarca.dataMarca-->
        <?php echo $view_subcontent_modelo; ?>
    <!-- /ko -->
  </div>
</div>
