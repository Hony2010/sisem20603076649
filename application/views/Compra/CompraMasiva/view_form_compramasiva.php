<!-- ko with : CompraMasiva -->
<form  id="formCompraMasiva" name="formCompraMasiva" role="form" autocomplete="off" >
  <div class="datalist__result">
  <input id="IdTipoDocumento" class="form-control" type="hidden" placeholder="Documento" data-bind="value: IdTipoDocumento">
  <input id="IdTipoCompra" class="form-control" type="hidden" placeholder="Documento" data-bind="value: IdTipoCompra">
  <!-- <input id="IdTipoOperacion" class="form-control" type="hidden" placeholder="TipoOperacion" data-bind="value: IdTipoOperacion">-->
  <input id="IdCompraMasiva" class="form-control" type="hidden" placeholder="IdCompraMasiva">
  <input id="IdProveedor" class="form-control" type="hidden" placeholder="RUC/DNI:"  data-bind="value: IdProveedor">

    <div class="tab-pane active" id="brand" role="tabpanel">
      <div class="scrollable scrollbar-macosx">
        <div class="container-fluid">
          <div class="row">
            <fieldset>
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-6">
                    <!-- <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">RUC - Proveedor</div>
                        <input id="Proveedor" name="Proveedor" class="form-control formulario" type="text" data-bind="value : RUCDNIProveedor(),event : { focus : OnFocus }"
                        data-validation="autocompletado_proveedor" data-validation-error-msg="No se han encontrado resultados para tu búsqueda de Proveedor" data-validation-text-found="">
                      </div>
                    </div> -->
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Almacen</div>
                        <select id="combo-almacen" class="form-control formulario" data-bind="
                          value : IdAsignacionSede,
                          options : Sedes,
                          optionsValue : 'IdAsignacionSede' ,
                          optionsText : 'NombreSede' ,
                          event : { focus : OnFocus ,change : OnChangeComboAlmacen , keydown : OnKeyEnter }">
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div tabindex="500" style="border-radius: 4px; padding-top: 4px;" class="btn btn-primary btn-file focus-control btn-control">
                      <label>Excel</label>
                      <input class="formulario" type="file" id="ParseExcel" name="ParseExcel" data-bind="event : { change : $root.GenerarExcel }">
                    </div>
                  </div>
                </div>

            </div>
          </fieldset>
          </div>
          <br>
          <div class="row">
              <div class="col-md-12">
                <div class="row detalle-comprobante">
                  <div class="col-md-12">
                    <fieldset>
                      <table class="datalist__table table display grid-detail-body table-border" width="100%" id="tablaDetalleCompraMasiva">
                        <thead>
                          <tr>
                            <th class="col-sm-1 products__id codigo-producto"><center>Serie/Documento</center></th>
                            <th class="col-sm-2 products__title">Proveedor</th>
                            <th class="col-sm-1 products__title op-unidad"><center>Fecha</center></th>
                            <!-- <th class="col-sm-1 products__title op-cantidad"><center>FormaPago</center></th> -->
                            <th class="col-sm-6 products__title"><center>Detalles</center></th>
                            <th class="col-sm-2 products__title"><center>Observaciones</center></th>
                          </tr>
                        </thead>
                        <tbody>
                          <!-- ko foreach : $root.data.ComprobanteCompra -->
                          <tr name="Fila" class="clickable-row" style="min-height: 80px;" data-bind="attr:{id: IdComprobanteCompra() + '_comprobantecompra'}">
                            <td>
                              <span data-bind="text: SerieDocumento"></span>
                            </td>
                            <td class="col-md-2">
                              <span data-bind="text: Proveedor"></span>
                            </td>
                            <td>
                              <span data-bind="text: FechaEmision"></span>
                            </td>
                            <!-- <td>
                              <span data-bind="text: FormaPago"></span>
                            </td> -->
                            <td>
                              <table class="datalist__table display grid-detail-body table-border" width="100%" id="tablaDetalleCompraMasiva">
                                <thead>
                                  <tr>
                                    <th class="col-sm-auto products__title"><center>Codigo</center></th>
                                    <th class="col-sm-auto products__title"><center>Nombre</center></th>
                                    <th class="col-sm-auto products__title"><center>Unidad</center></th>
                                    <th class="col-sm-auto products__title"><center>Cant.</center></th>
                                    <th class="col-sm-auto products__title"><center>P. U.</center></th>
                                    <th class="col-sm-auto products__title"><center>SubTotal</center></th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <!-- ko foreach : DetallesComprobanteCompra -->
                                  <tr>
                                    <td class="text-right">
                                      <span data-bind="text: CodigoProducto"></span>
                                    </td>
                                    <td class="text-right">
                                      <span data-bind="text: NombreProducto"></span>
                                    </td>
                                    <td class="text-right">
                                      <span data-bind="text: AbreviaturaUnidadMedida"></span>
                                    </td>
                                    <td class="text-right">
                                      <span data-bind="text: Cantidad"></span>
                                    </td>
                                    <td class="text-right">
                                      <span data-bind="text: PrecioUnitario"></span>
                                    </td>
                                    <td class="text-right">
                                      <span data-bind="text: SubTotal"></span>
                                    </td>
                                  </tr>
                                  <!-- /ko -->
                                </tbody>
                              </table>
                            </td>
                            <td class="col-md-2">
                              <ul>
                              <!-- ko foreach : Observaciones -->
                                <li data-bind="text: Observacion"></li>
                              <!-- /ko -->
                              </ul>
                            </td>
                          </tr>
                          <!-- /ko -->
                        </tbody>
                      </table>
                    </fieldset>
                  </div>
                </div>
              </div>
          </div>

          <div class="row">
            <center>
              <br>
              <button type="button" id="btn_Grabar" class="btn btn-success focus-control" data-bind="click : Guardar">Grabar</button> &nbsp;
              <br>
            </center>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
<!-- /ko -->
