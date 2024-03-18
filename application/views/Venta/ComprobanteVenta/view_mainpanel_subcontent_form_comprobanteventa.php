<!-- ko with : ComprobanteVenta -->
<form  id="formComprobanteVenta" name="formComprobanteVenta" role="form" autocomplete="off" >
  <div class="datalist__result">
  <input id="IdTipoDocumento" class="form-control" type="hidden" placeholder="Documento" data-bind="value: IdTipoDocumento">
  <input id="IdTipoVenta" class="form-control" type="hidden" placeholder="Documento" data-bind="value: IdTipoVenta">
  <!-- <input id="IdTipoOperacion" class="form-control" type="hidden" placeholder="TipoOperacion" data-bind="value: IdTipoOperacion">-->
  <input id="IdComprobanteVenta" class="form-control" type="hidden" placeholder="IdComprobanteVenta">
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
                        <div class="input-group-addon formulario-venta">Serie</div>
                        <select name="combo-seriedocumento" id="combo-seriedocumento" class="form-control formulario" data-bind="
                          value : IdCorrelativoDocumento,
                          options : SeriesDocumento,
                          optionsValue : 'IdCorrelativoDocumento' ,
                          optionsText : 'SerieDocumento',
                          event : { focus : OnFocus , change : OnChangeSerieDocumento , keydown : OnKeyEnter}"
                          data-validation="required" data-validation-error-msg="No tiene serie asignada">
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Numero</div>
                        <input id="NumeroDocumento" class="form-control formulario no-tab" type="text" tabindex="-1" data-bind="value: NumeroDocumento ,
                        attr : { readonly : CheckNumeroDocumento }, event : {  focus : OnFocus , focusout : ValidarNumeroDocumento , keydown : OnKeyEnter }"
                        data-validation="number" data-validation-allowing="range[1;99999999]" data-validation-error-msg="El número documento debe ser numérico y tener como máximo 8 digitos"
                        data-validation-depends-on="CheckNumeroDocumento" data-validation-optional="true">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="checkbox">
                          <input name="CheckNumeroDocumento" id="CheckNumeroDocumento" type="checkbox" class="form-control formulario"
                          data-bind="event: { change : OnChangeCheckNumeroDocumento , focus : OnFocus , keydown : OnKeyEnter}" />
                          <label for="CheckNumeroDocumento">Editar Número</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-1">
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">F. Emision</div>
                        <input id="FechaEmision" name="FechaEmision" class="form-control formulario" data-inputmask-clearmaskonlostfocus="false" data-bind="value: FechaEmision, event: {focus : OnFocus , focusout : ValidarFechaEmision ,keydown : OnKeyEnter}"
                        data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha de emision en invalida"/>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">RUC/DNI - Cliente</div>
                        <input id="Cliente" name="Cliente" class="form-control formulario" type="text" data-bind="value : RUCDNICliente(),event : { focus : OnFocus }"
                        data-validation="autocompletado_cliente" data-validation-error-msg="No se han encontrado resultados para tu búsqueda de cliente" data-validation-text-found="">
                        <div class="input-group-btn">
                          <button type="button" class="btn-buscar btn btn-default no-tab" id="BtnNuevoCliente" data-bind="click : function(data,event) {  return OnClickBtnNuevoCliente(data,event,$parent.Cliente); }"><span class="fa fa-plus-circle"></span></button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Forma Pago</div>
                        <select id="combo-formapago" class="form-control formulario" data-bind="
                        value : IdFormaPago,
                        options : FormasPago,
                        optionsValue : 'IdFormaPago' ,
                        optionsText : 'NombreFormaPago' ,
                        event : { change : OnChangeFormaPago , focus : OnFocus , keydown : OnKeyEnter }">
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario-venta">F. Vencimiento</div>
                      <input id="FechaVencimiento" name="FechaVencimiento" class="form-control formulario" data-inputmask-clearmaskonlostfocus="false" data-bind="value: FechaVencimiento, event : {focus : OnFocus , focusout : ValidarFechaVencimiento , keydown : OnKeyEnter}"
                      data-validation="fecha_vencimiento" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha de vencimiento es incorrecta.Solo es obligatoria cuando es forma de pago credito"/>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario-venta">Dirección</div>
                      <input readonly tabindex="-1" class="form-control formulario no-tab" id="Direccion"  data-bind="value : Direccion" type="text" >
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario-venta">Guia Remision</div>
                      <input id="GuiaRemision" class="form-control formulario" type="text" data-bind="value: GuiaRemision ,event : { focus : OnFocus , keydown : OnKeyEnter}"  >
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario-venta">OC:</div>
                      <input id="OrdenCompra" class="form-control formulario" type="text" data-bind="value: OrdenCompra, event : { focus : OnFocus ,keydown : OnKeyEnter }"  >
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <fieldset style="padding: 2px !important;">
                    <legend>
                      <div class="radio radio-inline">
                      <input id="rad6" type="radio" name="radio">
                      <label for="rad6">Afecta kardex</label>
                      </div>
                      <div class="radio radio-inline">
                      <input id="rad6" type="radio" name="radio">
                      <label for="rad6">Pendiente entrega con nota de salida</label>
                      </div>
                    </legend>
                    <div class="col-md-6">
                      <div class="form-group">
                        <div class="input-group">
                          <div class="input-group-addon formulario-venta">Almacen</div>
                          <select id="combo-formapago" class="form-control formulario" data-bind="
                            value : IdFormaPago,
                            options : FormasPago,
                            optionsValue : 'IdFormaPago' ,
                            optionsText : 'NombreFormaPago' ,
                            event : { change : OnChangeFormaPago , focus : OnFocus , keydown : OnKeyEnter }"
                            data-validation="required" data-validation-error-msg="No tiene almacen asignado">
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <div class="input-group">
                          <div class="input-group-addon formulario-venta">Fecha</div>
                          <input id="FechaVencimiento" name="FechaVencimiento" class="form-control formulario" data-inputmask-clearmaskonlostfocus="false" data-bind="value: FechaVencimiento, event : {focus : OnFocus , focusout : ValidarFechaVencimiento , keydown : OnKeyEnter}"
                          data-validation="fecha_vencimiento" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha de vencimiento es incorrecta.Solo es obligatoria cuando es forma de pago credito"/>
                        </div>
                      </div>
                    </div>
                  </fieldset>
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
                    <table class="datalist__table table display grid-detail-body table-border" width="100%" id="tablaDetalleComprobanteVenta">
                      <thead>
                        <tr>
                          <th class="col-sm-1 products__id"><center>Código</center></th>
                          <th class="col-sm-6 products__title">Descripción</th>
                          <th class="col-sm-1 products__title"><center>Unidad</center></th>
                          <th class="col-sm-1 products__title"><center>Cantidad</center></th>
                          <th class="col-sm-1 products__title"><center>P. U.</center></th>
                          <th class="col-sm-1 products__title"><center>Desc.</center></th>
                          <th class="col-sm-1 products__title"><center>Importe</center></th>
                          <th class="col-sm-1 products__title"></th>
                        </tr>
                      </thead>
                      <tbody>
                        <!-- ko foreach : DetallesComprobanteVenta -->
                        <tr name="Fila" class="clickable-row" style="min-height: 80px;" data-bind="attr : { id: IdDetalleComprobanteVenta }, click :  function(data,event) { return OnClickFila(data,event,$parent.OnRefrescar); } ">
                          <td class="col-sm-1">
                            <div class="input-group">
                              <input class="form-control formulario"
                              data-bind="value: CodigoMercaderia, valueUpdate : 'keyup',
                              attr : { id : IdDetalleComprobanteVenta() + '_input_CodigoMercaderia'},
                              event : {focus : function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnterCodigoMercaderia(data,event,$parent.OnKeyEnter); }  }" type="text"
                              data-validation="validacion_producto" data-validation-error-msg="Cod. Inválido"
                              data-validation-found="false"  data-validation-text-found="" ><!-- , focusout : ValidarCodigoMercaderia -->
                            </div>
                          </td>
                          <td class="col-sm-6">
                            <div class="input-group">
                              <input class="form-control formulario"
                              data-bind="value: NombreProducto,
                              attr : { id : IdDetalleComprobanteVenta() + '_input_NombreProducto',
                              'data-validation-reference' : IdDetalleComprobanteVenta() + '_input_CodigoMercaderia'  },
                              event: { focus : function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); }  }" type="text"
                              data-validation="autocompletado_producto" data-validation-error-msg="No se han encontrado resultados para tu búsqueda de producto">
                            </div>
                          </td>
                          <td class="col-sm-1  text-center">
                            <span class="" data-bind="text : AbreviaturaUnidadMedida, attr : { id : IdDetalleComprobanteVenta() + '_span_AbreviaturaUnidadMedida'}"></span>
                          </td>
                          <td class="col-sm-1">
                            <div class="input-group">
                              <input name="Cantidad" class="form-control formulario numeric text-mumeric"
                              data-bind="value : Cantidad , attr : { id : IdDetalleComprobanteVenta() + '_input_Cantidad' },
                              event: {focus : function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout : ValidarCantidad } , numbertrim : Cantidad" type="text"
                              data-validation="number" data-validation-allowing="float,positive,range[0.001;9999999]" data-validation-decimal-separator="." data-validation-error-msg="De 0 a más">
                            </div>
                          </td>
                          <td class="col-sm-1">
                            <input name="PrecioUnitario" class="form-control  formulario numeric text-mumeric inputs"
                            data-bind="value : PrecioUnitario ,
                            attr : { id : IdDetalleComprobanteVenta() + '_input_PrecioUnitario' },
                            event: { focus: function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout : ValidarPrecioUnitario} , numbertrim : PrecioUnitario" type="text"
                            data-validation="number" data-validation-allowing="float,positive,range[0.001;9999999]" data-validation-decimal-separator="." data-validation-error-msg="Solo positivo">
                          </td>
                          <td class="col-sm-1">
                            <input name="DescuentoUnitario" class="form-control formulario numeric text-mumeric inputs"
                            data-bind="value : DescuentoUnitario ,
                            attr : { id : IdDetalleComprobanteVenta() + '_input_DescuentoUnitario' },
                            event: { focus: function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout : ValidarDescuentoUnitario} , numbertrim : DescuentoUnitario" type="text"
                            data-validation="number_desc" data-validation-allowing="float,positive,range[0;9999999]" data-validation-decimal-separator="." data-validation-error-msg="De 0 a más">
                          </td>
                          <td class="col-sm-1">
                            <div class="input-group">
                              <input readonly tabindex="-1" class="form-control  formulario text-right no-tab"
                              data-bind="value : CalculoSubTotal() , attr : { id : IdDetalleComprobanteVenta() + '_span_SubTotal'},event : { focus: function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } }"
                              type="text">
                            </div>
                          </td>
                          <td class="col-sm-auto">
                            <div class="input-group ajuste-opcion-plusminus">
                              <button type="button" class="btn btn-default focus-control glyphicon glyphicon-minus no-tab"
                              data-bind="click : function(data,event) {  return OnClickBtnOpcion(data,event,$parent.OnQuitarFila);  },
                              event : { focus: function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } ,keydown : function(data,event) { return OnKeyEnterOpcion(data,event,$parent.OnKeyEnter); }  },
                              attr : { id : IdDetalleComprobanteVenta() + '_a_opcion'}" ></button>
                            </div>
                          </td>
                        </tr>
                        <!-- /ko -->
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
          </div>

          <div class="row">
            <div class="col-md-12 denominacion-moneda-nacional">
              <span data-bind="html : DenominacionTotal()" id="nletras"></span>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2">
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <div class="addon-top">Op. Gravada</div>
                <input id="ValorVentaGravado" tabindex="-1" readonly class="form-control formulario numeric text-mumeric no-tab input-totales" type="text" placeholder="Op. Grav." data-bind="value: CalculoTotalVentaGravado()">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <div class="addon-top">Op. Exonerado</div>
                <input id="ValorVentaNoGravado" tabindex="-1" readonly class="form-control formulario numeric text-mumeric no-tab input-totales" type="text" placeholder="Op. No Grav." data-bind="value: CalculoTotalVentaNoGravado()">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <div class="addon-top">(Descto. Global)</div>
                <input id="DescuentoGlobal" class="form-control formulario numeric text-mumeric input-totales" type="text" placeholder="Des. Global" data-bind="value: DescuentoGlobal, numbertrim : DescuentoGlobal,
                  event : {  focus: OnFocus , focusout : ValidarDescuentoGlobal , keydown : OnKeyEnter }">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <div class="addon-top">IGV</div>
                <input id="IGV" readonly tabindex="-1" class="form-control formulario numeric text-mumeric no-tab input-totales" type="text" placeholder="IGV" data-bind="value: CalculoTotalIGV()">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <div class="addon-top">Total</div>
                <input id="Total" readonly tabindex="-1" class="form-control formulario numeric text-mumeric no-tab input-totales" type="text" placeholder="Total" data-bind="value : CalculoTotalVenta()">
              </div>
            </div>
          </div>
          <div class="row">
            <center>
              <br>
              <button type="button" id="btn_Grabar" class="btn btn-success focus-control" data-bind="click : Guardar">Grabar</button> &nbsp;
              <button type="button" id="btn_Limpiar" class="btn btn-default focus-control" data-bind="click : Limpiar, visible : opcionProceso() == 1">Limpiar</button> &nbsp;
              <button type="button" id="BtnDeshacer" class="btn btn-default" data-bind="click : Deshacer, visible : opcionProceso() == 2">Deshacer</button> &nbsp;
              <button type="button" id="btn_Cerrar" class="btn btn-default" data-bind="click : OnClickBtnCerrar , visible :  opcionProceso() == 2">Cerrar</button>
              <br>
              <p>
            </center>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
<!-- /ko -->
