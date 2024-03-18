<!-- ko with : ListaRaleo -->
<form  id="formListaRaleoMercaderia" name="formListaRaleoMercaderia" role="form" autocomplete="off" enctype='multipart/form-data'>
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
                        <select name="combo-lista" id="combo-almacen" class="form-control formulario" data-bind="
                          value : IdTipoListaRaleo,
                          options : TiposListaRaleo,
                          optionsValue : 'IdTipoListaRaleo' ,
                          optionsText : 'NombreTipoListaRaleo',
                          event: {keydown: OnKeyEnter}">
                        </select>
                      </div>
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
                          optionsCaption: 'Todos',
                          optionsValue : 'IdFamiliaProducto' ,
                          optionsText : 'NombreFamiliaProducto',
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
                          optionsCaption: 'Todos',
                          optionsValue : 'IdMarca' ,
                          optionsText : 'NombreMarca',
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
                  <div class="col-md-3">
                    <div class="form-group">
                      <button class="btn btn-primary btn-control" type="button" name="button" data-bind="event:{click: OnClickConsultarMercaderias}">Buscar</button>
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
                      <table class="datalist__table table display table-border" width="100%" id="tablaListaRaleoMercaderia">
                        <thead>
                          <tr>
                            <th class="col-sm-1 products__id"><center>Código</center></th>
                            <th class="col-sm-8 products__title">Descripción Producto</th>
                            <th class="col-sm-1 products__title"><center>Unidad</center></th>
                            <th class="col-sm-3 products__title"><center>Precio </center></th>
                          </tr>
                        </thead>
                        <tbody>
                          <!-- ko foreach : DetallesListaRaleo -->
                          <td class="col-sm-1">
                            <div class="form-group">
                              <span  data-bind="text : CodigoMercaderia, attr : { id : IdListaRaleoMercaderia() + '_span_CodigoMercaderia'}"></span>
                            </div>
                          </td>
                          <td class="col-sm-8">
                            <div class="form-group">
                              <span  data-bind="text : NombreProducto, attr : { id : IdListaRaleoMercaderia() + '_span_NombreProducto'}"></span>
                            </div>
                          </td>
                          <td class="col-sm-1  text-center">
                              <span class="" data-bind="text : AbreviaturaUnidadMedida, attr : { id : IdListaRaleoMercaderia() + '_span_AbreviaturaUnidadMedida'}"></span>
                          </td>
                          <td class="NotaEntrada_Todos ListaRaleoMercaderia_ValorUnitario col-sm-3">
                            <input name="ValorUnitario" class="NotaEntrada_Todos ListaRaleoMercaderia_ValorUnitario form-control  formulario numeric text-mumeric inputs"
                            data-bind="value : Precio,
                            attr : { id : IdListaRaleoMercaderia() + '_input_ValorUnitario' , 'data-cantidad-decimal' : DecimalPrecio() }, event:{focus: OnFocus, keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); }, focusout: ValidarPrecio, change: OnChangePrecio}, numberdecimal: Precio" type="text"
                            data-validation="number" data-validation-allowing="float,positive,range[0;9999999]" data-validation-decimal-separator="." data-validation-error-msg="Solo positivo">
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
              <!-- <button type="button" id="BtnDeshacer" class="btn btn-default" data-bind="click : Deshacer, visible : opcionProceso() == 2">Deshacer</button> &nbsp;
              <button type="button" id="btn_Cerrar" class="btn btn-default" data-bind="click : OnClickBtnCerrar , visible :  opcionProceso() == 2">Cerrar</button> -->
              <br>
            </center>
          </div>
        </div>

  </div>
</form>
<!-- /ko -->
