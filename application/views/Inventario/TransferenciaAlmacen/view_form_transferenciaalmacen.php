<!-- ko with : TransferenciaAlmacen -->
<form  id="formTransferenciaAlmacen" name="formTransferenciaAlmacen" role="form" autocomplete="off" enctype='multipart/form-data'>
  <div class="datalist__result">
    <input id="IdTipoDocumento" class="form-control" type="hidden" placeholder="Documento" data-bind="value: IdTipoDocumento">
    <input id="IdTransferenciaAlmacen" class="form-control" type="hidden" placeholder="IdTransferenciaAlmacen">
  
        <div class="container-fluid">
          <div class="row">
              <fieldset>
                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <div class="input-group">
                          <div class="input-group-addon formulario-venta">Serie</div>
                          <select name="combo-seriedocumento" id="combo-seriedocumento" class="form-control formulario" data-bind="
                          value : IdCorrelativoDocumento,
                          options : SeriesDocumento,
                          optionsValue : 'IdCorrelativoDocumento',
                          optionsText : 'SerieDocumento',
                          event : { focus : OnFocus , change : OnChangeSerieTransferencia , keydown : OnKeyEnter}" 
                          data-validation="required" data-validation-error-msg="No tiene serie asignada">
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <div class="input-group">
                          <div class="input-group-addon formulario-venta">Número</div>
                          <input id="NumeroTransferencia" readonly  class="form-control formulario no-tab" type="text" tabindex="-1" data-bind="
                          value: NumeroTransferencia ,
                          event : {  focus : OnFocus , focusout : OnFocusOutNumeroTransferencia , keydown : OnKeyEnter }" 
                          data-validation="number" data-validation-allowing="range[1;99999999]" 
                          data-validation-error-msg="El número documento debe ser numérico y tener como máximo 8 digitos" 
                          data-validation-optional="true">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <div class="input-group">
                          <div class="input-group-addon formulario-venta">Fecha de Traslado</div>
                          <input id="FechaTraslado" name="FechaTraslado" class="form-control formulario" 
                          data-inputmask-clearmaskonlostfocus="false" 
                          data-bind="value: FechaTraslado, event: {focus : OnFocus , focusout : OnFocusOutFechaTraslado ,keydown : OnKeyEnter}"
                          data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha de traslado en inválida"/>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <div class="input-group">
                          <div class="input-group-addon formulario-venta">Almacen Origen</div>
                          <select  name="combo-motivoTransferenciaAlmacenOrigen" id="combo-motivoTransferenciaAlmacenOrigen" 
                          class="form-control formulario" data-bind="
                            value : IdAsignacionSedeOrigen,
                            options : Sedes,
                            optionsValue : 'IdAsignacionSede' ,
                            optionsText : 'NombreSede',
                            event : { focus : OnFocus , change : OnChangeSedeOrigen, keydown : OnKeyEnter}">
                          </select>
                        </div>
                      </div>
                    </div>                    
                    <div class="col-md-6">
                      <div class="form-group">
                        <div class="input-group">
                          <div class="input-group-addon formulario-venta">Guia Remisión</div>
                          <input id="GuiaRemisionEmisor" class="form-control formulario" type="text" data-bind="value: GuiaRemisionEmisor, event:{keydown : OnKeyEnter}" value="GuiaRemisionEmisor">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <div class="input-group">
                          <div class="input-group-addon formulario-venta">Almacen Destino</div>
                          <select name="combo-motivoTransferenciaAlmacenDestino" id="combo-motivoTransferenciaAlmacenDestino" class="form-control formulario"
                           data-bind="
                            value : IdAsignacionSedeDestino,
                            options : Sedes,
                            optionsValue : 'IdAsignacionSede',
                            optionsText : 'NombreSede',
                            event : { focus : OnFocus , change : OnChangeSedeDestino, keydown : OnKeyEnter}">
                          </select>
                        </div>
                      </div>
                    </div>                    
                    <div class="col-md-6">
                      <div class="form-group">
                        <div class="input-group">
                          <div class="input-group-addon formulario-venta">Observación</div>
                          <input id="Observacion" class="form-control formulario" type="text" data-bind="value: Observacion, event:{keydown : OnKeyEnter}" value="Observacion">
                        </div>
                      </div>
                    </div>
                  </div>                  
                </div>              
            </fieldset>
          </div>
        
          <br/>
          <div class="row">
              <div class="col-md-12">
                <div class="row detalle-comprobante">
                  <div class="col-md-12">
                    <fieldset>
                      <table class="datalist__table table display grid-detail-body table-border" width="100%" id="tablaTransferenciaAlmacen">
                        <thead>
                          <tr>
                            <th class="col-sm-1 products__id"><center>Código</center></th>
                            <th class="col-sm-7 products__title">Descripción Producto</th>
                            
                            <th class="col-sm-2 products__title"><center>Unidad</center></th>
                            <th class="col-sm-1 products__title"><center>Cantidad</center></th>
                            <th class="col-sm-1 products__title"><center>Costo Unitario</center></th>
                            <th class="col-sm-1 products__title"></th>
                          </tr>
                        </thead>
                        <tbody>
                          <!-- ko foreach : DetallesTransferenciaAlmacen -->
                          <td class="col-sm-1">
                            <div class="input-group">
                              <input class="TransferenciaAlmacen_Todos TransferenciaAlmacen_Codigo form-control formulario"
                              data-bind="value: CodigoMercaderia, valueUpdate : 'keyup',
                              attr : { id : IdDetalleTransferenciaAlmacen() + '_input_CodigoMercaderia'},
                              event : {focus : function(data,event) { return OnFocus(data,event,$parent.OnFocus,$parent.CrearTransferenciaAlmacen); } , 
                                       keydown : function(data,event) { return OnKeyEnterCodigoMercaderia(data,event,$parent.OnKeyEnter); } , 
                                       focusout : ValidarCodigoMercaderia }" type="text"
                              data-validation="validacion_producto" data-validation-error-msg="Cod. Inválido"
                              data-validation-found="false"  data-validation-text-found="" >
                            </div>
                          </td>
                          <td class="col-sm-7">
                            <div class="input-group">
                              <input class="TransferenciaAlmacen_Todos TransferenciaAlmacen_Descripcion form-control formulario"
                              data-bind="value: NombreProducto,
                              attr : { id : IdDetalleTransferenciaAlmacen() + '_input_NombreProducto',
                              'data-validation-reference' : IdDetalleTransferenciaAlmacen() + '_input_CodigoMercaderia'  },
                              event: { focus : function(data,event) { return OnFocus(data,event,$parent.OnFocus,$parent.CrearTransferenciaAlmacen); }  }" type="text"
                              data-validation="autocompletado_producto" data-validation-error-msg="No se han encontrado resultados para tu búsqueda de producto">
                            </div>
                          </td>
                          
                          <td class="col-sm-2  text-center">                            
                            <span class="" data-bind="text : AbreviaturaUnidadMedida, attr : { id : IdDetalleTransferenciaAlmacen() + '_span_AbreviaturaUnidadMedida'} "></span>
                          </td>
                          <td class="col-sm-1">
                            <div class="input-group">
                              <input name="Cantidad" class="TransferenciaAlmacen_Todos TransferenciaAlmacen_Cantidad form-control formulario numeric text-mumeric"
                              data-bind="value : Cantidad , attr : { id : IdDetalleTransferenciaAlmacen() + '_input_Cantidad' },
                              event: {focus : function(data,event) { return OnFocus(data,event,$parent.OnFocus,$parent.CrearTransferenciaAlmacen); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout : OnFocusOutCantidad } , numbertrim : Cantidad" type="text"
                              data-validation="number_desc" data-validation-allowing="float,positive,range[0.001;9999999]" data-validation-decimal-separator="." data-validation-error-msg="De 0 a más">
                            </div>
                          </td>
                          <td class="TransferenciaAlmacen_Todos TransferenciaAlmacen_ValorUnitario col-sm-1">
                            <input name="ValorUnitario" class="TransferenciaAlmacen_Todos TransferenciaAlmacen_ValorUnitario form-control  formulario numeric text-mumeric inputs"
                            data-bind="value : ValorUnitario ,
                            attr : { id : IdDetalleTransferenciaAlmacen() + '_input_ValorUnitario' },
                            event: { focus: function(data,event) { return OnFocus(data,event,$parent.OnFocus,$parent.CrearTransferenciaAlmacen); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout : OnFocusOutValorUnitario} , numbertrim : ValorUnitario" type="text"
                            data-validation="number_desc" data-validation-allowing="float,positive,range[0.001;9999999]" data-validation-decimal-separator="." data-validation-error-msg="Solo positivo">
                          </td>
                          <td class="col-sm-auto">
                            <div class="input-group ajuste-opcion-plusminus">
                              <button type="button" class="TransferenciaAlmacen_Todos TransferenciaAlmacen_Eliminar btn btn-default focus-control glyphicon glyphicon-minus no-tab"
                              data-bind="click : $parent.QuitarDetalleTransferenciaAlmacen,
                              event : { focus: function(data,event) { return OnFocus(data,event,$parent.OnFocus,$parent.CrearTransferenciaAlmacen); } ,keydown : function(data,event) { return OnKeyEnterOpcion(data,event,$parent.OnKeyEnter); }  },
                              attr : { id : IdDetalleTransferenciaAlmacen() + '_a_opcion'}" ></button>
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
              <button type="button" id="BtnDeshacer" class="btn btn-default" data-bind="click : Deshacer, visible : opcionProceso() == 2">Deshacer</button> &nbsp;
              <button type="button" id="btn_Cerrar" class="btn btn-default btn-close" data-bind="click : OnClickBtnCerrar , visible :  opcionProceso() == 2">Cerrar</button>
              <br>
            </center>
          </div>
        </div>

  </div>
</form>
<!-- /ko -->
