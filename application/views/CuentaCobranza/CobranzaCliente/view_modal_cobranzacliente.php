<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalCobranzaCliente"
data-bind="bootstrapmodal : CobranzaCliente.showComprobanteVenta, show : CobranzaCliente.showComprobanteVenta, onhiden :  function(){CobranzaCliente.Hide(window)}">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <div class="col-md-3">
          <h3 class="panel-title" style="margin-top: 3px;">Edición de Cobranza Cliente</h3>
        </div>
        <div class="col-md-1" style="float:right;">
          <button type="button" class="close" data-bind="click : CobranzaCliente.OnClickBtnCerrar"  aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
      </div>
      <div class="modal-body">
        <!-- ko with : CobranzaCliente -->
        <?php echo $view_form_cobranzacliente; ?>
        <!-- /ko -->
      </div>
    </div>
  </div>
</div>
