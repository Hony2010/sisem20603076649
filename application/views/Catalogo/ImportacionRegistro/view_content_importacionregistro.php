<!-- ko with : data -->
<div class="main__scroll scrollbar-macosx">
  <div class="main__cont">
    <div class="form-group">
      <?php //echo $view_tipoventa_compramasiva ?>
    </div>
    <div class="container-fluid half-padding">
      <div class="datalist page page_products products float-right">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?php echo $view_panel_importacionregistro; ?>
          </div>
        </div>
      </div>
      <div class="row">
        <?php echo $view_template_cliente; ?>
        <?php echo $view_template_proveedor; ?>
        <?php echo $view_template_rol; ?>
        <?php echo $view_template_familiaproducto; ?>
        <?php echo $view_template_marca; ?>
        <?php echo $view_template_subfamiliaproducto; ?>
        <?php echo $view_template_modelo; ?>
        <?php echo $view_template_mercaderia; ?>
      </div>
    </div>
  </div>
</div>
<!-- /ko -->
