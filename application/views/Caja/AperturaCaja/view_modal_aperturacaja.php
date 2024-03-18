<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalAperturaCaja"
data-bind="bootstrapmodal : AperturaCaja.showComprobanteVenta, show : AperturaCaja.showComprobanteVenta, onhiden :  function(){AperturaCaja.Hide(window)}">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <div class="col-md-4">
          <h3 class="panel-title" style="margin-top: 3px;">Edición de Apertura Turno de Caja</h3>
        </div>
        <div class="col-md-1" style="float:right;">
          <button type="button" class="close" data-bind="click : AperturaCaja.OnClickBtnCerrar"  aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
      </div>
      <div class="modal-body">
        <?php echo $view_form_aperturacaja; ?>
      </div>
    </div>
  </div>
</div>
