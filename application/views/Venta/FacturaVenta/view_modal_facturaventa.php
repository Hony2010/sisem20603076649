<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalFacturaVenta" data-backdrop="static"
data-bind="bootstrapmodal : FacturaVenta.showComprobanteVenta, show : FacturaVenta.showComprobanteVenta, onhiden :  function(){FacturaVenta.Hide(window)}, backdrop: 'static'">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                  <div class="col-md-3">
                      <h3 class="panel-title" style="margin-top: 3px;">Edición de Factura Venta</h3>
                  </div>
                  <div class="col-md-1" style="float:right;">
                    <button type="button" class="close" data-bind="click : FacturaVenta.OnClickBtnCerrar"  aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="col-md-8" style="float:right;">
                    <!-- ko if: FacturaVenta.ParametroVistaVenta() == 1 -->
                    <?php echo $view_panel_header_facturaventa; ?>
                    <!-- /ko -->
                  </div>
            </div>
            <div class="modal-body">
                <?php echo $view_form_facturaventa; ?>
            </div>
        </div>
    </div>
</div>
