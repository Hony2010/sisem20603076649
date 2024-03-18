<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalNotaCredito" data-backdrop="static"
data-bind="bootstrapmodal : NotaCredito.showNotaCredito, show : NotaCredito.showNotaCredito, onhiden :  function(){NotaCredito.Hide(window)}, backdrop: 'static'">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                  <div class="col-md-3">
                      <h3 class="panel-title" style="margin-top: 3px;">Edición de Nota de Crédito</h3>
                  </div>
                  <div class="col-md-1" style="float:right;">
                    <button type="button" class="close" data-bind="click : NotaCredito.OnClickBtnCerrar"  aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="col-md-8" style="float:right;">
                    <?php echo $view_panel_header_notacreditoventa; ?>
                  </div>
            </div>
            <div class="modal-body">
                <?php echo $view_form_notacreditoventa; ?>
            </div>
        </div>
    </div>
</div>
