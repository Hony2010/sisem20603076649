<!-- ko with : InventarioInicial -->
<form  id="formInventarioInicial" name="formInventarioInicial" role="form" autocomplete="off" enctype='multipart/form-data'>
  <div class="datalist__result">
  <input id="IdTipoDocumento" class="form-control" type="hidden" placeholder="Documento" data-bind="value: IdTipoDocumento">
  <input id="IdTipoVenta" class="form-control" type="hidden" placeholder="Documento" data-bind="value: IdTipoVenta">
  <!-- <input id="IdTipoOperacion" class="form-control" type="hidden" placeholder="TipoOperacion" data-bind="value: IdTipoOperacion">-->
  <input id="IdInventarioInicial" class="form-control" type="hidden" placeholder="IdInventarioInicial">
  <input id="IdCliente" class="form-control" type="hidden" placeholder="RUC/DNI:"  data-bind="value: IdCliente">

        <div class="container-fluid">
          <div class="row">
              <fieldset>
                <div class="col-md-10">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <div class="input-group">
                          <div class="input-group-addon formulario-venta">Almacen</div>
                          <select name="combo-almacen" id="combo-almacen" class="form-control formulario" data-bind="
                            value : IdAsignacionSede,
                            options : Sedes,
                            optionsValue : 'IdAsignacionSede' ,
                            optionsText : 'NombreSede',
                            event : { focus : OnFocus , change: OnChangeAlmacen, keydown : OnKeyEnter}"
                            data-validation="required" data-validation-error-msg="No tiene almacen asignado">
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <div class="input-group">
                          <div class="input-group-addon formulario-venta">Tipo Existencia</div>
                          <select disabled name="combo-tipoexistencia" id="combo-tipoexistencia" class="form-control formulario" data-bind="
                            value : IdTipoExistencia,
                            options : TiposExistencia,
                            optionsValue : 'IdTipoExistencia' ,
                            optionsText : 'NombreTipoExistencia',
                            event : { focus : OnFocus , keydown : OnKeyEnter}">
                          </select>
                          <!-- <input id="NumeroDocumento" class="form-control formulario no-tab" type="text" tabindex="-1" data-bind="value: IdTipoExistencia, event : {  focus : OnFocus , keydown : OnKeyEnter }"
                          data-validation="number" data-validation-allowing="range[1;99999999]" data-validation-error-msg="El número documento debe ser numérico y tener como máximo 8 digitos"
                          data-validation-depends-on="CheckNumeroDocumento" data-validation-optional="true"> -->
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <div class="input-group">
                          <div class="input-group-addon formulario-venta">Fecha de Inventario</div>
                          <input id="FechaInventario" name="FechaInventario" class="form-control formulario" data-inputmask-clearmaskonlostfocus="false" data-bind="value: FechaInventario, event: {focus : OnFocus , focusout : ValidarFechaInventario ,keydown : OnKeyEnter}"
                          data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha de emision en invalida"/>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <div class="input-group">
                          <div class="input-group-addon formulario-venta">Motivo Inv. Ini.</div>
                          <select name="combo-motivoinventarioinicial" id="combo-motivoinventarioinicial" class="form-control formulario" data-bind="
                            value : IdMotivoInventarioInicial,
                            options : MotivosInventario,
                            optionsValue : 'IdMotivoInventarioInicial' ,
                            optionsText : 'NombreMotivoInventarioInicial',
                            event : { focus : OnFocus , keydown : OnKeyEnter}">
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-8">
                      <div class="form-group">
                        <div class="input-group">
                          <div class="input-group-addon formulario-venta">Observación</div>
                          <input id="Observacion" class="form-control formulario" type="text" data-bind="value: Observacion, event:{keydown : OnKeyEnter}" value="Observacion">
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="col-md-2">
                <div tabindex="500" style="border-radius: 4px; padding-top: 4px;" class="btn btn-primary btn-file focus-control btn-control">
                  <label>Importar Excel</label>
                  <input class="formulario" type="file" id="ParseExcel" name="FileFoto" data-bind="event : { change : GenerarExcel }">
                </div>
                <!-- <div class="form-group">
                  <div class="input-group">
                    <input id="ParseExcel" class="form-control formulario" type="file" data-bind="event:{change: GenerarExcel}" value="Excel">
                  </div>
                </div> -->
              </div>
            </fieldset>
          </div>
          <br/>
          <div class="row">
              <div id="GrupoDocumentos" class="col-md-6">
                <div class="radio radio-inline">
                  <input id="OrigenMercaderiaGeneral" type="radio" name="radio" class="no-tab" value="1" data-bind="checked : IdOrigenMercaderia,event : { change : OnChangeIdOrigenMercaderia }">
                  <label for="OrigenMercaderiaGeneral">General</label>
                </div>
                <!-- ko if: ParametroDua() != 0 -->
                <div class="radio radio-inline">
                  <input id="OrigenMercaderiaDua" type="radio" name="radio" class="no-tab" value="2" data-bind="checked : IdOrigenMercaderia,event : { change : OnChangeIdOrigenMercaderia }">
                  <label for="OrigenMercaderiaDua">DUA</label>
                </div>
                <!-- /ko -->
                <!-- ko if: ParametroDocumentoSalidaZofra() != 0 -->
                <div class="radio radio-inline">
                  <input id="OrigenMercaderiaZofra" type="radio" name="radio" class="no-tab" value="3" data-bind="checked : IdOrigenMercaderia,event : { change : OnChangeIdOrigenMercaderia }">
                  <label for="OrigenMercaderiaZofra">Documento Salida Zofra</label>
                </div>
                <!-- /ko -->
              </div>
          </div>
          <br/>
          <div class="row">
              <div class="col-md-12">
                <div class="row detalle-comprobante">
                  <div class="col-md-12">
                    <fieldset>
                      <table class="datalist__table table display grid-detail-body table-border" width="100%" id="tablaInventarioInicial">
                        <thead>
                          <tr>
                            <th class="col-sm-1 products__id"><center>Código</center></th>
                            <th class="col-sm-7 products__title">Descripción Producto</th>
                            <!-- ko if: ParametroLote() == 1-->
                            <th class="col-sm-1 products__title op-lote">Lote</th>
                            <th class="col-sm-1 products__title op-lote">F. Vencimiento</th>
                            <!-- /ko -->
                            <!-- ko if: ParametroDocumentoSalidaZofra() == 1 && IdOrigenMercaderia() == ORIGEN_MERCADERIA.ZOFRA -->
                            <th class="col-sm-2 products__title"><center>Doc. Zofra</center></th>
                            <th class="col-sm-2 products__title"><center>Fec. Doc. Zofra</center></th>
                            <!-- /ko -->
                            <!-- ko if: ParametroDua() == 1 && IdOrigenMercaderia() == ORIGEN_MERCADERIA.DUA -->
                            <th class="col-sm-1 products__title">Numero Dua</th>
                            <th class="col-sm-1 products__title">Fecha Dua</th>
                            <th class="col-sm-1 products__title">Nro Item</th>
                            <!-- /ko -->
                            <th class="col-sm-2 products__title"><center>Unidad</center></th>
                            <th class="col-sm-1 products__title"><center>Cantidad</center></th>
                            <th class="col-sm-1 products__title"><center>Costo Unitario</center></th>
                            <th class="col-sm-1 products__title"></th>
                          </tr>
                        </thead>
                        <tbody>
                          <!-- ko foreach : DetallesInventarioInicial -->
                          <td class="col-sm-1">
                            <div class="input-group">
                              <input class="NotaEntrada_Todos InventarioInicial_Codigo form-control formulario"
                              data-bind="value: CodigoMercaderia, valueUpdate : 'keyup',
                              attr : { id : IdInventarioInicial() + '_input_CodigoMercaderia'},
                              event : {focus : function(data,event) { return OnFocus(data,event,$parent.OnFocus,$parent.CrearInventarioInicial); } , keydown : function(data,event) { return OnKeyEnterCodigoMercaderia(data,event,$parent.OnKeyEnter); } , focusout : ValidarCodigoMercaderia }" type="text"
                              data-validation="validacion_producto" data-validation-error-msg="Cod. Inválido"
                              data-validation-found="false"  data-validation-text-found="" >
                            </div>
                          </td>
                          <td class="col-sm-7">
                            <div class="input-group">
                              <input class="NotaEntrada_Todos InventarioInicial_Descripcion form-control formulario"
                              data-bind="value: NombreProducto,
                              attr : { id : IdInventarioInicial() + '_input_NombreProducto',
                              'data-validation-reference' : IdInventarioInicial() + '_input_CodigoMercaderia'  },
                              event: { focus : function(data,event) { return OnFocus(data,event,$parent.OnFocus,$parent.CrearInventarioInicial); }  }" type="text"
                              data-validation="autocompletado_producto" data-validation-error-msg="No se han encontrado resultados para tu búsqueda de producto">
                            </div>
                          </td>
                          <!--PARA LOTE-->
                          <!-- ko if: $parent.ParametroLote() == 1 -->
                          <td class="col-sm-1">
                            <div class="input-group">
                              <input name="NumeroLote" class="form-control formulario inputs"
                              data-bind="value : NumeroLote ,
                              attr : { id : IdInventarioInicial() + '_input_NumeroLote' },
                              event: { focus : function(data,event) { return OnFocus(data,event,$parent.OnFocus,$parent.CrearInventarioInicial); }, keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout: ValidarLote }" type="text"
                              >
                            </div>
                          </td>
                          <td class="col-sm-1">
                            <div class="input-group">
                              <input name="FechaVencimientoLote" class="form-control formulario inputs" data-inputmask-clearmaskonlostfocus="false"
                              data-bind="value : FechaVencimiento ,
                              attr : { id : IdInventarioInicial() + '_input_FechaVencimientoLote' },
                              event: { focus : function(data,event) { return OnFocus(data,event,$parent.OnFocus,$parent.CrearInventarioInicial); }, keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout: ValidarFechaVencimiento}" type="text"
                               data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha de vencimiento es invalida">
                            </div>
                          </td>
                          <!-- /ko -->
                          <!--FIN PARA LOTE-->
                          <!-- ko if: $parent.ParametroDocumentoSalidaZofra() == 1 && $parent.IdOrigenMercaderia() == ORIGEN_MERCADERIA.ZOFRA -->
                          <td class="col-sm-2">
                            <div class="input-group">
                              <input name="NumeroDocumentoSalidaZofra" class="form-control formulario inputs"
                              data-bind="value : NumeroDocumentoSalidaZofra ,
                              attr : { id : IdInventarioInicial() + '_input_NumeroDocumentoSalidaZofra' },
                              event: { focus : function(data,event) { return OnFocus(data,event,$parent.OnFocus,$parent.CrearInventarioInicial); }, keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout: ValidarNumeroDocumentoSalidaZofra }" type="text"
                              data-validation="required" data-validation-error-msg="Este campo es requerido">
                            </div>
                          </td>
                          <td class="col-sm-1">
                            <div class="input-group">
                              <input name="FechaEmisionDocumentoSalidaZofra" class="form-control formulario inputs" data-inputmask-clearmaskonlostfocus="false"
                              data-bind="value : FechaEmisionDocumentoSalidaZofra ,
                              attr : { id : IdInventarioInicial() + '_input_FechaEmisionDocumentoSalidaZofra' },
                              event: { focus : function(data,event) { return OnFocus(data,event,$parent.OnFocus,$parent.CrearInventarioInicial); }, keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout: ValidarFechaEmisionDocumentoSalidaZofra}" type="text"
                              data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha de documento salida es invalida">
                            </div>
                          </td>
                          <!-- /ko -->
                          <!-- ko if: $parent.ParametroDua() == 1 && $parent.IdOrigenMercaderia() == ORIGEN_MERCADERIA.DUA -->
                          <td class="col-sm-1">
                            <div class="input-group">
                              <input name="NumeroDua" class="form-control formulario inputs"
                              data-bind="value : NumeroDua ,
                              attr : { id : IdInventarioInicial() + '_input_NumeroDua' },
                              event: { focus : function(data,event) { return OnFocus(data,event,$parent.OnFocus,$parent.CrearInventarioInicial); }, keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout: ValidarNumeroDua }" type="text"
                              data-validation="required" data-validation-error-msg="Este campo es requerido">
                            </div>
                          </td>
                          <td class="col-sm-1">
                            <div class="input-group">
                              <input name="FechaEmisionDua" class="form-control formulario inputs" data-inputmask-clearmaskonlostfocus="false"
                              data-bind="value : FechaEmisionDua ,
                              attr : { id : IdInventarioInicial() + '_input_FechaEmisionDua' },
                              event: { focus : function(data,event) { return OnFocus(data,event,$parent.OnFocus,$parent.CrearInventarioInicial); }, keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout: ValidarFechaEmisionDua}" type="text"
                              data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha de dua es invalida">
                            </div>
                          </td>
                          <td class="col-sm-1">
                            <div class="input-group">
                              <input name="NumeroItemDua" class="form-control formulario inputs"
                              data-bind="value : NumeroItemDua ,
                              attr : { id : IdInventarioInicial() + '_input_NumeroItemDua' },
                              event: { focus : function(data,event) { return OnFocus(data,event,$parent.OnFocus,$parent.CrearInventarioInicial); }, keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout: ValidarNumeroItemDua }" type="text"
                              data-validation="number" data-validation-allowing="float,positive,range[1;9999999]" data-validation-error-msg="De 0 a más">
                            </div>
                          </td>
                          <!-- /ko -->
                          <td class="col-sm-2  text-center">
                            <!-- <span class="" data-bind="text : AbreviaturaUnidadMedida, attr : { id : IdInventarioInicial() + '_span_AbreviaturaUnidadMedida'}"></span> -->
                            <select name="combo-unidadmedida" id="combo-unidadmedida" class="form-control formulario" tabindex="-1" data-bind="
                              value : IdUnidadMedida,
                              options : $root.data.UnidadesMedida,
                              optionsValue : 'IdUnidadMedida' ,
                              optionsText : 'AbreviaturaUnidadMedida',
                              attr : { id : IdInventarioInicial() + '_combo_UnidadMedida'}">
                            </select>
                          </td>
                          <td class="col-sm-1">
                            <div class="input-group">
                              <input name="CantidadInicial" class="NotaEntrada_Todos InventarioInicial_Cantidad form-control formulario numeric text-mumeric"
                              data-bind="value : CantidadInicial , attr : { id : IdInventarioInicial() + '_input_CantidadInicial' },
                              event: {focus : function(data,event) { return OnFocus(data,event,$parent.OnFocus,$parent.CrearInventarioInicial); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout : ValidarCantidad } , numbertrim : CantidadInicial" type="text"
                              data-validation="number_desc" data-validation-allowing="float,positive,range[0.001;9999999]" data-validation-decimal-separator="." data-validation-error-msg="De 0 a más">
                            </div>
                          </td>
                          <td class="NotaEntrada_Todos InventarioInicial_ValorUnitario col-sm-1">
                            <input name="ValorUnitario" class="NotaEntrada_Todos InventarioInicial_ValorUnitario form-control  formulario numeric text-mumeric inputs"
                            data-bind="value : ValorUnitario ,
                            numberdecimal : ValorUnitario,
                            attr : { id : IdInventarioInicial() + '_input_ValorUnitario', 'data-cantidad-decimal' : DecimalValorUnitario() },
                            event: { focus: function(data,event) { return OnFocus(data,event,$parent.OnFocus,$parent.CrearInventarioInicial); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout : ValidarValorUnitario}" type="text"
                            data-validation="number_desc" data-validation-allowing="float,positive,range[0.001;9999999]" data-validation-decimal-separator="." data-validation-error-msg="Solo positivo">
                          </td>
                          <td class="col-sm-auto">
                            <div class="input-group ajuste-opcion-plusminus">
                              <button type="button" class="NotaEntrada_Todos InventarioInicial_Eliminar btn btn-default focus-control glyphicon glyphicon-minus no-tab"
                              data-bind="click : $parent.QuitarDetalleInventarioInicial,
                              event : { focus: function(data,event) { return OnFocus(data,event,$parent.OnFocus,$parent.CrearInventarioInicial); } ,keydown : function(data,event) { return OnKeyEnterOpcion(data,event,$parent.OnKeyEnter); }  },
                              attr : { id : IdInventarioInicial() + '_a_opcion'}" ></button>
                            </div>
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
