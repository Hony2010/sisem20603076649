<div class="panel panel-info">
  <div class="panel-heading">
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <h3 class="panel-title" style="margin-top: 6px;">
            Emisión de Factura de Venta
          </h3>
        </div>
      </div>
      <div class="col-md-8">
      <!-- ko if: FacturaVenta.ParametroVistaVenta() == 1 -->
      <?php echo $view_panel_header_facturaventa; ?>
      <!-- /ko -->
      </div>
    </div>
  </div>
  <div class="panel-body">
    <div class="">
      <div class="tab-pane active" id="brand" role="tabpanel">
        <div class="scrollable scrollbar-macosx">
          <div class="container-fluid">
            <div class="row">
            </div>
            <div class="row">
              <?php echo $view_form_facturaventa; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
