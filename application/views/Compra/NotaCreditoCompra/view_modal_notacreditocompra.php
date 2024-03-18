<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalNotaCreditoCompra"
data-bind="bootstrapmodal : NotaCreditoCompra.showNotaCreditoCompra, show : NotaCreditoCompra.showNotaCreditoCompra, onhiden :  function(){NotaCreditoCompra.Hide(window)}">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                  <div class="col-md-3">
                      <h3 class="panel-title" style="margin-top: 3px;">Edición de Nota de Crédito</h3>
                  </div>
                  <div class="col-md-1" style="float:right;">
                    <button type="button" class="close" data-bind="click : NotaCreditoCompra.OnClickBtnCerrar"  aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="col-md-8" style="float:right;">
                    <?php echo $view_panel_header_notacreditocompra; ?>
                  </div>
            </div>
            <div class="modal-body">
                <?php echo $view_form_notacreditocompra; ?>
            </div>
        </div>
    </div>
</div>
