<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalOtroDocumentoEgreso"
data-bind="bootstrapmodal : OtroDocumentoEgreso.showComprobanteVenta, show : OtroDocumentoEgreso.showComprobanteVenta, onhiden :  function(){OtroDocumentoEgreso.Hide(window)}">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-bind="click : OtroDocumentoEgreso.OnClickBtnCerrar"  aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="panel-title" style="margin-top: 3px;">Edición de Otros Documentos Egreso</h3>
      </div>
      <div class="modal-body">
        <br>
        <?php echo $view_form_otrodocumentoegreso; ?>
      </div>
    </div>
  </div>
</div>
