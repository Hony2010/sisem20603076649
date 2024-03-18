<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog" id="modalProveedor"
  data-bind="bootstrapmodal : Proveedor.showProveedor, show : Proveedor.showProveedor, onhiden :  function(){Proveedor.Hide(window)}, backdrop: 'static'" >
    <div class="modal-dialog" role="document">
      <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-bind="click : Proveedor.OnClickBtnCerrar"  aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="panel-title"><span data-bind="text : Proveedor.MostrarTitulo()"></span></h4>
            </div>
            <div class="modal-body">
              <?php echo $view_subcontent_form_proveedor; ?>
            </div>
      </div>
    </div>
</div>
