<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog" id="BuscadorMercaderiaLista">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="panel-title">BÚSQUEDA DE INVENTARIO DE MERCADERÍAS <span id="spanNombreSede"></span> </h4>
      </div>
      <div class="modal-body">
        <div class="container-fluid" style="padding:0px; min-height: 400px;">
          <div class="row">
            <br>
            <div class="col-md-12">
              <div class="col-md-12">
                <form action="">
                  <div class="row">
                    <!-- ko with : BusquedaAvanzadaProducto -->
                    <div class="col-md-12">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="">Filtro de producto</label>
                          <input type="text" class="form-control formulario" placeholder="Buscar Mercaderia" data-bind="
                            value: textofiltro,
                            event: { focus : OnFocus, keydown : OnKeyEnter }">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="">Tipo Cambio</label>
                          <input type="text" class="form-control formulario text-right" data-bind="
                            value: TipoCambio,
                            event: { focus : OnFocus, keydown : OnKeyEnter }">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="">Margen Utilidad</label>
                          <input type="text" class="form-control formulario text-right" data-bind="
                            value: MargenUtilidad,
                            event: { focus : OnFocus, keydown : OnKeyEnter }">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="">&nbsp;</label>
                          <button type="button" class="btn btn-primary btn-control" data-bind=" event: { click: (data, event) => BuscarPorLista(data, event, $root) }">Buscar</button>
                        </div>
                      </div>
                    </div>
                    <!-- /ko -->
                  </div>
                  <fieldset>
                    <div style="overflow-x: scroll;">
                      <table class="table table-hover">
                        <thead>
                          <tr>
                            <th>COD NUEVO</th>
                            <th>COD ALTERNO</th>
                            <th>FAMILIA</th>
                            <th>LINEA</th>
                            <th>APLICACION</th>
                            <th>MARCA</th>
                            <th>REFERENCIA</th>
                            <th>PROVEEDOR</th>
                            <th>UNIDAD</th>
                            <th>PIEZAS</th>
                            <th>STOCK</th>
                            <th>COSTO DOLARES</th>
                            <th>COSTO SOLES</th>
                            <th>PRECIO VENTA SOLES</th>
                            <th>PRECIO SUGERIDO</th>
                            <th>FECHA INGRESO</th>
                          </tr>
                        </thead>
                        <tbody>
                          <!-- ko foreach : Mercaderias -->
                          <tr data-bind="event: {click: OnClickImagenMercaderia}">
                            <td>
                              <span data-bind="text: CodigoMercaderia2"></span>
                            </td>
                            <td>
                              <span data-bind="text: CodigoAlterno"></span>
                            </td>
                            <td>
                              <span data-bind="text: NombreFamiliaProducto"></span>
                            </td>
                            <td>
                              <span data-bind="text: NombreLineaProducto"></span>
                            </td>
                            <td>
                              <span data-bind="text: Aplicacion"></span>
                            </td>
                            <td>
                              <span data-bind="text: NombreMarca"></span>
                            </td>
                            <td>
                              <span data-bind="text: Referencia"></span>
                            </td>
                            <td>
                              <span data-bind="text: RazonSocialProveedor"></span>
                            </td>
                            <td>
                              <span data-bind="text: AbreviaturaUnidadMedida"></span>
                            </td>
                            <td>
                              <span data-bind="text: NumeroPiezas"></span>
                            </td>
                            <td>
                              <span data-bind="text: StockProducto, css: ColorText"></span>
                            </td>
                            <td class="text-right">
                              <span data-bind="text: CostoUnitarioCompraDolares"></span>
                            </td>
                            <td class="text-right">
                              <span data-bind="text: CostoUnitarioCompraSoles"></span>
                            </td>
                            <td class="text-right">
                              <span data-bind="text: PrecioVentaSoles"></span>
                            </td>
                            <td class="text-right">
                              <span data-bind="text: ListaPrecios().length > 0 ? ListaPrecios()[0].Precio() : '0.0000' "></span>
                            </td>
                            <td>
                              <span data-bind="text: FechaIngresoCompra"></span>
                            </td>
                          </tr>
                          <!-- /ko -->
                        </tbody>
                      </table>
                    </div>
                  </fieldset>
                  <br>
                </form>
              </div>
            </div>
          </div>

          <table>
            <tr>
              <td>
                <div id="PaginadorJSONParaLista">
                </div>
              </td>
              <td style="padding-bottom:5px;">
                <!-- ko with : BusquedaAvanzadaProducto -->
                <!-- <h5>Se encontraron <span data-bind="html : totalfilas"></span> registros</h5> -->
                <!-- /ko -->
              <td>
            </tr>
          </table>

        </div>
      </div>
    </div>
  </div>
</div>