<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog" id="modalTransportista"
  data-bind="bootstrapmodal : Transportista.showTransportista, show : Transportista.showTransportista, onhiden :  function(){Transportista.Hide(window)}" >
    <div class="modal-dialog" role="document">
      <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-bind="click : Transportista.OnClickBtnCerrar"  aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="panel-title"><span data-bind="text : Transportista.MostrarTitulo()"></span></h4>
            </div>
            <div class="modal-body">
              <?php echo $view_subcontent_form_transportista; ?>
            </div>
      </div>
    </div>
</div>
