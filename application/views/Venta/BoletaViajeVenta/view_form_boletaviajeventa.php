<!-- ko with : BoletaViajeVenta -->
<form id="formBoletaViajeVenta" name="formBoletaViajeVenta" role="form" autocomplete="off">
  <div class="datalist__result">
    <input id="IdTipoDocumento" class="form-control" type="hidden" placeholder="Documento" data-bind="value: IdTipoDocumento">
    <input id="IdTipoVenta" class="form-control" type="hidden" placeholder="Documento" data-bind="value: IdTipoVenta">
    <!-- <input id="IdTipoOperacion" class="form-control" type="hidden" placeholder="TipoOperacion" data-bind="value: IdTipoOperacion">-->
    <input id="IdComprobanteVenta" class="form-control" type="hidden" placeholder="IdComprobanteVenta">
    <input id="IdCliente" class="form-control" type="hidden" placeholder="RUC/DNI:" data-bind="value: IdCliente">

    <div class="tab-pane active" id="brand" role="tabpanel">
      <div class="scrollable scrollbar-macosx">
        <div class="container-fluid">
          <div class="row">
            <fieldset id="fieldsetForm">
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
                          event : { focus : OnFocus , change : OnChangeSerieDocumento , keydown : OnKeyEnter}" data-validation="required" data-validation-error-msg="No tiene serie asignada">
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Número</div>
                        <input id="NumeroDocumento" class="form-control formulario no-tab" type="text" tabindex="-1" data-bind="value: NumeroDocumento ,
                        attr : { readonly : CheckNumeroDocumento }, event : {  focus : OnFocus , focusout : ValidarNumeroDocumento , keydown : OnKeyEnter }" data-validation="number" data-validation-allowing="range[1;99999999]" data-validation-error-msg="El número documento debe ser numérico y tener como máximo 8 digitos" data-validation-depends-on="CheckNumeroDocumento" data-validation-optional="true">                        
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="checkbox">
                          <input name="CheckNumeroDocumento" id="CheckNumeroDocumento" type="checkbox" class="form-control formulario" data-bind="event: { change : OnChangeCheckNumeroDocumento , focus : OnFocus , keydown : OnKeyEnter}" />
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
                        <div class="input-group-addon formulario-venta">F. Emisión</div>
                        <input id="FechaEmision" name="FechaEmision" class="form-control formulario" data-inputmask-clearmaskonlostfocus="false" data-bind="value: FechaEmision, event: {focus : OnFocus , focusout : ValidarFechaEmision ,keydown : OnKeyEnter}" data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha de emision en invalida" />
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-5">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">RUC/DNI - Cliente</div>
                        <input id="Cliente" name="Cliente" class="form-control formulario" type="text" data-bind="value : RUCDNICliente(),event : { focus : OnFocus }" data-validation="autocompletado_cliente" data-validation-error-msg="No se han encontrado resultados para tu búsqueda de cliente" data-validation-text-found="">
                        <div class="input-group-btn">
                          <button type="button" class=" btn-buscar btn btn-default no-tab" id="BtnNuevoCliente" data-bind="click : function(data,event) {  return OnClickBtnNuevoCliente(data,event,$parent.Cliente); }"><span class="fa fa-plus-circle"></span></button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-1">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="checkbox">
                          <input name="CheckCliente" id="CheckCliente" type="checkbox" class="form-control formulario" data-bind="event: { change : OnChangeCheckCliente , focus : OnFocus , keydown : OnKeyEnter}" />
                          <label for="CheckCliente">Editar</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group">
                            <div class="input-group-addon formulario-venta">Origen</div>
                            <select id="combo-lugarorigen" class="form-control formulario valid-producto" data-bind="
                            value : IdLugarOrigen,
                            options : LugaresDestinos,
                            optionsValue : 'IdLugarDestino' ,
                            optionsText : 'NombreLugarDestino' ,
                            optionsCaption: 'Selecciona origen...',
                            event : { focus : OnFocus, keydown : OnKeyEnter, change: OnChangeOrigenDestino }">
                          </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group">
                            <div class="input-group-addon formulario-venta">Destino</div>
                            <select id="combo-lugardestino" class="form-control formulario valid-producto" data-bind="
                            value : IdLugarDestino,
                            options : LugaresDestinos,
                            optionsValue : 'IdLugarDestino' ,
                            optionsText : 'NombreLugarDestino' ,
                            optionsCaption: 'Selecciona destino...',
                            event : { focus : OnFocus, keydown : OnKeyEnter, change: OnChangeOrigenDestino }">
                          </select>
                      </div>
                    </div>
                  </div>
                  
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Dirección</div>
                        <input readonly tabindex="-1" class="form-control formulario no-tab" id="Direccion" data-bind="value : Direccion" type="text">
                      </div>
                    </div>
                  </div>
                  <!-- ko if:(MostrarCampos.GuiaRemision() == "1" ) -->
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Guia Remision</div>
                        <input id="GuiaRemision" class="form-control formulario" type="text" data-bind="value: GuiaRemision ,event : { focus : OnFocus , keydown : OnKeyEnter}">
                      </div>
                    </div>
                  </div>
                  <!-- /ko -->
                  <!-- ko if:(MostrarCampos.OrdenCompra() == "1" ) -->
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">O.C.</div>
                        <input id="OrdenCompra" class="form-control formulario" type="text" data-bind="value: OrdenCompra, event : { focus : OnFocus ,keydown : OnKeyEnter }">
                      </div>
                    </div>
                  </div>
                  <!-- /ko -->
                  <div class="col-md-2">
                    <div class="form-group">                            
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Asiento</div>
                        <input id="NumeroAsiento" class="form-control formulario" type="text" data-bind="
                        value: NumeroAsiento, 
                        event : { focus : OnFocus ,keydown : OnKeyEnter }" data-validation="required" data-validation-error-msg="Campo obligatorio">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-addon formulario-venta">Hora de Partida</div>
                        <input id="HoraPartida" class="form-control formulario" type="text" data-bind="
                        value: HoraPartida, 
                        event : { focus : OnFocus ,keydown : OnKeyEnter }" data-validation="required" data-validation-error-msg="Campo obligatorio">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <div class="radio radio-inline">
                        <input id="IndicadorAm" type="radio" name="IndicadorAmPm" value="1" data-bind="checked : IndicadorAmPm, event : { change : $root.OnChangeTipoAfectacionIGV ,focus : OnFocus ,keydown : OnKeyEnter}">
                        <label for="IndicadorAm">AM</label>
                      </div>
                      <div class="radio radio-inline">
                        <input id="IndicadorPm" type="radio" name="IndicadorAmPm" value="2" data-bind="checked : IndicadorAmPm, event : { change : $root.OnChangeTipoAfectacionIGV ,focus : OnFocus ,keydown : OnKeyEnter}">
                        <label for="IndicadorPm">PM</label>
                      </div>
                    </div>
                  </div>                  
                </div>
                <!-- ko if:( ParametroTransporte() == "1" && TipoVenta() == TIPO_VENTA.SERVICIOS)-->
                <div class="row" id="DivTransporte">
                  <div class="col-md-12">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="col-md-3" id="divDNIPasajero" >
                          <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-addon formulario-venta">DNI Pasajero</div>
                              <input id="DniPasajero" disabled class="form-control formulario" type="text" data-bind="value: DniPasajero, valueUpdate : 'keyup', event : {  focus : OnFocus , focusout : OnFocusOutNumeroDocumentoIdentidad , keydown : function(data,event) {return OnKeyEnterNumeroDocumentoIdentidad(data,event,OnKeyEnter);} }" data-validation="required" data-validation-error-msg="Campo requerido" data-validation-optional="true">
                              <div class="input-group-btn">
                                <button tabindex="-1" type="button" disabled class="btn-busqueda btn focus-control no-tab" id="BtnBusquedaRENIEC" data-bind="click : OnClickBtnBusquedaRENIEC">
                                  <img width="25px" height="20px" src="<?php echo base_url()?>assets/js/iconos/logoRENIEC.svg" alt="" title="RENIEC">
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <div class="input-group">
                                  <div class="input-group-addon formulario-venta">Fecha Expedición</div>
                                  <input id="FechaExpedicion" class="form-control formulario fecha" type="text" data-bind="value: FechaExpedicion, event : { focus : OnFocus ,keydown : OnKeyEnter }">
                            </div>                            
                          </div>
                        </div>
                        <!-- ko if:(MostrarCampos.Observacion() == "1" ) -->
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-addon formulario-venta">Otros Datos</div>
                              <input id="OrdenCompra" class="form-control formulario" type="text" data-bind="value: Observacion, event : { focus : OnFocus ,keydown : OnKeyEnter, change: OnChangeOrigenDestino }">
                            </div>
                          </div>
                        </div>
                        <!-- /ko -->                        
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="col-md-6" id="divNombrePasajero" >
                            <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-addon formulario-venta">Pasajero</div>
                              <input id="NombrePasajero" disabled class="form-control formulario" type="text" data-bind="value: NombrePasajero, event : { focus : OnFocus ,keydown : OnKeyEnter }">
                            </div>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /ko -->
                <div class="row">
                  <!-- ko if:(TipoVenta() == TIPO_VENTA.MERCADERIAS && MostrarCampos.GrupoAlmacen() == "1")-->
                  <div id="GrupoAlmacen" class="col-md-6">
                    <fieldset style="padding: 2px !important;">
                      <legend>
                        <div class="radio radio-inline">
                          <input id="radBol1" type="radio" name="radio" class="" value="0" data-bind="checked : EstadoPendienteNota,event : { change : OnChangeEstadoPendienteNota, keydown : OnKeyEnter }">
                          <label for="radBol1">Afecta Kardex</label>
                        </div>
                        <div class="radio radio-inline">
                          <input id="radBol2" type="radio" name="radio" class="" value="1" data-bind="checked : EstadoPendienteNota,event : { change : OnChangeEstadoPendienteNota, keydown : OnKeyEnter }">
                          <label for="radBol2">Pendiente entrega con Nota de Salida</label>
                        </div>
                      </legend>
                      <div class="col-md-6">
                        <div class="form-group">
                          <div class="input-group">
                            <div class="input-group-addon formulario-venta">Almacén</div>
                            <select id="combo-almacen" class="form-control formulario" data-bind="
                            value : IdAsignacionSede,
                            options : Sedes,
                            optionsValue : 'IdAsignacionSede' ,
                            optionsText : 'NombreSede' ,
                            event : { focus : OnFocus ,change : OnChangeComboAlmacen , keydown : OnKeyEnter }" data-validation="required" data-validation-error-msg="No tiene almacen asignado">
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <div class="input-group">
                            <div class="input-group-addon formulario-venta">Fecha Movimiento</div>
                            <input id="FechaMovimientoAlmacen" name="FechaMovimientoAlmacen" class="form-control formulario" data-inputmask-clearmaskonlostfocus="false" data-bind="value: FechaMovimientoAlmacen, event : {focus : OnFocus , focusout : ValidarFechaMovimientoAlmacen,  keydown : OnKeyEnter}" data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha de nota de salida es invalida" />
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                  <!-- /ko -->
                  <!-- ko if: (ParametroCaja() == 1)-->
                  <div class="col-md-6" data-bind="visible : IdFormaPago() == ID_FORMA_PAGO_CONTADO">
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
              </div>
            </fieldset>
          </div>
          <br>
          <div class="row">
            <!-- ko if:(TipoVenta() == TIPO_VENTA.MERCADERIAS) -->
            <div class="btn-group">
              <button type="button" class="btn btn-primary no-tab" href="#" data-bind="click : function(data,event) {  return OnClickBtnNuevaMercaderia(data,event,$parent.Mercaderia);}">
                <span class="glyphicon glyphicon-plus"></span> Nueva MercaderÍa
              </button>
            </div>
            <div class="btn-group">
              <button type="button" class="btn btn-primary no-tab" href="#" data-bind="click : function(data,event) {  return OnClickBtnBuscadorMercaderia(data,event,$parent);}">
                <span class="glyphicon glyphicon-search"></span> Buscar Mercadería con Imagen
              </button>
            </div>



            <div><br></div>
            <!-- /ko -->
            <fieldset>
              <div class="col-md-12">
                <div class="row detalle-comprobante">
                  <div class="col-md-12">
                    <table id="DetalleBoletaViaje" class="datalist__table table display grid-detail-body table-border" width="100%" id="tablaDetalleComprobanteVenta">
                      <thead>
                        <tr>
                          <th class="col-sm-2 text-center products__id">Código</th>
                          <th class="col-sm-4 products__title">Descripción</th>
                          <!-- ko if:(ParametroObservacionDetalle() == "1" && TipoVenta() == TIPO_VENTA.SERVICIOS ) -->
                          <th class="col-sm-2 products__title">Otros Datos</th>
                          <!-- /ko -->
                          <!-- ko if:(TipoVenta() == TIPO_VENTA.MERCADERIAS && ParametroMarcaVenta() == 1) -->
                          <th class="products__title">
                            <center>Marca</center>
                          </th>
                          <!-- /ko -->
                          <!-- ko if:(TipoVenta() == TIPO_VENTA.MERCADERIAS) -->
                          <th class="text-center products__title">Unid.</th>
                          <!-- /ko -->
                          <!-- ko if:(ParametroStockProductoVenta() != 0 && TipoVenta() == TIPO_VENTA.MERCADERIAS) -->
                          <th class="col-sm-1 text-center products__title">Stock</th>
                          <!-- /ko -->
                          <!-- ko if: (IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA  && (IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_T || IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_Z))&&(TipoVenta() == TIPO_VENTA.MERCADERIAS) -->
                          <th class="col-sm-1 text-center products__title">Documento Zofra</th>
                          <!-- /ko -->
                          <!-- ko if:  ParametroDua() != 0 && (IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA  && !(IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_T || IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_Z))&&(TipoVenta() == TIPO_VENTA.MERCADERIAS) -->
                          <th class="col-sm-1 text-center products__title">Documento D.U.A.</th>
                          <!-- /ko -->
                          <!-- ko if: ParametroLote() != 0 && (TipoVenta() == TIPO_VENTA.MERCADERIAS) -->
                          <th class="col-sm-1 text-center products__title">Lote</th>
                          <!-- /ko -->
                          <th class="col-sm-1 text-center products__title ">Cantidad</th>
                          <!-- ko if: (ParametroCalculoIGVDesdeTotal() == 0) -->
                          <th class="col-sm-1 products__title">
                            <center>P. U.</center>
                          </th>
                          <!-- /ko -->
                          <!-- ko if: (ParametroCalculoIGVDesdeTotal() == 1) -->
                          <th class="col-sm-1 products__title">
                            <center>V. U.</center>
                          </th>
                          <!-- /ko -->
                          <!-- ko if: (ParametroDescuentoUnitario() != 0) -->
                          <th class="col-sm-1 text-center products__title">Desc. Unit.</th>
                          <!-- /ko -->
                          <!-- ko if: (ParametroDescuentoItem() != 0 && TipoVenta() == TIPO_VENTA.MERCADERIAS) -->
                          <th class="text-center products__title">Desc. Item</th>
                          <!-- /ko -->
                          <th class="col-sm-1 products__title">
                            <center>Importe</center>
                          </th>

                          <th class="col-sm-1 text-center products__title"></th>
                        </tr>
                      </thead>
                      <tbody>
                        <!-- ko foreach : DetallesComprobanteVenta -->
                        <tr name="Fila" class="clickable-row" style="min-height: 80px;" data-bind="attr : { id: IdDetalleComprobanteVenta()+'_tr_detalle' }, click :  function(data,event) { return OnClickFila(data,event,$parent.OnRefrescar); } ">
                          <td class="col-sm-2">
                            <div class="input-group">
                              <input class="form-control formulario" disabled data-bind="
                              value: CodigoMercaderia, valueUpdate : 'keyup',
                              attr : { id : IdDetalleComprobanteVenta() + '_input_CodigoMercaderia'},
                              event : {input:function(data,event) { return OnChangeText(data, event, $parent); } , focus : function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnterCodigoMercaderia(data,event,$parent.OnKeyEnter); }  }" type="text" data-validation="validacion_producto" data-validation-error-msg="Cod. Inválido" data-validation-found="false" data-validation-text-found="">
                            </div>
                          </td>
                          <td class="col-sm-4">
                            <div class="input-group">
                              <input class="form-control formulario" disabled data-bind="
                              value: NombreProducto,
                              attr : { id : IdDetalleComprobanteVenta() + '_input_NombreProducto', 'data-validation-reference' : IdDetalleComprobanteVenta() + '_input_CodigoMercaderia'  },
                              event: { focus : function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); }  }" type="text" data-validation="autocompletado_producto" data-validation-error-msg="No se han encontrado resultados para tu búsqueda de producto">
                            </div>
                          </td>
                          <!-- ko if:($parent.ParametroObservacionDetalle() == "1" && $parent.TipoVenta() == TIPO_VENTA.SERVICIOS ) -->
                          <td class="col-sm-2">
                            <input name="Observacion" class="form-control  formulario numeric text-mumeric inputs" data-bind="value : Observacion ,
                            attr : { id : IdDetalleComprobanteVenta() + '_input_Observacion'},
                            event: { focus: function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout : ValidarObservacion} ," type="text" data-validation="" data-validation-error-msg="requerido">
                          </td>
                          <!-- /ko -->
                          <!-- ko if:($parent.TipoVenta() == TIPO_VENTA.MERCADERIAS && $parent.ParametroMarcaVenta() == 1) -->
                          <td class="text-center">
                            <span class="" data-bind="text : NombreMarca, attr : { id : IdDetalleComprobanteVenta() + '_span_Marca'} "></span>
                          </td>
                          <!-- /ko -->
                          <!-- ko if:($parent.TipoVenta() == TIPO_VENTA.MERCADERIAS) -->
                          <td class="text-center">
                            <span class="" data-bind="text : AbreviaturaUnidadMedida, attr : { id : IdDetalleComprobanteVenta() + '_span_AbreviaturaUnidadMedida'}"></span>
                          </td>
                          <!-- /ko -->
                          <!-- ko if:($parent.ParametroStockProductoVenta() != 0 && $parent.TipoVenta() == TIPO_VENTA.MERCADERIAS) -->
                          <td class="text-center">
                            <span class="" data-bind="text : StockProducto, attr : { id : IdDetalleComprobanteVenta() + '_span_StockProducto'}, css: ColorText , numbertrim :StockProducto "></span>
                          </td>
                          <!-- /ko -->
                          <!-- ko if: ($parent.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA  && ($parent.IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_T || $parent.IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_Z))&&($parent.TipoVenta() == TIPO_VENTA.MERCADERIAS) -->
                          <td class="col-sm-1">
                            <input name="NumeroDocumentoSalidaZofra" class="form-control formulario" data-bind="value : NumeroDocumentoSalidaZofra , attr : { id : IdDetalleComprobanteVenta() + '_input_NumeroDocumentoSalidaZofra'},
                            event: {focus : function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); }, focusout : ValidarNumeroDocumentoSalidaZofra } , ko_autocomplete_zofra: { zofra: DataListaZofra($parent.IdAsignacionSede()),id: IdDocumentoSalidaZofraProducto}" type="text" data-validation="" data-validation-error-msg="Requerido" data-validation-found="false">
                          </td>
                          <!-- /ko -->
                          <!-- ko if: $parent.ParametroDua() != 0 && ($parent.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA  && !($parent.IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_T || $parent.IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_Z))&&($parent.TipoVenta() == TIPO_VENTA.MERCADERIAS) -->
                          <td class="col-sm-1">
                            <input name="NumeroDua" class="form-control formulario" data-bind="value : NumeroDua , attr : { id : IdDetalleComprobanteVenta() + '_input_NumeroDua'}, enable:OnEnableDua(),
                            event: {focus : function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); }, focusout : ValidarNumeroDua } , ko_autocomplete_dua: { dua: DataListaDua($parent.IdAsignacionSede()),id: IdDuaProducto}" type="text" data-validation="required_dua" data-validation-error-msg="Requerido" data-validation-found="false">
                          </td>
                          <!-- /ko -->
                          <!-- ko if: $parent.ParametroLote() != 0 && ($parent.TipoVenta() == TIPO_VENTA.MERCADERIAS) -->
                          <td class="col-sm-1">
                            <input name="NumeroLote" class="form-control formulario" data-bind="value : NumeroLote , attr : { id : IdDetalleComprobanteVenta() + '_input_NumeroLote'},
                            event: {focus : function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); }, focusout : ValidarNumeroLote } , ko_autocomplete_lote: { lote: DataLotes($parent.IdAsignacionSede()),id: IdLoteProducto}" type="text" data-validation="required_lote" data-validation-error-msg="Requerido" data-validation-found="false">
                          </td>
                          <!-- /ko -->
                          <td class="col-sm-1">
                            <input name="Cantidad" class="form-control formulario numeric text-mumeric" data-bind="
                            value : Cantidad, 
                            disable: $parent.ParametroCalcularCantidad() == 1,
                            attr: { id : IdDetalleComprobanteVenta() + '_input_Cantidad', 'data-cantidad-decimal' : DecimalCantidad() },
                            event: { focus : function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); }, keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); }, focusout : ValidarCantidad, blur: function (data,event){return CalculoSubTotal(data,event,$parent.OnRefrescar);}, change: (data, event) => OnChangeCantidad(data, event, $parent) }, 
                            numberdecimal: Cantidad, 
                            ko_autocomplete_cantidad: { source: DataPrecios(), raleo: DataRaleo(), precio: PrecioUnitario}" type="text" data-validation="number_calc" data-validation-allowing="float,positive,range[0.001;9999999]" data-validation-decimal-separator="." data-validation-error-msg="De 0 a más">
                          </td>
                          <!-- ko if: ($parent.ParametroCalculoIGVDesdeTotal() == 0) -->
                          <td class="col-sm-1">
                            <input name="PrecioUnitario" list="items" class="form-control  formulario numeric text-mumeric inputs" data-bind="value : PrecioUnitario, disable: $parent.ParametroCalcularCantidad() == 1,
                            attr : { id : IdDetalleComprobanteVenta() + '_input_PrecioUnitario', 'data-cantidad-decimal' : DecimalPrecioUnitario()},
                            event: { focus: function(data,event) { return OnFocusPrecioUnitario(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout : ValidarPrecioUnitario, blur: function (data,event){return CalculoSubTotal(data,event,$parent.OnRefrescar);} } , numberdecimal : PrecioUnitario, ko_autocomplete: { source: DataPrecios(), raleo: DataRaleo(), cantidad: Cantidad}" type="text" data-validation="number_calc" data-validation-allowing="float,positive,range[0.001;9999999]" data-validation-decimal-separator="." data-validation-error-msg="Solo positivo">
                          </td>
                          <!-- /ko -->
                          <!-- ko if: ($parent.ParametroCalculoIGVDesdeTotal() == 1) -->
                          <td class="col-sm-1">
                            <input name="ValorUnitario" list="items" class="form-control  formulario numeric text-mumeric inputs" data-bind="value : ValorUnitario, disable: $parent.ParametroCalcularCantidad() == 1,
                            attr : { id : IdDetalleComprobanteVenta() + '_input_ValorUnitario', 'data-cantidad-decimal' : DecimalPrecioUnitario()},
                            event: { focus: function(data,event) { return OnFocusValorUnitario(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout : ValidarPrecioUnitario, blur: function (data,event){return CalculoSubTotal(data,event,$parent.OnRefrescar);} } , numberdecimal : ValorUnitario" type="text" data-validation="number_calc" data-validation-allowing="float,positive,range[0.001;9999999]" data-validation-decimal-separator="." data-validation-error-msg="Solo positivo">
                          </td>
                          <!-- /ko -->
                          <!-- ko if: ($parent.ParametroCalculoIGVDesdeTotal() == 0 && $parent.ParametroDescuentoUnitario() != 0) -->
                          <td class="col-sm-1">
                            <input name="DescuentoUnitario" class="form-control formulario numeric text-mumeric inputs" data-cantidad-decimal="2" data-bind="value : DescuentoUnitario ,
                            attr : { id : IdDetalleComprobanteVenta() + '_input_DescuentoUnitario', 'data-cantidad-decimal' : DecimalDescuentoUnitario() },
                            event: { focus: function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout : ValidarDescuentoUnitario, blur: function (data,event){return CalculoSubTotal(data,event,$parent.OnRefrescar);} } , numberdecimal : DescuentoUnitario" type="text" data-validation="number_desc" data-validation-allowing="float,positive,range[0;9999999]" data-validation-decimal-separator="." data-validation-error-msg="De 0 a más">
                          </td>
                          <!-- /ko -->
                          <!-- ko if: ($parent.ParametroCalculoIGVDesdeTotal() == 1 && $parent.ParametroDescuentoUnitario() != 0) -->
                          <td class="col-sm-1">
                            <input name="DescuentoValorUnitario" class="form-control formulario numeric text-mumeric inputs" data-cantidad-decimal="2" data-bind="value : DescuentoValorUnitario ,
                            attr : { id : IdDetalleComprobanteVenta() + '_input_DescuentoValorUnitario', 'data-cantidad-decimal' : DecimalDescuentoValorUnitario() },
                            event: { focus: function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout : ValidarDescuentoValorUnitario, blur: function (data,event){return CalculoSubTotal(data,event,$parent.OnRefrescar);} } , numberdecimal : DescuentoValorUnitario" type="text" data-validation="number_desc" data-validation-allowing="float,positive,range[0;9999999]" data-validation-decimal-separator="." data-validation-error-msg="De 0 a más">
                          </td>
                          <!-- /ko -->
                          <!-- ko if:($parent.ParametroDescuentoItem() != 0 && $parent.TipoVenta() == TIPO_VENTA.MERCADERIAS) -->
                          <td class="text-center">
                            <span class="" data-bind="text : CalcularDescuentoItem(), attr : { id : IdDetalleComprobanteVenta() + '_span_DescuentoItem'}"></span>
                          </td>
                          <!-- /ko -->
                          <!-- ko if: ($parent.ParametroCalculoIGVDesdeTotal() == 0) -->
                          <td class="col-sm-1">
                            <div class="input-group">
                              <input class="form-control  formulario text-right" data-bind="value : SubTotal,
                                attr : { id : IdDetalleComprobanteVenta() + '_span_SubTotal'},
                                event : { focus: function(data,event) { return OnFocusSubtotal(data,event,$parent.OnRefrescar); }, keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); }, focusout: ValidarSubTotal, change: function (data,event){return CalculoPrecioUnitario(data,event,$parent.OnRefrescar);} }, numbertrim:SubTotal" type="text" data-validation="number_calc" data-validation-allowing="float,positive,range[0.001;9999999]" data-validation-decimal-separator="." data-validation-error-msg="De 0 a más">
                            </div>
                          </td>
                          <!-- /ko -->
                          <!-- ko if: ($parent.ParametroCalculoIGVDesdeTotal() == 1) -->
                          <td class="col-sm-1">
                            <div class="input-group">
                              <input class="form-control  formulario text-right" data-bind="value : ValorVentaItem,
                              attr : { id : IdDetalleComprobanteVenta() + '_span_ValorVentaItem'},
                              event : { focus: function(data,event) { return OnFocusValorVentaItem(data,event,$parent.OnRefrescar); }, keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); }, focusout: ValidarValorVentaItem, change: function (data,event){return CalculoValorUnitario(data,event,$parent.OnRefrescar);} }, numbertrim: ValorVentaItem" type="text" data-validation="number_calc" data-validation-allowing="float,positive,range[0.001;9999999]" data-validation-decimal-separator="." data-validation-error-msg="De 0 a más">
                            </div>
                          </td>
                          <!-- /ko -->
                          <td class="col-sm-auto">
                            <div class="input-group ajuste-opcion-plusminus">
                              <button type="button" class="btn btn-default focus-control glyphicon glyphicon-minus no-tab" data-bind="click : function(data,event) {  return OnClickBtnOpcion(data,event,$parent.OnQuitarFila);  },
                              event : { focus: function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } ,keydown : function(data,event) { return OnKeyEnterOpcion(data,event,$parent.OnKeyEnter); }  },
                              attr : { id : IdDetalleComprobanteVenta() + '_a_opcion'}"></button>
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
          <div class="row">
            <div class="col-md-9 denominacion-moneda-nacional">
              <span data-bind="html : DenominacionTotal()" id="nletras"></span>
            </div>
            <div class="col-md-2 text-right">
              <!-- ko if: (ParametroDescuentoUnitario() != 0 && TipoVenta() == TIPO_VENTA.MERCADERIAS) -->
              <span data-bind="html : 'DESC. TOTAL : '+CalcularTotalDescuento()"></span>
              <!-- /ko -->
            </div>
            <div class="col-md-1 text-right">
              <span data-bind="html : 'ITEMS : '+TotalItems()"></span>
            </div>
          </div>
          <div class="row">
            <div class="" data-bind="css : DivFooterVenta()">
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <div class="addon-top">Op. Gravada</div>
                <input id="ValorVentaGravado" tabindex="-1" readonly class="form-control formulario numeric text-mumeric input-totales no-tab" type="text" placeholder="Op. Grav." data-bind="value: CalculoTotalVentaGravado()">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <div class="addon-top">Op. Exonerado</div>
                <input id="ValorVentaNoGravado" tabindex="-1" readonly class="form-control formulario numeric text-mumeric input-totales no-tab" type="text" placeholder="Op. No Grav." data-bind="value: CalculoTotalVentaNoGravado()">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <div class="addon-top">Op. Inafecto</div>
                <input id="ValorVentaInafecto" tabindex="-1" readonly class="form-control formulario numeric text-mumeric no-tab input-totales" type="text" placeholder="Op. Ina." data-bind="value: CalculoTotalVentaInafecto()">
              </div>
            </div>
            <!-- ko if:(IdTipoVenta() != TIPO_VENTA.SERVICIOS) -->
            <div class="col-md-2">
              <div class="form-group">
                <div class="addon-top">(Descto. Global)</div>
                <input id="DescuentoGlobal" class="form-control formulario numeric text-mumeric input-totales" type="text" placeholder="Des. Global" data-bind="
                value: DescuentoGlobal,
                numbertrim : DescuentoGlobal,
                enable: OnEnableDescuentoGlobal,
                event : {  focus: OnFocus , focusout : ValidarDescuentoGlobal , keydown : OnKeyEnter }">
              </div>
            </div>
            <!-- /ko -->
            <div class="col-md-1">
              <div class="form-group">
                <div class="addon-top">ICBPER</div>
                <input id="ICBPER" readonly tabindex="-1" class="form-control formulario numeric text-mumeric no-tab input-totales" type="text" placeholder="ICBPER" data-bind="value: ICBPER">
              </div>
            </div>
            <div class="col-md-1">
              <div class="form-group">
                <div class="addon-top">IGV</div>
                <input id="IGV" readonly tabindex="-1" class="form-control formulario numeric text-mumeric input-totales no-tab" type="text" placeholder="IGV" data-bind="value: CalculoTotalIGV()">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <div class="addon-top">Total</div>
                <input id="Total" readonly tabindex="-1" class="form-control formulario numeric text-mumeric input-totales no-tab" type="text" placeholder="Total" data-bind="value : CalculoTotalVenta()">
              </div>
            </div>
            <!-- ko if:(ParametroCampoACuenta() == 1) -->
            <div class="col-md-2">
              <div class="form-group">
                <div class="addon-top">A Cuenta</div>
                <input id="ACuenta" class="form-control formulario numeric text-mumeric input-totales" type="text" placeholder="0.00" data-bind="value : MontoACuenta, numbertrim :MontoACuenta, event:{ focus: OnFocus, keydown : OnKeyEnter}">
              </div>
            </div>
            <!-- /ko -->
          </div>
          <!-- ko if: (ParametroComprobantesAutomaticos() == 1 && (IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA  && (IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_T || IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_Z)) && opcionProceso() == 1) -->
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <div class="checkbox checkbox-inline">
                  <input id="OpcionComprobantesAutomaticos" type="checkbox" class="form-control formulario" data-bind="checked: OpcionComprobantesAutomaticos" />
                  <label for="OpcionComprobantesAutomaticos">¿DESEA GENERAR BOLETAS AUTOMÁTICAS?</label>
                </div>
                <div class="checkbox-inline" data-bind="visible: OpcionComprobantesAutomaticos">
                  <input id="CantidadComprobantesAutomaticos" type="text" class="form-control formulario" data-bind="value: CantidadComprobantesAutomaticos, number: CantidadComprobantesAutomaticos, event: { focus: OnFocus, keydown: OnKeyEnter }" />
                </div>
              </div>
            </div>
          </div>
          <!-- /ko -->
          <div class="row">
            <div class="col-md-2">
              <br>
              <strong class="alert-info">* Grabar = ALT + G</strong>
            </div>
            <div class="col-md-8">
              <center>
                <br>
                <button type="button" id="btn_Grabar" class="btn btn-success focus-control" data-bind="click : Guardar">Grabar</button> &nbsp;
                <button type="button" id="btn_Limpiar" class="btn btn-default focus-control" data-bind="click : Limpiar, visible : opcionProceso() == 1">Limpiar</button> &nbsp;
                <button type="button" id="BtnDeshacer" class="btn btn-default" data-bind="click : Deshacer, visible : opcionProceso() == 2">Deshacer</button> &nbsp;
                <button type="button" id="btn_Cerrar" class="btn btn-default" data-bind="click : OnClickBtnCerrar , visible :  opcionProceso() == 2">Cerrar</button>
                <p>
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