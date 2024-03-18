<!-- ko with : data -->
<div class="main__scroll scrollbar-macosx">
  <div class="main__cont">
    <div class="container-fluid half-padding">
      <div class="datalist page page_products products float-right">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Consulta de Comprobantes de Venta</h3>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                  <?php echo $view_buscador_consultacomprobanteventa; ?>
                  <?php echo $view_tabla_consultacomprobanteventa; ?>
                  <br>
                  <?php echo $view_paginacion_consultacomprobanteventa; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php echo $view_modal_comprobanteventa; ?>
<?php echo $view_modal_boletaventa; ?>
<?php echo $view_modal_ordenpedido; ?>
<?php echo $view_modal_facturaventa; ?>
<?php echo $view_modal_notacreditoventa; ?>
<?php echo $view_modal_notadevolucionventa; ?>
<?php echo $view_modal_notadebitoventa; ?>
<?php echo $view_modal_boletaviajeventa; ?>
<?php echo $view_modal_proforma; ?>
<!-- ko with : NotaCredito -->
<?php echo $view_modal_buscador_notacreditoventa; ?>
<!-- /ko -->

<!-- ko with : NotaDebito -->
<?php echo $view_modal_buscador_notadebitoventa; ?>
<!-- /ko -->

<?php echo $view_modal_cliente; ?>
<?php echo $view_modal_preview_cliente; ?>
<?php echo $view_modal_form_mercaderia; ?>
<?php echo $view_modal_buscador_mercaderia; ?>
<?php echo $view_modal_buscador_mercaderia_lista; ?>
<?php echo $view_modal_buscador_mercaderia_lista_simple; ?>
<?php echo $view_modal_casilleroporgenero; ?>
<?php echo $view_modal_buscadorproforma; ?>
<!-- ko with : NotaCredito -->
<?php echo $view_modal_cuotapagoclientecomprobanteventa; ?>
<!-- /ko -->
<!-- ko with :FacturaVenta -->
<?php echo $view_modal_cuotapagoclientecomprobanteventa; ?>
<!-- /ko -->
<div class="modal fade bd-example-modal-lg" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalPDFGenerado">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <iframe class="embed-responsive-item" id="DescargarPDF_iframe" src="" style="width: 100%;height: 550px;"></iframe>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- /ko -->
