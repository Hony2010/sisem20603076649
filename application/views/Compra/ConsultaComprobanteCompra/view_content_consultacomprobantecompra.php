<!-- ko with : data -->
<div class="main__scroll scrollbar-macosx">
  <div class="main__cont">
    <div class="container-fluid half-padding">
      <div class="datalist page page_products products float-right">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Consulta de Comprobantes de Compra</h3>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                  <?php echo $view_buscador_consultacomprobantecompra; ?>
                  <?php echo $view_tabla_consultacomprobantecompra; ?>
                  <br>
                  <?php echo $view_paginacion_consultacomprobantecompra; ?>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>


<?php echo $view_modal_comprobantecompra; ?>
<?php echo $view_modal_comprobantecompra_alternativo; ?>
<?php echo $view_modal_gasto; ?>
<?php echo $view_modal_costoagregado; ?>

<?php echo $view_modal_notacreditocompra; ?>
<?php echo $view_modal_notadebitocompra; ?>

<?php echo $view_modal_proveedor; ?>
<?php echo $view_modal_preview_proveedor; ?>
<?php echo $view_modal_form_mercaderia; ?>
<?php echo $view_modal_buscador_mercaderia_lista_simple; ?>

<?php echo $view_subcontent_modal_documentocompra; ?>

<!-- ko with : ComprobanteCompra -->
<?php echo $view_subcontent_modal_content_documentoingreso; ?>
<!-- /ko -->

<!-- ko with : NotaCreditoCompra  -->
<?php echo $view_subcontent_modal_comprobantescompra_notacredito; ?>
<!-- /ko -->

<!-- ko with : NotaDebitoCompra  -->
<?php echo $view_subcontent_modal_comprobantescompra_notadebito; ?>
<!-- /ko -->

<!-- /ko -->
