<div class="main__scroll scrollbar-macosx">
  <!-- ko with : data -->
  <div class="main__cont">
    <div class="container-fluid half-padding">
      <div class="datalist page page_products products float-right">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <!-- ko with : ParametrosSistema -->
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Configuración de compras</h3>
              </div>
              <div class="panel-body">
                <fieldset>
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Descripción</th>
                        <th colspan="2">Valor</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <!-- ko with : ParametroSerieCompra -->
                        <td>
                          <span>Cantidad de dígitos de la serie de registro de compra.</span>
                        </td>
                        <td colspan="2">
                          <input id="SerieCompra" class="form-control formulario" type="text" data-bind="value: ValorParametroSistema">
                        </td>
                        <!-- /ko -->
                      </tr>
                      <tr>
                        <!-- ko with : ParametroPrecioCompra -->
                        <td>
                          <span>Utilizar el presio o valor durate el registro de compra.</span>
                        </td>
                        <td>
                          <div class="radio radio-inline">
                            <input id="PrecioCompraSi" type="radio" name="PrecioCompra" value="1" data-bind="checked : ValorParametroSistema">
                            <label for="PrecioCompraSi">Precio Unit.</label>
                          </div>
                        </td>
                        <td>
                          <div class="radio radio-inline">
                            <input id="PrecioCompraNo" type="radio" name="PrecioCompra" value="0" data-bind="checked : ValorParametroSistema">
                            <label for="PrecioCompraNo">Valor Unit.</label>
                          </div>
                        </td>
                        <!-- /ko -->
                      </tr>
                      <tr>
                        <!-- ko with : CodigoProductoProveedor -->
                        <td>
                          <span>¿Desea registrar el código producto del proveedor durante el registro de compras?.</span>
                        </td>
                        <td>
                          <div class="radio radio-inline">
                            <input id="CodigoProductoProveedorSi" type="radio" name="CodigoProductoProveedor" value="1" data-bind="checked : ValorParametroSistema">
                            <label for="CodigoProductoProveedorSi">Si</label>
                          </div>
                        </td>
                        <td>
                          <div class="radio radio-inline">
                            <input id="CodigoProductoProveedorNo" type="radio" name="CodigoProductoProveedor" value="0" data-bind="checked : ValorParametroSistema">
                            <label for="CodigoProductoProveedorNo">No</label>
                          </div>
                        </td>
                        <!-- /ko -->
                      </tr>
                    </tbody>
                  </table>
                </fieldset>
                <br>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group text-center">
                      <button type="button" class="btn" data-bind="event: {click: OnClickDeshacerCambios}">Deshacer</button>
                      <button type="button" class="btn btn-success" data-bind="event: {click: OnClickGuardarParametro}">Guardar</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- /ko -->
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /ko -->
</div>