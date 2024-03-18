<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalPreVenta"
data-bind="bootstrapmodal : PreVenta.showComprobanteVenta, show : PreVenta.showComprobanteVenta, onhiden :  function(){PreVenta.Hide(window)}, backdrop: 'static'">
  <div class="modal-dialog modal-full-screen" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="panel-title" style="margin-top: 3px;" data-bind="text: PreVenta.TituloComprobante"></h3>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">
            <br>
            <?php echo $view_form_preventa; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade in" data-backdrop-limit="1" tabindex="-1" role="dialog" id="PreviewImgProduct">
  <div class="modal-dialog modal-xs" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <center>
          <img Id="ImgProduct" width="100%" height="100%">
        </center>
      </div>
    </div>
  </div>
</div>

<div class="modal fade in" data-backdrop-limit="1" tabindex="-1" role="dialog" id="PreviewAnotacionesPlato">
  <div class="modal-dialog modal-xs" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <br>
        <fieldset>
          <table class="table display table-border">
            <thead>
              <tr>
                <th class="text-center">¿Como desea su ronda fria ?</th>
              </tr>
            </thead>
            <tbody>
              <!-- ko foreach : AnotacionesPlatoProducto  -->
              <tr data-bind="event: { click: function (data, event) { return OnClickAnotacionMercaderia (data, event, $root.data)} }">
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
  }
</style>
