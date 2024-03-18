<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalComanda" data-backdrop="static"
data-bind="bootstrapmodal : Comanda.showComprobanteVenta, show : Comanda.showComprobanteVenta, onhiden :  function(){Comanda.Hide(window)}, backdrop: 'static'">
  <div class="modal-dialog modal-full-screen" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-bind="click : Comanda.OnClickBtnCerrarModalComanda">&times;</button>
        <h3 class="panel-title" data-bind="text: Comanda.TituloComprobante"></h3>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">
            <?php echo $view_form_comanda; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade in" data-backdrop-limit="1" tabindex="-1" role="dialog" id="PreviewImgProduct">
  <div class="modal-dialog modal-xs" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <img Id="ImgProduct" width="100%" height="100%">
      </div>
    </div>
  </div>
</div>

<div class="modal fade in" data-backdrop-limit="1" tabindex="-1" role="dialog" id="PreviewAnotacionesPlato">
  <div class="modal-dialog modal-xs" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <fieldset>
          <table class="table display table-hover table-border">
            <thead>
              <tr>
                <th class="text-center titulo-anotacion"></th>
              </tr>
            </thead>
            <tbody>
              <!-- ko foreach : AnotacionesPlatoProducto  -->
              <tr class="tr-list-products" data-bind="event: { click: function (data, event) { return OnClickAnotacionMercaderia (data, event, $root.data)} }">
                <td data-bind="text: NombreAnotacionPlato"> </td>
              </tr>
              <!-- /ko -->
            </tbody>
          </table>
        </fieldset>
      </div>
    </div>
  </div>
</div>

<style media="screen">
  .modal-full-screen {
    width: 100% !important;
    height: 100% !important;
    margin: 0px;
  }

  .modal-full-screen .modal-content {
    height: 100% !important;
    border-radius: 0px;
    border: 1px solid #30363c;
  }
  .modal-body {
    padding-top: 15px !important;
}
</style>
