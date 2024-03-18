<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalBoletaVenta"
data-bind="bootstrapmodal : BoletaVenta.showComprobanteVenta, show : BoletaVenta.showComprobanteVenta, onhiden :  function(){BoletaVenta.Hide(window)}, backdrop: 'static'">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                  <div class="col-md-3">
                      <h3 class="panel-title" style="margin-top: 3px;">Edición de Boleta de Venta</h3>
                  </div>
                  <div class="col-md-1" style="float:right;">
                    <button type="button" class="close" data-bind="click : BoletaVenta.OnClickBtnCerrar"  aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="col-md-8" style="float:right;">
                    <!-- ko if: BoletaVenta.ParametroVistaVenta() == 1 -->
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
