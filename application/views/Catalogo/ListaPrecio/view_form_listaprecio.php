<!-- ko with : ListaPrecio -->
<form  id="formListaPrecioMercaderia" name="formListaPrecioMercaderia" role="form" autocomplete="off" enctype='multipart/form-data'>
  <div class="datalist__result">
        <div class="container-fluid">
          <div class="row">
            <fieldset>
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Lista</div>
                        <select name="combo-lista" id="combo-lista" class="form-control formulario" data-bind="
                          value : IdTipoListaPrecio,
                          options : TiposListaPrecio,
                          optionsValue : 'IdTipoListaPrecio' ,
                          optionsText : 'NombreTipoListaPrecio',
                          event: {keydown: OnKeyEnter}">
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="checkbox">
                          <input name="CheckPrecioBase" id="CheckPrecioBase" type="checkbox" class="form-control formulario"
                          data-bind="checked: InputPrecioBase, event: { change : OnChangeCheckPrecioBase , keydown : OnKeyEnter}" />
                          <label for="CheckPrecioBase">Precio Base</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                  </div>
                  <!-- ko if: ParametroListaPrecioAvanzado() == 1-->
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Margen Utilidad (%)</div>
                        <input class="form-control formulario" type="text" placeholder="" data-bind='value : MargenUtilidadPrincipal, event:{keydown: OnKeyEnter}, numberdecimal : MargenUtilidadPrincipal'
                        data-validation="number_desc" data-validation-allowing="float,positive,range[0;9999999]" data-validation-decimal-separator="." data-validation-error-msg="De 0 a más" 
                        data-cantidad-decimal="2" data-validation-optional="false">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-1">
                    <div class="form-group">
                      <button class="btn btn-primary btn-control" type="button" data-bind="event:{click: OnClickAplicarMargenUtilidad}">Aplicar</button>
                    </div>
                  </div>
                  <!-- /ko -->

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
                        <select name="combo-marca" id="combo-marca" class="form-control formulario" data-bind="
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
            <!-- ko if: ParametroListaPrecioAvanzado() == 1-->
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-5">
                    <!-- ko if: ParametroCostoOPrecioPromedio() == 0-->
                    <div id="GrupoDocumentos">
                      <div class="radio radio-inline">
                        <input id="UltimoCostoPromedioPonderado" type="radio" name="radio" class="no-tab" value="0" data-bind="checked : FiltroCosto">
                        <label for="UltimoCostoPromedioPonderado">Ult. Costo Prom. Pond.</label>
                      </div>
                      <div class="radio radio-inline">
                        <input id="CostoPromedioPonderado" type="radio" name="radio" class="no-tab" value="1" data-bind="checked : FiltroCosto">
                        <label for="CostoPromedioPonderado">Costo Promedio Ponderado</label>
                      </div>
                    </div>
                    <!-- /ko -->
                    <!-- ko if: ParametroCostoOPrecioPromedio() == 1-->
                    <div id="GrupoDocumentos">
                      <div class="radio radio-inline">
                        <input id="UltimoPrecioPromedioCompra" type="radio" name="radio" class="no-tab" value="0" data-bind="checked : FiltroPrecio">
                        <label for="UltimoPrecioPromedioCompra">Ult. Precio Compra</label>
                      </div>
                      <div class="radio radio-inline">
                        <input id="PrecioPromedioCompra" type="radio" name="radio" class="no-tab" value="1" data-bind="checked : FiltroPrecio">
                        <label for="PrecioPromedioCompra">Precio Promedio Compra</label>
                      </div>
                    </div>
                    <!-- /ko -->
                </div>
                <div class="col-md-4">
                  <!-- ko if: ParametroCostoOPrecioPromedio() == 0-->
                  <div class="form-group">
                    <button class="btn btn-primary btn-control" type="button" data-bind="event:{click: OnClickActualizarCostosPromedio}">Actualizar Costo</button>
                  </div>
                  <!-- /ko -->
                  <!-- ko if: ParametroCostoOPrecioPromedio() == 1-->
                  <div class="form-group">
                    <button class="btn btn-primary btn-control" type="button" data-bind="event:{click: OnClickActualizarPreciosPromedio}">Actualizar Precio</button>
                  </div>
                  <!-- /ko -->
                </div>
              </div>
            </div>
            <!-- /ko -->
          </fieldset>
          </div>
          <br>
          <div class="row">
              <div class="col-md-12">
                <div class="row detalle-comprobante">
                  <div class="col-md-12">
                    <fieldset>
                      <table class="datalist__table table display table-border" width="100%" id="tablaListaPrecioMercaderia">
                        <thead>
                          <tr>
                            <th class="col-sm-1 products__id"><center>Código</center></th>
                            <th class="col-sm-5 products__title">Descripción Producto</th>
                            <th class="col-sm-1 products__title"><center>Unidad</center></th>
                            <!-- ko if: ParametroListaPrecioAvanzado() == 1-->
                              <!-- ko if: ParametroCostoOPrecioPromedio() == 0-->
                              <th class="col-sm-auto products__title"><center>Costo Prom. Pond.</center></th>
                              <!-- /ko -->
                              <!-- ko if: ParametroCostoOPrecioPromedio() == 1-->
                              <th class="col-sm-auto products__title"><center>Precio Prom. Comp.</center></th>
                              <!-- /ko -->
                              <th class="col-sm-auto products__title"><center>Mar. Porc. (%)</center></th>
                              <th class="col-sm-auto products__title"><center>Mar. Utilidad</center></th>
                              <!-- ko if: ParametroCostoOPrecioPromedio() == 0-->
                              <th class="col-sm-auto products__title"><center>Valor Venta</center></th>
                              <th class="col-sm-auto products__title"><center>Valor IGV</center></th>
                              <!-- /ko -->
                            <!-- /ko -->
                            <th class="col-sm-3 products__title"><center>Precio</center></th>
                            <th class="col-sm-3 products__title"><center>Ult. Precio</center></th>
                            <th class="col-sm-3 products__title"><center>Fecha Ult. Precio</center></th>
                          </tr>
                        </thead>
                        <tbody>
                          <!-- ko foreach : DetallesListaPrecio -->
                          <td class="col-sm-1">
                            <div class="form-group">
                              <span  data-bind="text : CodigoMercaderia, attr : { id : IdListaPrecioMercaderia() + '_span_CodigoMercaderia'}"></span>
                            </div>
                          </td>
                          <td class="col-sm-5">
                            <div class="form-group">
                              <span  data-bind="text : NombreProducto, attr : { id : IdListaPrecioMercaderia() + '_span_NombreProducto'}"></span>
                            </div>
                          </td>
                          <td class="col-sm-1  text-center">
                              <span class="" data-bind="text : AbreviaturaUnidadMedida, attr : { id : IdListaPrecioMercaderia() + '_span_AbreviaturaUnidadMedida'}"></span>
                          </td>

                          <!-- ko if: $parent.ParametroListaPrecioAvanzado() == 1-->

                          <!-- ko if: $parent.ParametroCostoOPrecioPromedio() == 0-->
                          <td class="col-sm-auto">
                            <input name="CostoPromedioPonderado" class="form-control  formulario numeric text-mumeric inputs"
                            data-bind="value : CostoPromedioPonderado,                                               
                            attr : { id : IdListaPrecioMercaderia() + '_input_CostoPromedioPonderado' , 'data-cantidad-decimal' : DecimalPrecio()}, event:{focus: OnFocus, keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); }, focusout: ValidarPrecio, change: OnChangePrecio, blur: CalcularPrecioVenta}, numberdecimal: CostoPromedioPonderado" type="text"
                            data-validation="number" data-validation-allowing="float,positive,range[0;9999999]" data-validation-decimal-separator="." data-validation-error-msg="Solo positivo">
                          </td>
                          <!-- /ko -->
                          <!-- ko if: $parent.ParametroCostoOPrecioPromedio() == 1-->
                          <td class="col-sm-auto">
                            <input name="PrecioPromedioCompra" class="form-control  formulario numeric text-mumeric inputs"
                            data-bind="value : PrecioPromedioCompra,
                            attr : { id : IdListaPrecioMercaderia() + '_input_PrecioPromedioCompra' , 'data-cantidad-decimal' : DecimalPrecio()}, event:{focus: OnFocus, keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); }, focusout: ValidarPrecio, change: OnChangePrecio, blur: CalcularPrecioVenta}, numberdecimal: PrecioPromedioCompra" type="text"
                            data-validation="number" data-validation-allowing="float,positive,range[0;9999999]" data-validation-decimal-separator="." data-validation-error-msg="Solo positivo">
                          </td>
                          <!-- /ko -->
                          <td class="col-sm-auto">
                            <input name="MargenPorcentaje" class="form-control  formulario numeric text-mumeric inputs"
                            data-bind="value : MargenPorcentaje,
                            attr : { id : IdListaPrecioMercaderia() + '_input_MargenPorcentaje' , 'data-cantidad-decimal' : DecimalLista()}, event:{focus: OnFocus, keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); }, focusout: ValidarPrecio, change: OnChangePrecio, blur: CalcularPrecioVenta}, numberdecimal: MargenPorcentaje" type="text"
                            data-validation="number" data-validation-allowing="float,positive,range[0;9999999]" data-validation-decimal-separator="." data-validation-error-msg="Solo positivo">
                          </td>
                          <td class="col-sm-auto">
                            <input disabled name="MargenUtilidad" class="form-control  formulario numeric text-mumeric inputs"
                            data-bind="value : MargenUtilidad,
                            attr : { id : IdListaPrecioMercaderia() + '_input_MargenUtilidad' , 'data-cantidad-decimal' : DecimalPrecio()}, event:{focus: OnFocus, keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); }, focusout: ValidarPrecio, change: OnChangePrecio, blur: CalcularPrecioVenta}, numberdecimal: MargenUtilidad" type="text"
                            data-validation="number" data-validation-allowing="float,positive,range[0;9999999]" data-validation-decimal-separator="." data-validation-error-msg="Solo positivo">
                          </td>
                          <!-- ko if: $parent.ParametroCostoOPrecioPromedio() == 0-->
                          <td class="col-sm-auto">
                            <input disabled name="ValorVenta" class="form-control  formulario numeric text-mumeric inputs"
                            data-bind="value : ValorVenta,
                            attr : { id : IdListaPrecioMercaderia() + '_input_ValorVenta' , 'data-cantidad-decimal' : DecimalPrecio()}, event:{focus: OnFocus, keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); }, focusout: ValidarPrecio, change: OnChangePrecio, blur: CalcularPrecioVenta}, numberdecimal: ValorVenta" type="text"
                            data-validation="number" data-validation-allowing="float,positive,range[0;9999999]" data-validation-decimal-separator="." data-validation-error-msg="Solo positivo">
                          </td>
                          <td class="col-sm-auto">
                            <input disabled name="ValorIGV" class="form-control  formulario numeric text-mumeric inputs"
                            data-bind="value : ValorIGV,
                            attr : { id : IdListaPrecioMercaderia() + '_input_ValorIGV' , 'data-cantidad-decimal' : DecimalPrecio()}, event:{focus: OnFocus, keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); }, focusout: ValidarPrecio, change: OnChangePrecio, blur: CalcularPrecioVenta}, numberdecimal: ValorIGV" type="text"
                            data-validation="number" data-validation-allowing="float,positive,range[0;9999999]" data-validation-decimal-separator="." data-validation-error-msg="Solo positivo">
                          </td>
                          <!-- /ko -->

                          <!-- /ko -->

                          <td class="NotaEntrada_Todos ListaPrecioMercaderia_ValorUnitario col-sm-3">
                            <input name="ValorUnitario" class="NotaEntrada_Todos ListaPrecioMercaderia_ValorUnitario form-control  formulario numeric text-mumeric inputs"
                            data-bind="value : Precio,
                            attr : { id : IdListaPrecioMercaderia() + '_input_ValorUnitario' , 'data-cantidad-decimal' : DecimalPrecio()}, event:{focus: OnFocus, keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); }, focusout: ValidarPrecio, change: OnChangePrecio, blur: CalcularPorPrecioVenta}, numberdecimal: Precio" type="text"
                            data-validation="number" data-validation-allowing="float,positive,range[0;9999999]" data-validation-decimal-separator="." data-validation-error-msg="Solo positivo">
                          </td>
                          <td class="col-sm-3" align="right">
                            <span data-bind="text : UltimoPrecioFormateado"></span>
                          </td>
                          <td class="col-sm-3" align="center">
                          <span data-bind="text : FechaUltimoPrecio"></span>
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
              <button type="button" id="btn_Limpiar" class="btn btn-default focus-control" data-bind="click : Limpiar, visible : opcionProceso() == 1">Limpiar</button> &nbsp;
              <br>
            </center>
          </div>
        </div>

  </div>
</form>
<!-- /ko -->
