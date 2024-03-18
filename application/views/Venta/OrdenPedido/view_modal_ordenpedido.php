<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalOrdenPedido" data-backdrop="static"
data-bind="bootstrapmodal : OrdenPedido.showComprobanteVenta, show : OrdenPedido.showComprobanteVenta, onhiden :  function(){OrdenPedido.Hide(window)}, backdrop: 'static'">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                  <div class="col-md-3">
                      <h3 class="panel-title" style="margin-top: 3px;">Edición de Orden de pedido</h3>
                  </div>
                  <div class="col-md-1" style="float:right;">
                    <button type="button" class="close" data-bind="click : OrdenPedido.OnClickBtnCerrar"  aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="col-md-8" style="float:right;">
                    <?php echo $view_panel_header_ordenpedido; ?>
                  </div>
            </div>
            <div class="modal-body">
                <?php echo $view_form_ordenpedido; ?>
            </div>
        </div>
    </div>
</div>
