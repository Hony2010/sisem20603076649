<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalTransferenciaAlmacen"
data-bind="bootstrapmodal : TransferenciaAlmacen.showTransferenciaAlamacen, show : TransferenciaAlmacen.showTransferenciaAlamacen, onhiden :  function(){TransferenciaAlmacen.Hide(window)}">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <div class="col-md-3">
          <h3 class="panel-title" style="margin-top: 3px;">Transferencia de Almacenes</h3>
        </div>
        <div class="col-md-1" style="float:right;">
          <button type="button" class="close" data-bind="click : TransferenciaAlmacen.OnClickBtnCerrar"  aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
      </div>
      <div class="modal-body">
        <?php echo $view_form_transferenciaalmacen; ?>
      </div>
    </div>
  </div>
</div>
