<!-- ko with : NotaSalida -->
<form  id="formNotaSalida" name="formNotaSalida" role="form" autocomplete="off">
  <div class="datalist__result">
  <input id="IdTipoDocumento" class="form-control" type="hidden" placeholder="Documento" data-bind="value: IdTipoDocumento">
  <input id="IdTipoVenta" class="form-control" type="hidden" placeholder="Documento" data-bind="value: IdTipoVenta">
  <input id="IdTipoOperacion" class="form-control" type="hidden" placeholder="TipoOperacion" data-bind="value: IdTipoOperacion">
  <input id="IdNotaSalida" class="form-control" type="hidden" placeholder="IdNotaSalida">
  <input id="IdCliente" class="form-control" type="hidden" placeholder="RUC/DNI:"  data-bind="value: IdCliente">

    <div class="tab-pane active" id="brand" role="tabpanel">
      <div class="scrollable scrollbar-macosx">
        <div class="container-fluid">
          <div class="row">
            <fieldset>
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario">Serie</div>
                        <select name="combo-seriedocumento" id="combo-seriedocumento" class="form-control formulario" data-bind="
                        value : IdCorrelativoDocumento,
                        options : SeriesDocumento,
                        optionsValue : 'IdCorrelativoDocumento' ,
                        optionsText : 'SerieDocumento',
                        event : { focus : OnFocus , change : OnChangeSerieNotaSalida , keydown : OnKeyEnter}"
                        data-validation="required" data-validation-error-msg="No tiene serie asignada">
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Numero</div>
                      <input id="NumeroNotaSalida" class="form-control formulario no-tab" type="text" tabindex="-1" data-bind="value: NumeroNotaSalida ,
                      attr : { readonly : CheckNumeroNotaSalida }, event : {  focus : OnFocus , focusout : ValidarNumeroNotaSalida , keydown : OnKeyEnter }"
                      data-validation="number" data-validation-allowing="range[1;99999999]" data-validation-error-msg="El número documento debe ser numérico y tener como máximo 8 digitos"
                      data-validation-depends-on="CheckNumeroNotaSalida" data-validation-optional="true">
                    </div>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="checkbox">
                        <input name="CheckNumeroNotaSalida" id="CheckNumeroNotaSalida" type="checkbox" class="form-control formulario"
                        data-bind="event: { change : OnChangeCheckNumeroNotaSalida , focus : OnFocus , keydown : OnKeyEnter}" />
                        <label for="CheckNumeroNotaSalida"> ¿Editar Numero?</label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Motivo</div>
                      <select name="combo-motivo" id="combo-motivo" class="form-control formulario" data-bind="
                      value : IdMotivoNotaSalida,
                      options : MotivosNotaSalida,
                      optionsValue : 'IdMotivoNotaSalida' ,
                      optionsText : 'NombreMotivoNotaSalida',
                      event:{change: CambiarMotivoNotaSalida, keydown : OnKeyEnter}">
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario">Fecha Salida</div>
                    <input id="FechaEmision" name="FechaEmision" class="form-control formulario" data-inputmask-clearmaskonlostfocus="false" data-bind="value: FechaEmision, event: {focus : OnFocus , focusout : ValidarFechaEmision ,keydown : OnKeyEnter}"
                    data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha de emision en invalida"/>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario">G. Rem. Proveedor</div>
                    <input class="form-control formulario" id="GuiaRemisionProveedor"  data-bind="value : GuiaRemisionProveedor, event:{keydown : OnKeyEnter}" type="text" >
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario">G. Rem. Emisor</div>
                    <input class="form-control formulario" id="GuiaRemisionEmisor"  data-bind="value : GuiaRemisionEmisor, event:{keydown : OnKeyEnter}" type="text" >
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario">Observación</div>
                    <!-- <textarea id="Observacion" class="form-control formulario" name="Observacion" rows="2" cols="20"></textarea> -->
                    <input id="Observacion" class="form-control formulario" type="text" data-bind="value: Observacion, event:{keydown : OnKeyEnter}" value="Jorge">
                  </div>
                </div>
              </div>
            </div>
            <!-- <div id="VistaCheckPendiente" style="display: none;">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="checkbox">
                        <input id="CheckPendiente" class="form-control formulario" type="checkbox" name="CheckPendiente" data-bind="checked: CheckPendiente, event:{change: OnChangeCheckPendiente, keydown : OnKeyEnter}">
                        <label for="CheckPendiente">Pendiente de Entrega de Comprobante</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div> -->

              <div id="VistaBusquedaComprobanteReferencia" style="display: none;">
                <div class="row">
                  <div class="col-md-6">
                    <fieldset>
                      <legend>Datos Cliente/Proveedor</legend>
                      <div class="form-group busqueda_cliente">
                        <div class="input-group">
                          <div class="input-group-addon formulario-venta">RUC/DNI - Cliente</div>
                          <input id="Cliente" name="Cliente" class="form-control formulario" type="text" data-bind="value : RUCDNICliente(),event : { focus : OnFocus }"
                          data-validation="autocompletado_cliente" data-validation-error-msg="No se han encontrado resultados para tu búsqueda de cliente" data-validation-text-found="">
                          <div class="input-group-addon unir"></div>
                          <!-- <button type="button" class="form-control btn-buscar btn btn-default no-tab" id="BtnNuevoCliente" data-bind="click : function(data,event) {  return OnClickBtnNuevoCliente(data,event,$parent.Cliente); }"><span class="fa fa-plus-circle"></span></button> -->
                        </div>
                      </div>
                      <div class="form-group busqueda_proveedor">
                        <div class="input-group">
                          <div class="input-group-addon formulario-venta">RUC - Proveedor</div>
                          <input id="Proveedor" name="Proveedor" class="form-control formulario" type="text" data-bind="value : RUCProveedor(),event : { focus : OnFocus }"
                          data-validation="autocompletado_proveedor" data-validation-error-msg="No se han encontrado resultados para tu búsqueda de proveedor" data-validation-text-found="">
                          <div class="input-group-addon unir"></div>
                          <!-- <button type="button" class="form-control btn-buscar btn btn-default no-tab" id="BtnNuevoCliente" data-bind="click : function(data,event) {  return OnClickBtnNuevoCliente(data,event,$parent.Cliente); }"><span class="fa fa-plus-circle"></span></button> -->
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="input-group">
                          <div class="input-group-addon formulario-venta">Dirección</div>
                          <input readonly tabindex="-1" class="form-control formulario no-tab" id="Direccion"  data-bind="value : Direccion" type="text" >
                        </div>
                      </div>
                    </fieldset>
                  </div>
                  <div class="col-md-6">
                    <fieldset>
                      <legend>Documento Referencia</legend>
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-5">
                            <div class="form-group">
                              <div class="input-group">
                                <div class="input-group-addon">Tipo Documento</div>
                                <select id="combo-tipodocumento" class="form-control formulario" data-bind="event:{keydown : OnKeyEnter}">
                                  <option value="0">DEFAULT</option>
                                </select>
                              </div>
                            </div>
                            <button id="btn_buscardocumentoreferencia" class="btn btn-primary btn-control" type="button" name="button" data-bind="click: AbrirConsultaComprobanteVenta">Buscar</button>
                          </div>
                          <div class="col-md-7">
                            <table class="datalist__table table display no-footer" width="100%" data-products="brand">
                              <thead>
                                <tr>
                                  <th  class="products__title">Serie/Numero</th>
                                  <th  class="products__title">Fecha</th>
                                  <th  class="products__title">&nbsp;</th>
                                </tr>
                              </thead>
                              <tbody>
                                <!-- ko foreach : MiniComprobantesVenta -->
                                <tr class="clickable-row" data-bind="" style="text-transform: UpperCase;">
                                  <td data-bind="text: Documento">F001-1286</td>
                                  <td data-bind="text: FechaEmision">10/05/2018</td>
                                  <td class="col-sm-auto">
                                    <div class="input-group ajuste-opcion-plusminus">
                                      <button type="button" class="btn btn-danger no-tab" data-bind="event:{click: EliminarComprobanteVenta}" >X</button>
                                    </div>
                                  </td>
                                </tr>
                                <!-- /ko -->
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                </div>
              </div>
              </div>
            </fieldset>
          </div>
          <p>
          <div class="row">
            <div class="btn-group">
							<button type="button" class="btn btn-primary no-tab" href="#"
								data-bind="click : function(data,event) {  return OnClickBtnBuscadorMercaderiaListaSimple(data,event,$parent);}">
								<span class="glyphicon glyphicon-search"></span> Buscar Mercadería con Lista
							</button>
						</div>
          </div>
          <br>
          <div class="row">
            <fieldset>
              <div class="col-md-12">
                <table id="TableDetalleNotaSalida" class="datalist__table table display grid-detail-body" width="100%" id="tablaDetalleNotaSalida">
                  <thead>
                    <tr>
                      <th class="col-sm-1 products__id"><center>Código</center></th>
                      <th class="col-sm-9 products__title">Descripción</th>
                      <th class="col-sm-1 products__title"><center>Unidad</center></th>
                      <th class="col-sm-1 products__title"><center>Cantidad</center></th>
                      <th class="NotaSalida_Todos DetalleNotaSalida_ValorUnitario col-sm-1 products__title"><center>Valor Referencial</center></th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- ko foreach : DetallesNotaSalida -->
                    <tr class="clickable-row" style="min-height: 80px;" data-bind="attr : { id: IdDetalleNotaSalida }, event :  {focus : $parent.Seleccionar}">
                      <td class="col-sm-1">
                        <div class="input-group">
                          <input class="NotaSalida_Todos DetalleNotaSalida_Codigo form-control formulario"
                          data-bind="value: CodigoMercaderia, valueUpdate : 'keyup',
                          attr : { id : IdDetalleNotaSalida() + '_input_CodigoMercaderia'},
                          event : {focus : function(data,event) { return OnFocus(data,event,$parent.OnFocus,$parent.CrearDetalleNotaSalida); } , keydown : function(data,event) { return OnKeyEnterCodigoMercaderia(data,event,$parent.OnKeyEnter); } , focusout : ValidarCodigoMercaderia }" type="text"
                          data-validation="validacion_producto" data-validation-error-msg="Cod. Inválido"
                          data-validation-found="false"  data-validation-text-found="" >
                        </div>
                      </td>
                      <td class="col-sm-9">
                        <div class="input-group">
                        <input class="NotaSalida_Todos DetalleNotaSalida_Descripcion form-control formulario"
                          data-bind="value: NombreProducto,
                          attr : { id : IdDetalleNotaSalida() + '_input_NombreProducto',
                          'data-validation-reference' : IdDetalleNotaSalida() + '_input_CodigoMercaderia'  },
                          event: { focus : function(data,event) { return OnFocus(data,event,$parent.OnFocus,$parent.CrearDetalleNotaSalida); }  }" type="text"
                          data-validation="autocompletado_producto" data-validation-error-msg="No se han encontrado resultados para tu búsqueda de producto">
                        </div>
                      </td>
                      <td class="col-sm-1  text-center">
                        <span class="" data-bind="text : AbreviaturaUnidadMedida, attr : { id : IdDetalleNotaSalida() + '_span_AbreviaturaUnidadMedida'}"></span>
                      </td>
                      <td class="col-sm-1">
                        <div class="input-group">
                          <input name="Cantidad" class="NotaSalida_Todos DetalleNotaSalida_Cantidad form-control formulario numeric text-mumeric"
                          data-bind="value : Cantidad , attr : { id : IdDetalleNotaSalida() + '_input_Cantidad','data-cantidad-decimal' : DecimalCantidad() },
                          event: {focus : function(data,event) { return OnFocus(data,event,$parent.OnFocus,$parent.CrearDetalleNotaSalida); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout : ValidarCantidad } , numberdecimal : Cantidad" type="text"
                          data-validation="number_calc" data-validation-allowing="float,positive,range[1;9999999]" data-validation-decimal-separator="." data-validation-error-msg="De 0 a más">
                        </div>
                      </td>
                      <td class="NotaSalida_Todos DetalleNotaSalida_ValorUnitario col-sm-1">
                        <input name="ValorUnitario" class="NotaSalida_Todos DetalleNotaSalida_ValorUnitario form-control  formulario numeric text-mumeric inputs"
                        data-bind="value : ValorUnitario ,
                        attr : { id : IdDetalleNotaSalida() + '_input_ValorUnitario','data-cantidad-decimal' : DecimalValorUnitario()  },
                        event: { focus: function(data,event) { return OnFocus(data,event,$parent.OnFocus,$parent.CrearDetalleNotaSalida); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout : ValidarValorUnitario} , numberdecimal : ValorUnitario" type="text"
                        data-validation="number_desc" data-validation-allowing="float,positive,range[0;9999999]" data-validation-decimal-separator="." data-validation-error-msg="Solo positivo">
                      </td>
                      <td class="col-sm-auto">
                        <div class="input-group ajuste-opcion-plusminus">
                          <button type="button" class="DetalleNotaSalida_Eliminar btn btn-default focus-control glyphicon glyphicon-minus no-tab"
                            data-bind="click : $parent.QuitarDetalleNotaSalida,  visible: EstadoInputOpcion(),
                              event : { focus: function(data,event) { return OnFocus(data,event,$parent.OnFocus,$parent.CrearDetalleNotaSalida); } ,keydown : function(data,event) { return OnKeyEnterOpcion(data,event,$parent.OnKeyEnter); }  },
                              attr : { id : IdDetalleNotaSalida() + '_a_opcion'}" ></button>
                        </div>
                      </td>
                    </tr>
                    <!-- /ko -->
                  </tbody>
                </table>
              </div>
            </fieldset>
          </div>
          <div class="row">
						<div class="col-md-10">
						</div>
						<div class="col-md-2 text-right">
							<span data-bind="html : 'CANT. TOTAL : '+TotalCantidades()"></span>
						</div>
					</div>
          <br>
          <div class="row">
            <center>
              <button type="button" id="btn_Grabar" class="btn btn-success focus-control" data-bind="click : Guardar">Grabar</button> &nbsp;
              <button type="button" id="btn_Limpiar" class="btn btn-default focus-control" data-bind="click : Limpiar, visible : opcionProceso() == 1">Limpiar</button> &nbsp;
              <button type="button" id="BtnDeshacer" class="btn btn-default" data-bind="click : Deshacer, visible : opcionProceso() == 2">Deshacer</button> &nbsp;
              <button type="button" id="btn_Cerrar" class="btn btn-default" data-bind="click : OnClickBtnCerrar , visible :  opcionProceso() == 2">Cerrar</button>
            </center>
          </div>

        </div>
      </div>
    </div>
  </div>
</form>
<!-- /ko -->
