<!-- ko with : ListaPrecio -->
<form  id="formListaPreciosMercaderia" name="formListaPreciosMercaderia" role="form" autocomplete="off" enctype='multipart/form-data'>
  <div class="datalist__result">
        <div class="container-fluid">
          <div class="row">
            <fieldset>
              <legend>Tipo Descuento</legend>
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="radio radio-inline">
                        <input id="TipoDescuentoPorcentual" type="radio" name="TipoDescuento" class="no-tab" value="0" data-bind="
                        checked : TipoDescuento,
                        event : { change : OnChangeValorDescuento}">
                        <label for="TipoDescuentoPorcentual">Porcentual</label>
                      </div>
                      <div class="radio radio-inline">
                        <input id="TipoDescuentoMonto" type="radio" name="TipoDescuento"  class="no-tab" value="1" data-bind="
                        checked : TipoDescuento,
                        event : { change : OnChangeValorDescuento }">
                        <label for="TipoDescuentoMonto">Monto</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario">Valor Descuento</div>
                        <input class="form-control formulario text-right" type="text" data-bind="
                        value: ValorDescuento,
                        enable: DetallesListaPrecio().length > 0,
                        event : {keydown : OnKeyEnter, change: OnChangeValorDescuento},
                        numbertrim: ValorDescuento">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <button type="button" class="btn btn-default btn-control" data-bind="event: { click: OnClickBtnLimpiarDescuentos }">Limpiar descuentos</button>
                    </div>
                  </div>
                </div>
              </div>
            </fieldset>
            <fieldset>
              <legend>Filtros</legend>
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Familia Producto</div>
                        <select name="combo-familia" id="combo-familia" class="form-control formulario" data-bind="
                          value : IdFamiliaProducto,
                          options : FamiliasProducto,
                          optionsValue : 'IdFamiliaProducto' ,
                          optionsText : 'NombreFamiliaProducto',
                          optionsCaption: 'Todos',
                          event: { change : OnChangeFamilia, keydown: OnKeyEnter}">
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Sub Familia</div>
                        <select id="combo-subfamiliaproducto" class="form-control formulario" data-bind="value: IdSubFamiliaProducto,event:{ change: OnChangeSubFamilia, keydown: OnKeyEnter}">
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Marca</div>
                        <select name="combo-marca" id="combo-motivoinventarioinicial" class="form-control formulario" data-bind="
                          value : IdMarca,
                          options : Marcas,
                          optionsValue : 'IdMarca' ,
                          optionsText : 'NombreMarca',
                          optionsCaption: 'Todos',
                          event: { change : OnChangeMarca, keydown: OnKeyEnter}">
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Modelo</div>
                        <select id="combo-modelo" class="form-control formulario" data-bind="value:IdModelo, event:{ change: OnChangeModelo, keydown: OnKeyEnter}">
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
            <div class="col-md-12">
              <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Linea</div>
                        <select name="combo-motivoinventarioinicial" id="combo-motivoinventarioinicial" class="form-control formulario" data-bind="
                          value : IdLineaProducto,
                          options : LineasProducto,
                          optionsCaption: 'Todos',
                          optionsValue : 'IdLineaProducto' ,
                          optionsText : 'NombreLineaProducto',
                          event: {keydown: OnKeyEnter}">
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <input class="form-control formulario" type="text" placeholder="Buscar Mercadería por Descripción" data-bind='value : Descripcion, event:{keydown: OnKeyEnter}'>
                    </div>
                  </div>
                  <!-- ko if: PrecioBase() == 0-->
                  <div class="col-md-3">
                    <div class="form-group">
                      <button class="btn btn-primary btn-control" type="button" data-bind="event:{click: OnClickConsultarMercaderias}">Buscar</button>
                    </div>
                  </div>
                  <!-- /ko -->
                  <!-- ko if: PrecioBase() == 1-->
                  <div class="col-md-3">
                    <div class="form-group">
                      <button class="btn btn-primary btn-control" type="button" data-bind="event:{click: OnClickConsultarMercaderiasParaPrecioBase}">Buscar</button>
                    </div>
                  </div>
                  <!-- /ko -->
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
                      <table class="datalist__table table display table-border" width="100%" id="tablaListaPreciosMercaderia">
                        <thead>
                          <tr>
                            <th class="text-center">Código</th>
                            <th class="">Descripción Producto</th>
                            <th class="text-center">Unidad</th>
                            <th width="250px" class="text-center">Tipo Descuento</th>
                            <th width="100px" class="text-center">Valor Descuento</th>
                          </tr>
                        </thead>
                        <tbody>
                          <!-- ko foreach : DetallesListaPrecio -->
                          <td class="">
                            <div class="form-group">
                              <span  data-bind="text : CodigoMercaderia"></span>
                            </div>
                          </td>
                          <td class="">
                            <div class="form-group">
                              <span  data-bind="text : NombreProducto"></span>
                            </div>
                          </td>
                          <td class="text-center">
                              <span class="" data-bind="text : AbreviaturaUnidadMedida"></span>
                          </td>
                          <td class="text-center">
                            <div class="form-group">
                              <div class="radio radio-inline">
                                <input type="radio" class="no-tab" value="0" data-bind="
                                checked : TipoDescuento,
                                attr: { id: 'TipoDescuentoPorcentual_' + IdProducto(), name: 'TipoDescuento_' + IdProducto() }">
                                <label data-bind="attr:{ for: 'TipoDescuentoPorcentual_' + IdProducto()}">Porcentual</label>
                              </div>
                              <div class="radio radio-inline">
                                <input type="radio" class="no-tab" value="1" data-bind="
                                checked : TipoDescuento,
                                attr: { id: 'TipoDescuentoMonto_' + IdProducto(), name: 'TipoDescuento_' + IdProducto() }">
                                <label data-bind="attr:{ for: 'TipoDescuentoMonto_' + IdProducto()}">Monto</label>
                              </div>
                            </div>
                          </td>
                          <td class="">
                            <input name="ValorDescuento" class="form-control  formulario numeric text-mumeric inputs"
                            data-bind="
                            value : ValorDescuento,
                            event:{focus: OnFocus, keydown : $parent.OnKeyEnter, focusout: ValidarPrecio}, numbertrim: ValorDescuento" type="text"
                            data-validation="number_desc" data-validation-allowing="float,range[0;9999999]" data-validation-decimal-separator="." data-validation-error-msg="Solo positivo">
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
              <button type="button" id="btn_Grabar" class="btn btn-success focus-control" data-bind="click : GuardarDescuentos">Grabar</button> &nbsp;
              <button type="button" id="btn_Limpiar" class="btn btn-default focus-control" data-bind="click : Limpiar, visible : opcionProceso() == 1">Limpiar</button> &nbsp;
              <br>
            </center>
          </div>
        </div>

  </div>
</form>
<!-- /ko -->
