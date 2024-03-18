<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalTransferenciaCaja"
data-bind="bootstrapmodal : TransferenciaCaja.showComprobanteVenta, show : TransferenciaCaja.showComprobanteVenta, onhiden :  function(){TransferenciaCaja.Hide(window)}">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <div class="col-md-3">
          <h3 class="panel-title" style="margin-top: 3px;">Transferencia de Caja</h3>
        </div>
        <div class="col-md-1" style="float:right;">
          <button type="button" class="close" data-bind="click : TransferenciaCaja.OnClickBtnCerrar"  aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
      </div>
      <div class="modal-body">
        <?php echo $view_form_transferenciacaja; ?>
      </div>
    </div>
  </div>
</div>
