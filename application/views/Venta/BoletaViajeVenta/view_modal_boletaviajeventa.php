<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalBoletaViajeVenta" data-backdrop="static"
data-bind="bootstrapmodal : BoletaViajeVenta.showComprobanteVenta, show : BoletaViajeVenta.showComprobanteVenta, onhiden :  function(){BoletaViajeVenta.Hide(window)}, backdrop: 'static'">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                  <div class="col-md-3">
                      <h3 class="panel-title" style="margin-top: 3px;">Edición de Boleta Viaje de Venta</h3>
                  </div>
                  <div class="col-md-1" style="float:right;">
                    <button type="button" class="close" data-bind="click : BoletaViajeVenta.OnClickBtnCerrar"  aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="col-md-8" style="float:right;">
                    <!-- ko if: BoletaViajeVenta.ParametroVistaVenta() == 1 -->
                    <?php echo $view_panel_header_boletaventa; ?>
                    <!-- /ko -->
                  </div>
            </div>
            <div class="modal-body">
                <?php echo $view_form_boletaventa; ?>
            </div>
        </div>
    </div>
</div>
