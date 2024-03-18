<!-- ko with : data -->
<div class="main__scroll scrollbar-macosx">
  <div class="main__cont">
    <div class="container-fluid half-padding">
      <div class="datalist page page_products products float-right">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?php echo $view_subcontent_panel_comprobantecompra; ?>
          </div>
        </div>
      </div>
      <div class="row">
        <?php echo $view_subcontent_modal_proveedor; ?>
        <?php echo $view_subcontent_modal_preview_foto_proveedor; ?>
        <?php echo $view_modal_form_mercaderia; ?>
        <?php echo $view_modal_buscador_mercaderia_lista_simple; ?>

        <!-- ko with : ComprobanteCompra -->
        <?php echo $view_subcontent_modal_content_documentoingreso; ?>
        <!-- /ko -->
      </div>
    </div>
  </div>
</div>
<!-- /ko -->
