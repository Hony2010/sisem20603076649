<!-- ko with : BoletaMasiva -->
<form  id="formBoletaMasiva" name="formBoletaMasiva" role="form" autocomplete="off" >
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
                  <div class="col-md-2">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">Serie</div>
                        <select id="combo-sede" class="form-control formulario" data-bind="
                          value : IdCorrelativoDocumento,
                          options : SeriesDocumento,
                          optionsValue : 'IdCorrelativoDocumento' ,
                          optionsText : 'SerieDocumento'">
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">RUC/DNI - Cliente</div>
                        <input id="Cliente" name="Cliente" class="form-control formulario" type="text" data-bind="value : RUCDNICliente(),event : { focus : OnFocus }"
                        data-validation="autocompletado_cliente" data-validation-error-msg="No se han encontrado resultados para tu búsqueda de cliente" data-validation-text-found="">
                        <!-- <div class="input-group-addon unir"></div> -->
                        <!-- <button type="button" class="form-control btn-buscar btn btn-default no-tab" id="BtnNuevoCliente" data-bind="click : function(data,event) {  return OnClickBtnNuevoCliente(data,event,$parent.Cliente); }"><span class="fa fa-plus-circle"></span></button> -->
                      </div>
                    </div>
                  </div>
                  <div class="col-md-1">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="checkbox">
                          <input disabled name="CheckCliente" id="CheckCliente" type="checkbox" class="form-control formulario"
                          data-bind="event: { change : OnChangeCheckCliente , focus : OnFocus , keydown : OnKeyEnter}" />
                          <label for="CheckCliente">Editar</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">Almacén</div>
                        <select id="combo-sede" class="form-control formulario" data-bind="
                          value : IdAsignacionSede,
                          options : Sedes,
                          optionsValue : 'IdAsignacionSede' ,
                          optionsText : 'NombreSede'">
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon">Moneda</div>
                        <select id="combo-moneda" class="form-control formulario" data-bind="
                        value : IdMoneda,
                        options : Monedas,
                        optionsValue : 'IdMoneda' ,
                        optionsText : 'NombreMoneda',event : { change : OnChangeMoneda },
                        event : {focus : OnFocus, keydown : OnKeyEnter} ">
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-1">
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
            <fieldset>
              <div class="col-md-12">
                <div class="row detalle-comprobante">
                  <div class="col-md-12">
                    <table class="datalist__table table display grid-detail-body table-border" width="100%" id="tablaDetalleComprobanteVenta">
                      <thead>
                        <tr>
                          <th class="col-sm-1 products__id codigo-producto"><center>Serie/Documento</center></th>
                          <th class="col-sm-3 products__title">Cliente</th>
                          <!-- <th class="col-sm-1 products__title op-mercaderia"></th> -->
                          <th class="col-sm-1 products__title op-unidad"><center>Fecha</center></th>
                          <th class="col-sm-1 products__title op-cantidad"><center>FormaPago</center></th>
                          <th class="col-sm-6 products__title"><center>Codigo Producto</center></th>
                          <th class="col-sm-6 products__title"><center>Nombre Producto</center></th>
                          <th class="col-sm-6 products__title"><center>Unidad</center></th>
                          <th class="col-sm-6 products__title"><center>Cantidad</center></th>
                          <th class="col-sm-6 products__title"><center>PrecioUnitario</center></th>
                          <th class="col-sm-6 products__title"><center>SubTotal</center></th>
                          <!-- <th class="col-sm-1 products__title"></th> -->
                        </tr>
                      </thead>
                      <tbody>
                        <!-- ko foreach : $root.data.ComprobantesMasivo -->
                        <tr name="Fila" class="clickable-row" style="min-height: 80px;" data-bind="">
                          <td>
                            <span data-bind="text: SerieDocumento"></span>
                          </td>
                          <td>
                            <span data-bind="text: Cliente"></span>
                          </td>
                          <td>
                            <span data-bind="text: FechaEmision"></span>
                          </td>
                          <td>
                            <span data-bind="text: FormaPago"></span>
                          </td>
                          <td>
                            <span data-bind="text: CodigoProducto"></span>
                          </td>
                          <td>
                            <span data-bind="text: NombreProducto"></span>
                          </td>
                          <td>
                            <span data-bind="text: Unidad"></span>
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
                          <!-- <td>
                            <span data-bind="text: Producto"></span>
                          </td>
                          <td>
                            <span data-bind="text: Unidad"></span>
                          </td>
                          <td>
                            <span data-bind="text: Cantidad"></span>
                          </td>
                          <td>
                            <span data-bind="text: PrecioUnitario"></span>
                          </td>
                          <td>
                            <span data-bind="text: SubTotal"></span>
                          </td> -->
                          <!-- <td class="col-sm-1">
                            <div class="input-group">
                              <input class="form-control formulario"
                              data-bind="value: CodigoMercaderia, valueUpdate : 'keyup',
                              attr : { id : IdDetalleComprobanteVenta() + '_input_CodigoMercaderia'},
                              event : {focus : function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnterCodigoMercaderia(data,event,$parent.OnKeyEnter); }  }" type="text"
                              data-validation="validacion_producto" data-validation-error-msg="Cod. Inválido"
                              data-validation-found="false"  data-validation-text-found="" >
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
                          </td> -->
                          <!-- <td>
                            <button type="button" style="background: none; border: none;" class="btn-default btn-buscar btn-control" name="button" data-bind="click : function(data,event) {  return $parent.OnClickBtnNuevaMercaderia(data,event,$root.data.Mercaderia);},attr : { id : IdDetalleComprobanteVenta() + '_opcion_mercaderia'}">
                              <i class="fas fa-plus"></i>
                            </button>
                          </td> -->
                          <!-- <td class="col-sm-1  text-center">
                            <span class="" data-bind="text : AbreviaturaUnidadMedida, attr : { id : IdDetalleComprobanteVenta() + '_span_AbreviaturaUnidadMedida'}"></span>
                          </td>
                          <td class="col-sm-1">
                            <div class="input-group">
                              <input name="Cantidad" class="form-control formulario numeric text-mumeric"
                              data-bind="value : Cantidad , attr : { id : IdDetalleComprobanteVenta() + '_input_Cantidad' },
                              event: {focus : function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout : ValidarCantidad } , numbertrim : Cantidad" type="text"
                              data-validation="number_calc" data-validation-allowing="float,positive,range[0.001;9999999]" data-validation-decimal-separator="." data-validation-error-msg="De 0 a más">
                            </div>
                          </td>
                          <td class="col-sm-1">
                            <input name="PrecioUnitario" class="form-control  formulario numeric text-mumeric inputs"
                            data-bind="value : PrecioUnitario ,
                            attr : { id : IdDetalleComprobanteVenta() + '_input_PrecioUnitario' },
                            event: { focus: function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout : ValidarPrecioUnitario} , numbertrim : PrecioUnitario" type="text"
                            data-validation="number_calc" data-validation-allowing="float,positive,range[0.001;9999999]" data-validation-decimal-separator="." data-validation-error-msg="Solo positivo">
                          </td>
                          <td class="col-sm-1">
                            <input name="DescuentoItem" class="form-control formulario numeric text-mumeric inputs"
                            data-bind="value : DescuentoItem ,
                            attr : { id : IdDetalleComprobanteVenta() + '_input_DescuentoItem' },
                            event: { focus: function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout : ValidarDescuentoItem} , numbertrim : DescuentoItem" type="text"
                            data-validation="number" data-validation-allowing="float,positive,range[0;9999999]" data-validation-decimal-separator="." data-validation-error-msg="De 0 a más">
                          </td>
                          <td class="col-sm-1">
                            <div class="input-group">
                              <input readonly tabindex="-1" class="form-control  formulario text-right no-tab"
                              data-bind="value : CalculoSubTotal() , attr : { id : IdDetalleComprobanteVenta() + '_span_SubTotal'},event : { focus: function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } }"
                              type="text">
                            </div>
                          </td> -->
                          <!-- <td class="col-sm-auto">
                            <div class="input-group ajuste-opcion-plusminus">
                              <button type="button" class="btn btn-default focus-control glyphicon glyphicon-minus no-tab"
                              data-bind="click : function(data,event) {  return OnClickBtnOpcion(data,event,$parent.OnQuitarFila);  },
                              event : { focus: function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } ,keydown : function(data,event) { return OnKeyEnterOpcion(data,event,$parent.OnKeyEnter); }  },
                              attr : { id : IdDetalleComprobanteVenta() + '_a_opcion'}" ></button>
                            </div>
                          </td> -->
                        </tr>
                        <!-- /ko -->
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </fieldset>
          </div>

          <div class="row">
            <div class="col-md-2">
              <br>
              * Grabar = ALT + G
            </div>
            <div class="col-md-8">
              <center>
                <br>
                <button type="button" id="btn_Grabar" class="btn btn-success focus-control" data-bind="click : Guardar">Grabar</button> &nbsp;
                <!-- <button type="button" id="btn_Limpiar" class="btn btn-default focus-control" data-bind="click : Limpiar, visible : opcionProceso() == 1">Limpiar</button> &nbsp;
                <button type="button" id="BtnDeshacer" class="btn btn-default" data-bind="click : Deshacer, visible : opcionProceso() == 2">Deshacer</button> &nbsp;
                <button type="button" id="btn_Cerrar" class="btn btn-default" data-bind="click : OnClickBtnCerrar , visible :  opcionProceso() == 2">Cerrar</button> -->
                <br>
              </center>
            </div>
            <div class="col-md-2">
              &nbsp;
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
<!-- /ko -->
