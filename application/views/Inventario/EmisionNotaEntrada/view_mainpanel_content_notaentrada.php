<!-- ko with : data -->
<div class="main__scroll scrollbar-macosx">
  <div class="main__cont">
    <div class="container-fluid half-padding">
      <div class="datalist page page_products products float-right">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?php echo $view_subcontent_panel_notaentrada; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php echo $view_modal_buscador_mercaderia_lista_simple; ?>
<!-- /ko -->

<!-- ko with : data.NotaEntrada  -->
<?php echo $view_subcontent_modal_comprobantesventa; ?>
<!-- /ko -->
