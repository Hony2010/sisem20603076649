<!-- ko with : NotaCreditoCompra  -->
<form id="formNotaCreditoCompra" name="formNotaCreditoCompra" role="form" autocomplete="off" data-bind="event : {change : OnChangeNotaCreditoCompra}">
  <div class="row">
    <div class="container-fluid">
      <div class="col-md-12">
        <div class="row">
          <input id="IdTipoDocumento" class="form-control" type="hidden" placeholder="Documento" data-bind="value: IdTipoDocumento">
          <input id="IdTipoCompra" class="form-control" type="hidden" placeholder="Documento" data-bind="value: IdTipoCompra">
          <input id="IdComprobanteCompra" class="form-control" type="hidden" placeholder="IdComprobanteCompra">
          <input id="IdProveedor" class="form-control" type="hidden" placeholder="RUC/DNI:" data-bind="value: IdProveedor">
          <input id="IdPersona" class="form-control" type="hidden" placeholder="Persona:" data-bind="value: IdPersona">
          <div class="col-md-12">
            <fieldset>
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Serie</div>
                        <input id="SerieDocumento" class="form-control formulario" type="text" data-bind="value: SerieDocumento, event:{focus : OnFocus , focusout : ValidarSerieDocumento , keydown : OnKeyEnter}" data-validation="required" data-validation-error-msg="Debe Ingresar una Serie">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Numero</div>
                        <input id="NumeroDocumento" class="form-control formulario" type="text" data-bind="value: NumeroDocumento , attr : { readonly : CheckNumeroDocumento }, event : { focusout : ValidarNumeroDocumento, keydown : OnKeyEnter}" data-validation="number" data-validation-allowing="range[1;99999999]" data-validation-error-msg="El número documento debe ser numérico y tener como máximo 8 digitos" data-validation-depends-on="CheckNumeroDocumento" data-validation-optional="false">
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
                        <div class="input-group-addon formulario-venta">F. Emision</div>
                        <input id="FechaEmision" name="FechaEmision" class="form-control formulario" data-inputmask-clearmaskonlostfocus="false" data-bind="value: FechaEmision, event: {focusout : ValidarFechaEmision, keydown : OnKeyEnter, change: OnChangeFechaEmision}" data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha de emision en invalida" />
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario formulario-venta">Motivo</div>
                        <select name="combo-motivo" id="combo-motivo" class="form-control formulario" data-bind="
                        value : IdMotivoNotaCredito,
                        options : MotivosNotaCreditoCompra,
                        optionsValue : 'IdMotivoNotaCredito' ,
                        optionsText : 'NombreMotivoNotaCredito',
                        event:{change: CambiarMotivoNotaCreditoCompra, keydown : OnKeyEnter}">
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Observación</div>
                        <input id="Observacion" class="form-control formulario" type="text" data-bind="value: Observacion, event:{keydown : OnKeyEnter}" value="Jorge">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Periodo Registro</div>
                        <select id="combo-periodo" class="form-control formulario" data-bind="
                        value : IdPeriodo,
                        options : Periodos,
                        optionsValue : 'IdPeriodo' ,
                        optionsText : 'NombrePeriodo' ,
                        event : { change : OnChangePeriodo , focus : OnFocus , keydown : OnKeyEnter }">
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- ko if: VistaPendienteNota() == 1 -->
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="checkbox">
                          <input id="CheckPendiente" class="form-control formulario" type="checkbox" name="CheckPendiente" data-bind="checked: CheckPendiente, event:{keydown : OnKeyEnter, change: OnChangeCheckPendiente}">
                          <label for="CheckPendiente">Pendiente Nota Salida</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /ko -->
                <div class="row">
                  <div class="col-md-7">
                    <fieldset>
                      <legend>Datos Proveedor</legend>
                      <div class="form-group">
                        <div class="input-group">
                          <div class="input-group-addon formulario">RUC/DNI - Proveedor</div>
                          <input id="ProveedorNC" name="ProveedorNC" class="Proveedor form-control formulario" type="text" data-bind="value : RUCDNICliente(),event : { focus : OnFocus }" data-validation="autocompletado_proveedor" data-validation-error-msg="No se han encontrado resultados para tu búsqueda de proveedor" data-validation-text-found="">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="form-group">
                          <div class="input-group">
                            <div class="input-group-addon formulario">Direccion:</div>
                            <input readonly tabindex="-1" class="form-control formulario" onfocus="blur()" id="Direccion" data-bind="value : Direccion" type="text">
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                  <!-- ko if: VistaAlmacen() == 1 -->
                  <div id="GrupoAlmacen" class="col-md-5">
                    <fieldset>
                      <legend>Movim. de Almacén</legend>
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                              <div class="input-group">
                                <div class="input-group-addon formulario">Almacen</div>
                                <select id="combo-sede" class="form-control formulario" data-bind="
                                value : IdAsignacionSede,
                                options : Sedes,
                                optionsValue : 'IdAsignacionSede' ,
                                optionsText : 'NombreSede',
                                event: {keydown : OnKeyEnter, change: OnChangeAlmacen}">
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="form-group">
                              <div class="input-group">
                                <div class="input-group-addon formulario">F. Salida</div>
                                <input disabled tabindex="-1" id="FechaIngreso" name="FechaIngreso" class="form-control formulario no-tab" data-inputmask-clearmaskonlostfocus="false" data-bind="value: FechaIngreso, event:{keydown : OnKeyEnter}" data-validation="fecha_vencimiento" data-validation-format="dd/mm/yyyy" data-validation-error-msg="" />
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                  <!-- /ko -->
                  <!-- ko if: (ParametroCaja() == 1)-->
                  <div class="col-md-5" data-bind="visible : IdFormaPago() == ID_FORMA_PAGO_CONTADO">
                    <fieldset style="padding: 2px !important;">
                      <legend>Caja</legend>
                      <div class="col-md-12">
                        <div class="form-group">
                          <div class="input-group">
                            <div class="input-group-addon formulario-venta">Cajas</div>
                            <select id="combo-caja" class="form-control formulario" data-bind="event:{ change: OnChangeCajas, focus : OnFocus, keydown : OnKeyEnter}" data-validation="required" data-validation-error-msg="No tiene caja asignado">
                            </select>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                  <!-- /ko -->
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <fieldset>
                      <legend>Documento Referencia</legend>
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-3">
                            <div class="form-group">
                              <div class="input-group">
                                <div class="input-group-addon">Tipo Documento</div>
                                <select id="combo-tipodocumento" class="form-control formulario" data-bind="
                              options : $parent.TiposDocumento,
                              optionsValue : 'id' ,
                              optionsText : 'value',
                              event:{keydown : OnKeyEnter}">
                                </select>
                              </div>
                            </div>
                            <button id="btn_buscardocumentoreferencia" class="btn btn-primary btn-control" type="button" name="button" data-bind="click: AbrirConsultaComprobanteCompra">Buscar</button>
                          </div>
                          <div class="col-md-9" style="overflow-x: scroll;">
                            <table class="datalist__table table display no-footer" width="100%" data-products="brand">
                              <thead>
                                <tr>
                                  <th class="products__title">Serie/Numero</th>
                                  <th class="products__title">Fecha</th>
                                  <th class="products__title">Forma Pago</th>
                                  <th class="products__title">Gravada</th>
                                  <th class="products__title">No Gravada</th>
                                  <th class="products__title">Des. Global</th>
                                  <th class="products__title">IGV</th>
                                  <th class="products__title">Total</th>
                                  <th class="products__title">Saldo</th>
                                  <th class="products__title">&nbsp;</th>
                                </tr>
                              </thead>
                              <tbody>
                                <!-- ko foreach : MiniComprobantesCompraNC -->
                                <tr class="clickable-row" data-bind="" style="text-transform: UpperCase;">
                                  <td data-bind="text: Documento">F001-1286</td>
                                  <td data-bind="text: FechaEmision">10/05/2018</td>
                                  <td data-bind="text: NombreFormaPago">CONTADO</td>
                                  <td data-bind="text: ValorCompraGravado">100.00</td>
                                  <td data-bind="text: ValorCompraNoGravado">00.00</td>
                                  <td data-bind="text: DescuentoGlobal">00.00</td>
                                  <td data-bind="text: IGV">18.00</td>
                                  <td data-bind="text: Total">118.00</td>
                                  <td data-bind="text: SaldoNotaCredito">100.00</td>
                                  <td class="col-sm-auto">
                                    <div class="input-group ajuste-opcion-plusminus">
                                      <button type="button" class="btn btn-danger no-tab" data-bind="event:{click: EliminarComprobanteCompra}">X</button>
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
            </fieldset>
          </div>
        </div>
        <br>

        <!-- ko if: VistaConceptoODetalle() == '0' -->
        <div class="row">
          <div class="col-md-12">
            <fieldset>
              <table id="tablaDetalleComprobanteCompra" class="datalist__table table display no-footer" width="100%" data-products="brand">
                <thead>
                  <tr>
                    <th class="col-sm-1 products__id codigo-producto">
                      <center>Código</center>
                    </th>
                    <th class="col-sm-4 products__title">Descripción</th>
                    <th class="col-sm-1 products__title unidad-producto">
                      <center>Unidad</center>
                    </th>
                    <th class="col-sm-1 products__title cantidad-producto">
                      <center>Cantidad</center>
                    </th>
                    <!-- ko if: ParametroPrecioCompra() == 1 -->
                    <th class="col-sm-1 products__title preciounitario-producto">
                      <c>P. U.</c>
                    </th>
                    <!-- /ko -->
                    <!-- ko if: ParametroPrecioCompra() == 0 -->
                    <th class="col-sm-1 products__title costounitario-producto">
                      <center>Costo U.</center>
                    </th>
                    <!-- /ko -->
                    <th class="col-sm-1 products__title afectoigv-producto">
                      <center>Afecto IGV</center>
                    </th>
                    <!-- <th class="col-sm-1 products__title afectoisc-producto"><center>Afecto ISC</center></th> -->
                    <!-- <th class="col-sm-1 products__title iscporcentaje-producto"><center>ISC (%)</center></th> -->
                    <!-- ko if: ParametroPrecioCompra() == 0 -->
                    <th class="NotaCreditoCompra_Todos DetalleNotaCreditoCompra_Descuento col-sm-1 products__title descuentounitario-producto">
                      <center>Desc. Unitario</center>
                    </th>
                    <!-- /ko -->
                    <th class="NotaCreditoCompra_Todos DetalleNotaCreditoCompra_DescuentoUnitario col-sm-1 products__title">
                      <center>Desc. Unitario</center>
                    </th>
                    <th class="col-sm-1 products__title importe-producto">
                      <center>Importe</center>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <!-- ko foreach : DetallesNotaCreditoCompra -->
                  <tr class="clickable-row" style="min-height: 80px;" data-bind="attr : { id: IdDetalleComprobanteCompra },  event : {focusout : $parent.Refrescar}">
                    <td class="col-sm-1 NotaCreditoCompra_Todos DetalleNotaCreditoCompra_Codigo">
                      <input class="NotaCreditoCompra_Todos DetalleNotaCreditoCompra_Codigo form-control input-detallecomprobanteventa formulario" data-bind="value: CodigoMercaderia, valueUpdate : 'keyup',
              attr : { id : IdDetalleComprobanteCompra() + '_input_CodigoMercaderia'},
              event : {keydown : function(data,event) { return OnKeyEnterCodigoMercaderia(data,event,$parent.OnKeyEnter); } , focusout : ValidarCodigoMercaderia}" type="text" data-validation="validacion_producto" data-validation-error-msg="No existe Codigo Producto" data-validation-found="false">
                    </td>
                    <td class="col-sm-4 NotaCreditoCompra_Todos DetalleNotaCreditoCompra_Descripcion">
                      <input class="NotaCreditoCompra_Todos DetalleNotaCreditoCompra_Descripcion form-control input-detallecomprobanteventa formulario" data-bind="value: NombreProducto,
            attr : { id : IdDetalleComprobanteCompra() + '_input_NombreProducto',
            'data-validation-reference' : IdDetalleComprobanteCompra() + '_input_CodigoMercaderia' },
            event: { focus : function(data,event){return OnFocus(data, event, $parent.OnFocus)}, keydown : $parent.OnKeyEnter }" type="text" data-validation="autocompletado_producto" data-validation-error-msg="No se han encontrado resultados para tu búsqueda de producto">
                    </td>
                    <td class="col-sm-1  text-center">
                      <span class="input-detallecomprobanteventa" data-bind="text : AbreviaturaUnidadMedida , visible : true ,
            attr : { id : IdDetalleComprobanteCompra() + '_span_AbreviaturaUnidadMedida'}"></span>
                    </td>
                    <td class="col-sm-1 NotaCreditoCompra_Todos DetalleNotaCreditoCompra_Cantidad">
                      <input name="Cantidad" class="NotaCreditoCompra_Todos DetalleNotaCreditoCompra_Cantidad form-control input-detallecomprobanteventa formulario numeric text-mumeric" data-bind="value : Cantidad ,
            visible : true , attr : { id : IdDetalleComprobanteCompra() + '_input_Cantidad' },
            event: {focus: function(data,event){return OnFocus(data, event, $parent.OnFocus)}, blur: CalcularCostoItemDetalle , keydown : $parent.OnKeyEnter} ,
            numbertrim: Cantidad " type="text" data-validation="number_calc" data-validation-allowing="float,positive,range[1;9999999]" data-validation-decimal-separator="." data-validation-error-msg="Debe ser de 0 a más">
                    </td>
                    <!-- ko if: $parent.ParametroPrecioCompra() == 1 -->
                    <td class="col-sm-1  NotaCreditoCompra_Todos DetalleNotaCreditoCompra_PrecioUnitario">
                      <input name="PrecioUnitario" class="NotaCreditoCompra_Todos DetalleNotaCreditoCompra_PrecioUnitario form-control input-detallecomprobanteventa formulario numeric text-mumeric" data-bind="value : PrecioUnitario ,
            attr : { id : IdDetalleComprobanteCompra() + '_input_PrecioUnitario'},
            event: { focus: function(data,event) { return OnFocus(data,event,$parent.OnFocus);} , 
            blur: CalcularCostoItemDetalle, 
            keydown : $parent.OnKeyEnter} " type="text" data-validation="number_calc" data-validation-allowing="float,positive,range[1;9999999]" data-validation-decimal-separator="." data-validation-error-msg="Solo positivo"> <!-- class="NotaCreditoCompra_Todos DetalleNotaCreditoCompra_CostoUnitario form-control input-detallecomprobanteventa formulario numeric text-mumeric" , att: , 'data-cantidad-decimal' : DecimalPrecioUnitario()-->
                    </td>
                    <!-- /ko -->
                    <!-- ko if: $parent.ParametroPrecioCompra() == 0 -->
                    <td class="col-sm-1 NotaCreditoCompra_Todos DetalleNotaCreditoCompra_CostoUnitario">
                      <input name="CostoUnitario" class="NotaCreditoCompra_Todos DetalleNotaCreditoCompra_CostoUnitario form-control input-detallecomprobanteventa formulario numeric text-mumeric" data-bind="value : CostoUnitario ,
            visible : true , 
            attr : { id : IdDetalleComprobanteCompra() + '_input_CostoUnitario' },
            event: {focus : function(data,event){return OnFocus(data, event, $parent.OnFocus)}, 
            blur: CalcularCostoItemDetalle, 
            keydown : $parent.OnKeyEnter} " type="text" data-validation="number_calc" data-validation-allowing="float,positive,range[1;9999999]" data-validation-decimal-separator="." data-validation-error-msg="Debe ser positivo">
                    </td>
                    <!-- /ko -->
                    <td class="col-sm-1 NotaCreditoCompra_Todos DetalleNotaCreditoCompra_AfectoIGV">
                      <select name="AfectoIGV" id="AfectoIGV" class="form-control formulario NotaCreditoCompra_Todos DetalleNotaCreditoCompra_AfectoIGV" data-bind="
            value : AfectoIGV ,
            attr : { id : IdDetalleComprobanteCompra() + '_input_AfectoIGV' },
            event: { keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } }">
                        <option value="1">SI</option>
                        <option value="0">NO</option>
                      </select>
                    </td>
                    <!-- <td class="col-sm-1 NotaCreditoCompra_Todos DetalleNotaCreditoCompra_AfectoISC">
            <select name="AfectoISC" id="AfectoISC" class="form-control formulario NotaCreditoCompra_Todos DetalleNotaCreditoCompra_AfectoISC"data-bind="
            value : AfectoISC ,
            attr : { id : IdDetalleComprobanteCompra() + '_input_AfectoISC' },
            event: { keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } }">
              <option value="1">SI</option>
              <option value="0">NO</option>
            </select>
          </td>
          <td class="NotaCreditoCompra_Todos DetalleNotaCreditoCompra_ISCPorcentaje col-sm-1">
            <input name="DescuentoUnitario" class="NotaCreditoCompra_Todos DetalleNotaCreditoCompra_ISCPorcentaje form-control input-detallecomprobanteventa formulario numeric text-mumeric"
            data-bind="value : TasaISC,
            visible : true , attr : { id : IdDetalleComprobanteCompra() + '_input_ISCPorcentaje' },
            event: {keydown : $parent.OnKeyEnter}" type="text"
            data-validation="number_calc" data-validation-allowing="float,positive,range[0;9999999]" data-validation-decimal-separator="." data-validation-error-msg="Debe ser de 0 a más">
          </td> -->
                    <!-- ko if: $parent.ParametroPrecioCompra() == 1 -->
                    <td class="NotaCreditoCompra_Todos DetalleNotaCreditoCompra_Importe col-sm-1">
                      <input class="NotaCreditoCompra_Todos DetalleNotaCreditoCompra_Importe form-control input-detallecomprobanteventa formulario numeric text-mumeric" data-bind="value : PrecioItem,
            visible : true , attr : { id : IdDetalleComprobanteCompra() + '_input_PrecioItem'},
            event: {
              blur: CalcularDescuentoUnitarioByImporte, 
              focusout : ValidarImporte, 
              focus: function(data,event){return OnFocus(data, event, $parent.OnFocus)}, 
              keydown : $parent.OnKeyEnter},
            numbertrim: PrecioItem" type="text" data-validation="number_calc" data-validation-allowing="float,positive,range[0;9999999]" data-validation-decimal-separator="." data-validation-error-msg="De 0 a más">
                    </td>
                    <!-- /ko -->
                    <!-- ko if: $parent.ParametroPrecioCompra() == 0 -->
                    <td class="NotaCreditoCompra_Todos DetalleNotaCreditoCompra_Descuento col-sm-1">
                      <input name="DescuentoUnitario" class="NotaCreditoCompra_Todos DetalleNotaCreditoCompra_Descuento form-control input-detallecomprobanteventa formulario numeric text-mumeric" data-bind="value : DescuentoUnitario ,
            visible : true , attr : { id : IdDetalleComprobanteCompra() + '_input_DescuentoUnitario', 'data-cantidad-decimal' : DecimalCostoUnitario() },
            event: {focus : function(data,event){return OnFocus(data, event, $parent.OnFocus)}, blur: CalcularCostoItemDetalle, keydown : $parent.OnKeyEnter}" type="text" data-validation="number_calc" data-validation-allowing="float,positive,range[0;9999999]" data-validation-decimal-separator="." data-validation-error-msg="Debe ser de 0 a más">
                    </td>

                    <td class="NotaCreditoCompra_Todos DetalleNotaCreditoCompra_DescuentoUnitario col-sm-1">
                      <input class="NotaCreditoCompra_Todos DetalleNotaCreditoCompra_DescuentoUnitario form-control input-detallecomprobanteventa formulario numeric text-mumeric" data-bind="value : DescuentoUnitario,
            visible : true , attr : { id : IdDetalleComprobanteCompra() + '_span_DescuentoUnitario', 'data-cantidad-decimal' : DecimalCostoUnitario()},
            event: {blur: CalcularImporteByDescuentoUnitario, focusout : ValidarDescuentoUnitario, focus: function(data,event){return OnFocus(data, event, $parent.OnFocus)}, keydown : $parent.OnKeyEnter},
            numberdecimal: DescuentoUnitario" type="text" data-validation="number_calc" data-validation-allowing="float,positive,range[0;9999999]" data-validation-decimal-separator="." data-validation-error-msg="De 0 a más">
                    </td>

                    <td class="NotaCreditoCompra_Todos DetalleNotaCreditoCompra_Importe col-sm-1">
                      <input class="NotaCreditoCompra_Todos DetalleNotaCreditoCompra_Importe form-control input-detallecomprobanteventa formulario numeric text-mumeric" data-bind="value : CostoItem,
            visible : true , attr : { id : IdDetalleComprobanteCompra() + '_input_CostoItem'},
            event: {blur: CalcularDescuentoUnitarioByImporte, focusout : ValidarImporte, focus: function(data,event){return OnFocus(data, event, $parent.OnFocus)}, keydown : $parent.OnKeyEnter},
            numbertrim: CostoItem" type="text" data-validation="number_calc" data-validation-allowing="float,positive,range[0;9999999]" data-validation-decimal-separator="." data-validation-error-msg="De 0 a más">
                    </td>
                    <!-- /ko -->
                    <td class="col-sm-auto NotaCreditoCompra_Todos DetalleNotaCreditoCompra_AgregarEliminar">
                      <div class="input-group ajuste-opcion-plusminus">
                        <button type="button" class="NotaCreditoCompra_Todos DetalleNotaCreditoCompra_AgregarEliminar btn btn-default focus-control glyphicon glyphicon-minus no-tab" data-bind="click : $parent.QuitarDetalleComprobanteCompra, attr : { id : IdDetalleComprobanteCompra() + '_a_opcion'}"></button>
                      </div>
                    </td>

                  </tr>
                  <!-- /ko -->
                </tbody>
              </table>
            </fieldset>
          </div>
        </div>
        <!-- /ko -->
        <!-- ko if: VistaConceptoODetalle() == '1' -->
        <div class="row">
          <div class="col-md-8">
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <div class="input-group">
                <div class="input-group-addon ancho">Saldo Comprobantes</div>
                <input id="TotalFacturas" tabindex="-1" readonly class="NotaCreditoCompra_Todos NotaCreditoCompra_TotalFacturas form-control formulario numeric text-mumeric no-tab" type="text" placeholder="TotalFacturas" data-bind="value: TotalSaldo">
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <table id="tablaConceptoComprobanteCompra" class="datalist__table table display no-footer" width="100%" data-products="brand">
              <thead>
                <tr>
                  <th class="NotaCreditoCompra_Todos NotaCreditoCompra_Concepto col-sm-8 products__title">Concepto</th>
                  <th class="NotaCreditoCompra_Todos NotaCreditoCompra_Porcentaje col-sm-2 products__title">
                    <center>Porcentaje</center>
                  </th>
                  <th class="NotaCreditoCompra_Todos NotaCreditoCompra_Importe col-sm-2 products__title">
                    <center>Importe</center>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr class="clickable-row" style="min-height: 80px;" data-bind="">
                  <td class="NotaCreditoCompra_Todos NotaCreditoCompra_Concepto col-sm-8">
                    <select id="combo-conceptonotacreditocompra" class="NotaCreditoCompra_Todos NotaCreditoCompra_Concepto form-control formulario" data-bind="
            value : Concepto,
            options : ConceptosNotaCreditoCompra,
            optionsValue : 'IdConceptoNotaCreditoDebito' ,
            optionsText : 'NombreConceptoNotaCreditoDebito',
            event:{keydown : OnKeyEnter}">
                    </select>
                  </td>
                  <td class="NotaCreditoCompra_Todos NotaCreditoCompra_Porcentaje col-sm-2">
                    <input id="input_porcentaje" name="Porcentaje" class="NotaCreditoCompra_Todos NotaCreditoCompra_Porcentaje form-control formulario numeric text-mumeric" data-bind="value : Porcentaje ,
            visible : true,
            event:{focus: OnFocus, blur: CalcularTotalByPorcentaje, keydown : OnKeyEnter, focusout: ValidarImporte},
            numbertrim: Porcentaje " type="text" data-validation="number_calc" data-validation-allowing="float,positive,range[1;9999999]" data-validation-decimal-separator="." data-validation-error-msg="Debe ser positivo, mayor a 0 y menor igual a 100">
                  </td>
                  <td class="NotaCreditoCompra_Todos NotaCreditoCompra_Importe col-sm-2">
                    <input id="input_importe" name="Importe" class="NotaCreditoCompra_Todos NotaCreditoCompra_Importe form-control formulario numeric text-mumeric" data-bind="value : Importe ,
            visible : true,
            event:{focus: OnFocus, blur: CalcularPorcentaje, keydown : OnKeyEnter, focusout: ValidarImporte},
            numbertrim: Importe" type="text" data-validation="number_calc" data-validation-allowing="float,positive,range[1;9999999]" data-validation-decimal-separator="." data-validation-error-msg="Debe ser positivo, mayor a 0">
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <!-- /ko -->
        <!-- ko if: VistaConceptoODetalle() == '0' -->
        <div class="row">
          <div class="col-md-12 denominacion-moneda-nacional">
            <span data-bind="html : DenominacionTotal()" id="nletras"></span>
          </div>
        </div>
        <!-- /ko -->
        <br>
        <div id="footer_notacreditocompra" class="row">
          <div class="col-md-3">
            <!-- ko if: VistaNota() == '1' -->
            <div>
              <h4>Nota:</h4>
              <span data-bind="text: NotaUsuario">No se pueden hacer modificaciones de la vida</span>
            </div>
            <!-- /ko -->
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <div class="addon-top">Op. Gravada</div>
              <input id="ValorCompraGravado" class="NotaCreditoCompra_Todos NotaCreditoCompra_Gravada form-control formulario numeric text-mumeric" type="text" placeholder="Op. Grav." data-bind="value: ValorCompraGravado, event: {focus: OnFocus, focusout: CalcularTotalesByFooter, keydown : OnKeyEnter},numbertrim: ValorCompraGravado">
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <div class="addon-top">Op. Exonerado</div>
              <input id="ValorCompraNoGravado" class="NotaCreditoCompra_Todos NotaCreditoCompra_NoGravada form-control formulario numeric text-mumeric" type="text" placeholder="Op. No Grav." data-bind="value: ValorCompraNoGravado, event: {focus: OnFocus, focusout: CalcularTotalesByFooter, keydown : OnKeyEnter},numbertrim: ValorCompraNoGravado">
            </div>
          </div>
          <!-- <div class="col-md-1">
    <div class="form-group">
      <div class="addon-top">ISC</div>
      <input id="ISC" class="NotaCreditoCompra_Todos NotaCreditoCompra_ISC form-control formulario numeric text-mumeric" type="text" placeholder="ISC" data-bind="value: CalculoTotalISC(), keydown : OnKeyEnter, numbertrim: ISC">
    </div>
  </div> -->
          <div class="col-md-2">
            <div class="form-group">
              <div class="addon-top">IGV</div>
              <input id="IGV" class="NotaCreditoCompra_Todos NotaCreditoCompra_IGV form-control formulario numeric text-mumeric" type="text" placeholder="IGV" data-bind="value: CalculoTotalIGV(), keydown : OnKeyEnter, numbertrim: IGV">
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <div class="addon-top">Total</div>
              <input id="Total" class="NotaCreditoCompra_Todos NotaCreditoCompra_Total form-control formulario numeric text-mumeric" type="text" placeholder="Total" data-bind="value : CalculoTotalCompra(), keydown : OnKeyEnter">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <br />

  <div class="row">
    <center>
      <button type="button" id="btn_Grabar" class="btn btn-success focus-control focus-tab" data-bind="click : Guardar">Grabar</button> &nbsp;
      <button type="button" id="btn_Limpiar" class="btn btn-default focus-control" data-bind="click : Limpiar, visible : opcionProceso() == 1">Limpiar</button> &nbsp;
      <button type="button" id="BtnDeshacer" class="btn btn-default" data-bind="click : Deshacer, visible : opcionProceso() == 2">Deshacer</button> &nbsp;
      <button type="button" id="btn_Cerrar" class="btn btn-default" data-bind="click : OnClickBtnCerrar , visible :  opcionProceso() == 2">Cerrar</button>
    </center>
  </div>
</form>
<!-- /ko -->