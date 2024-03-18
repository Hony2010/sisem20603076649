<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalPagoProveedor"
data-bind="bootstrapmodal : PagoProveedor.showComprobanteVenta, show : PagoProveedor.showComprobanteVenta, onhiden :  function(){PagoProveedor.Hide(window)}">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <div class="col-md-3">
          <h3 class="panel-title" style="margin-top: 3px;">Edición de Pago Proveedor</h3>
        </div>
        <div class="col-md-1" style="float:right;">
          <button type="button" class="close" data-bind="click : PagoProveedor.OnClickBtnCerrar"  aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
      </div>
      <div class="modal-body">
        <!-- ko with : PagoProveedor -->
        <?php echo $view_form_pagoproveedor; ?>
        <!-- /ko -->
      </div>
    </div>
  </div>
</div>
