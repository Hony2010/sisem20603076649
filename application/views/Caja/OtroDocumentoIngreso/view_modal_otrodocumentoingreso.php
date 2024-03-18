<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalOtroDocumentoIngreso"
data-bind="bootstrapmodal : OtroDocumentoIngreso.showComprobanteVenta, show : OtroDocumentoIngreso.showComprobanteVenta, onhiden :  function(){OtroDocumentoIngreso.Hide(window)}">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-bind="click : OtroDocumentoIngreso.OnClickBtnCerrar"  aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="panel-title" style="margin-top: 3px;">Edición de Otros Documentos Ingreso</h3>
      </div>
      <div class="modal-body">
        <br>
        <?php echo $view_form_otrodocumentoingreso; ?>
      </div>
    </div>
  </div>
</div>
