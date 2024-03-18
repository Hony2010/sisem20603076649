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
                <h3 class="panel-title">Configuración de ventas</h3>
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
                        <!-- ko with : ParametroValorUnitarioVenta -->
                        <td>
                          <span>Utilizar precio o valor de venta.</span>
                        </td>
                        <td>
                          <div class="radio radio-inline">
                            <input id="PrecioVentaUnitario" type="radio" name="PrecioVentaUnitario" value="0" data-bind="checked : ValorParametroSistema">
                            <label for="PrecioVentaUnitario">Precio Unitario Venta</label>
                          </div>
                        </td>
                        <td>
                          <div class="radio radio-inline">
                            <input id="ValorVentaUnitario" type="radio" name="PrecioVentaUnitario" value="1" data-bind="checked : ValorParametroSistema">
                            <label for="ValorVentaUnitario">Valor Unitario Venta</label>
                          </div>
                        </td>
                        <!-- /ko -->
                      </tr>
                      <tr>
                        <!-- ko with : ParametroStockProductoVenta -->
                        <td>
                          <span>Visualizar stock de producto durante la venta.</span>
                        </td>
                        <td>
                          <div class="radio radio-inline">
                            <input id="StockProductoVentaSi" type="radio" name="StockProductoVenta" value="1" data-bind="checked : ValorParametroSistema">
                            <label for="StockProductoVentaSi">Si</label>
                          </div>
                        </td>
                        <td>
                          <div class="radio radio-inline">
                            <input id="StockProductoVentaNo" type="radio" name="StockProductoVenta" value="0" data-bind="checked : ValorParametroSistema">
                            <label for="StockProductoVentaNo">No</label>
                          </div>
                        </td>
                        <!-- /ko -->
                      </tr>
                      <tr>
                        <!-- ko with : ParametroDescuentoUnitarioVenta -->
                        <td>
                          <span>Utilizar descuento unitario durante la venta. </span>
                        </td>
                        <td>
                          <div class="radio radio-inline">
                            <input id="DescuentoUnitarioSi" type="radio" name="DescuentoUnitario" value="1" data-bind="checked : ValorParametroSistema">
                            <label for="DescuentoUnitarioSi">Si</label>
                          </div>
                        </td>
                        <td>
                          <div class="radio radio-inline">
                            <input id="DescuentoUnitarioNo" type="radio" name="DescuentoUnitario" value="0" data-bind="checked : ValorParametroSistema">
                            <label for="DescuentoUnitarioNo">No</label>
                          </div>
                        </td>
                        <!-- /ko -->
                      </tr>
                      <tr>
                        <!-- ko with : ParametroDescuentoPorItemVenta -->
                        <td>
                          <span>Visualizar el descuento por ítem durante la venta.</span>
                        </td>
                        <td>
                          <div class="radio radio-inline">
                            <input id="DescuentoPorItemVentaSi" type="radio" name="DescuentoPorItemVenta" value="1" data-bind="checked : ValorParametroSistema">
                            <label for="DescuentoPorItemVentaSi">Si</label>
                          </div>
                        </td>
                        <td>
                          <div class="radio radio-inline">
                            <input id="DescuentoPorItemVentaNo" type="radio" name="DescuentoPorItemVenta" value="0" data-bind="checked : ValorParametroSistema">
                            <label for="DescuentoPorItemVentaNo">No</label>
                          </div>
                        </td>
                        <!-- /ko -->
                      </tr>
                      <tr>
                        <!-- ko with : ParametroBannerTipoVenta -->
                        <td>
                          <span>Visualizar el banner de tipos de venta (Mercadería, Servicios, Activos Fijos, Otros).</span>
                        </td>
                        <td>
                          <div class="radio radio-inline">
                            <input id="BannerTipoVentaSi" type="radio" name="BannerTipoVenta" value="1" data-bind="checked : ValorParametroSistema">
                            <label for="BannerTipoVentaSi">Si</label>
                          </div>
                        </td>
                        <td>
                          <div class="radio radio-inline">
                            <input id="BannerTipoVentaNo" type="radio" name="BannerTipoVenta" value="0" data-bind="checked : ValorParametroSistema">
                            <label for="BannerTipoVentaNo">No</label>
                          </div>
                        </td>
                        <!-- /ko -->
                      </tr>
                      <tr>
                        <!-- ko with : ParametroTipoVentaPorDefecto -->
                        <td>
                          <span>Seleccionar el tipo de venta por defecto. </span>
                        </td>
                        <td>
                          <div class="radio radio-inline">
                            <input id="TipoVentaPorDefectoSi" type="radio" name="TipoVentaPorDefecto" value="1" data-bind="checked : ValorParametroSistema">
                            <label for="TipoVentaPorDefectoSi">Mercadería</label>
                          </div>
                        </td>
                        <td>
                          <div class="radio radio-inline">
                            <input id="TipoVentaPorDefectoNo" type="radio" name="TipoVentaPorDefecto" value="2" data-bind="checked : ValorParametroSistema">
                            <label for="TipoVentaPorDefectoNo">Servicio</label>
                          </div>
                        </td>
                        <!-- /ko -->
                      </tr>
                      <tr>
                        <!-- ko with : GuiaRemision -->
                        <td>
                          <span>Visualizar el campo guia de remisión durante la venta.</span>
                        </td>
                        <td>
                          <div class="radio radio-inline">
                            <input id="GuiaRemisionSi" type="radio" name="GuiaRemision" value="1" data-bind="checked : ValorParametroSistema">
                            <label for="GuiaRemisionSi">Si</label>
                          </div>
                        </td>
                        <td>
                          <div class="radio radio-inline">
                            <input id="GuiaRemisionNo" type="radio" name="GuiaRemision" value="0" data-bind="checked : ValorParametroSistema">
                            <label for="GuiaRemisionNo">No</label>
                          </div>
                        </td>
                        <!-- /ko -->
                      </tr>
                      <tr>
                        <!-- ko with : OrdenCompra -->
                        <td>
                          <span>Visualizar el campo orden de compra durante la venta.</span>
                        </td>
                        <td>
                          <div class="radio radio-inline">
                            <input id="OrdenCompraSi" type="radio" name="OrdenCompra" value="1" data-bind="checked : ValorParametroSistema">
                            <label for="OrdenCompraSi">Si</label>
                          </div>
                        </td>
                        <td>
                          <div class="radio radio-inline">
                            <input id="OrdenCompraNo" type="radio" name="OrdenCompra" value="0" data-bind="checked : ValorParametroSistema">
                            <label for="OrdenCompraNo">No</label>
                          </div>
                        </td>
                        <!-- /ko -->
                      </tr>
                      <tr>
                        <!-- ko with : GrupoAlmacen -->
                        <td>
                          <span>Visualizar almacén durante la venta. </span>
                        </td>
                        <td>
                          <div class="radio radio-inline">
                            <input id="GrupoAlmacenSi" type="radio" name="GrupoAlmacen" value="1" data-bind="checked : ValorParametroSistema">
                            <label for="GrupoAlmacenSi">Si</label>
                          </div>
                        </td>
                        <td>
                          <div class="radio radio-inline">
                            <input id="GrupoAlmacenNo" type="radio" name="GrupoAlmacen" value="0" data-bind="checked : ValorParametroSistema">
                            <label for="GrupoAlmacenNo">No</label>
                          </div>
                        </td>
                        <!-- /ko -->
                      </tr>
                      <tr>
                        <th colspan="3">
                          <p class="text-uppercase">En caso utilice la opción de documento de zofra</p>
                          <span> Deberá realizar lo siguiente:
                            <ul style="padding-left: 15px; list-style: decimal">
                              <li>Crear almacén tipo zofra (Tabla AsignaciónSede).</li>
                              <li>Activar las boletas tipo T y Z (Seguridad/Acceso de Roles).</li>
                              <li>Asignar al usuario las boletas tipo T, Z y el almacén zofra (Seguridad/Usuarios).</li>
                            </ul>
                          </span>
                        </th>
                      </tr>
                      <tr>
                        <!-- ko with : DocumentoSalidaZofra -->
                        <td>
                          <span>Activar la opción de trabajar con el control de documento de salida de zofra.</span>
                        </td>
                        <td>
                          <div class="radio radio-inline">
                            <input id="DocumentoSalidaZofraSi" type="radio" name="DocumentoSalidaZofra" value="1" data-bind="checked : ValorParametroSistema">
                            <label for="DocumentoSalidaZofraSi">Si</label>
                          </div>
                        </td>
                        <td>
                          <div class="radio radio-inline">
                            <input id="DocumentoSalidaZofraNo" type="radio" name="DocumentoSalidaZofra" value="0" data-bind="checked : ValorParametroSistema">
                            <label for="DocumentoSalidaZofraNo">No</label>
                          </div>
                        </td>
                        <!-- /ko -->
                      </tr>
                      <tr>
                        <!-- ko with : TipoDocumentoSalidaZofra -->
                        <td>
                          <span>Selecciona el tipo de documento que utilizará como documento de zofra.</span>
                        </td>
                        <td colspan="2">
                          <select id="TipoDocumentoSalidaZofra" class="formulario form-control" data-bind="
                          value : ValorParametroSistema,
                          options : $root.data.TiposDocumento,
                          optionsValue : 'IdTipoDocumento' ,
                          optionsText : 'NombreAbreviado',
                          enable: $parent.DocumentoSalidaZofra.ValorParametroSistema() == 1">
                          </select>
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