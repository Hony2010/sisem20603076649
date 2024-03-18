<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalComprobanteVenta" data-backdrop="static"
data-bind="bootstrapmodal : ComprobanteVenta.showComprobanteVenta, show : ComprobanteVenta.showComprobanteVenta, onhiden :  function(){ComprobanteVenta.Hide(window)}, backdrop: 'static'">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                  <div class="col-md-3">
                      <h3 class="panel-title" style="margin-top: 3px;">Edición de Comprobante Venta</h3>
                  </div>
                  <div class="col-md-1" style="float:right;">
                    <button type="button" class="close" data-bind="click : ComprobanteVenta.OnClickBtnCerrar"  aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="col-md-8" style="float:right;">
                    <?php echo $view_panel_header_comprobanteventa; ?>
                  </div>
            </div>
            <div class="modal-body">
                <?php echo $view_form_comprobanteventa; ?>
            </div>
        </div>
    </div>
</div>
