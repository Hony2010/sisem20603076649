<!-- ko with : ComprobanteCompra -->
<form id="formComprobanteCompra" name="formComprobanteCompra" role="form" autocomplete="off">
	<div class="datalist__result">
		<input id="IdTipoDocumento" class="form-control" type="hidden" placeholder="Documento"
			data-bind="value: IdTipoDocumento">
		<input id="IdTipoCompra" class="form-control" type="hidden" placeholder="Documento"
			data-bind="value: IdTipoCompra">
		<!-- <input id="IdTipoOperacion" class="form-control" type="hidden" placeholder="TipoOperacion" data-bind="value: IdTipoOperacion">-->
		<input id="IdComprobanteCompra" class="form-control" type="hidden" placeholder="IdComprobanteCompra">
		<input id="IdProveedor" class="form-control" type="hidden" placeholder="RUC/DNI:"
			data-bind="value: IdProveedor">

		<div class="tab-pane active" id="brand" role="tabpanel">
			<div class="scrollable scrollbar-macosx">
				<div class="container-fluid">
					<div class="row">
						<fieldset>
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario-venta">RUC - Proveedor</div>
												<input id="Proveedor" name="Proveedor" class="form-control formulario"
													type="text"
													data-bind="value : RUCDNIProveedor(),event : { focus : OnFocus }"
													data-validation="autocompletado_proveedor"
													data-validation-error-msg="No se han encontrado resultados para tu búsqueda de Proveedor"
													data-validation-text-found="">
												<div class="input-group-btn">
													<button type="button" class="btn-buscar btn btn-default no-tab"
														id="BtnNuevoProveedor"
														data-bind="click : function(data,event) {  return OnClickBtnNuevoProveedor(data,event,$parent.Proveedor); }"><span
															class="fa fa-plus-circle"></span></button>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario-venta">F. Emision</div>
												<input id="FechaEmision" name="FechaEmision"
													class="form-control formulario"
													data-inputmask-clearmaskonlostfocus="false"
													data-bind="value: FechaEmision, event: {focus : OnFocus , focusout : ValidarFechaEmision ,keydown : OnKeyEnter}"
													data-validation="date" data-validation-format="dd/mm/yyyy"
													data-validation-error-msg="La fecha de emision en invalida" />
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
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario-venta">Dirección</div>
												<input readonly tabindex="-1" class="form-control formulario no-tab"
													id="Direccion" data-bind="value : Direccion" type="text">
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
												<input id="FechaVencimiento" name="FechaVencimiento"
													class="form-control formulario"
													data-inputmask-clearmaskonlostfocus="false"
													data-bind="value: FechaVencimiento, event : {focus : OnFocus , focusout : ValidarFechaVencimiento , keydown : OnKeyEnter}"
													data-validation="fecha_vencimiento"
													data-validation-format="dd/mm/yyyy"
													data-validation-error-msg="La fecha de vencimiento es incorrecta.Solo es obligatoria cuando es forma de pago credito" />
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario-venta">Tipo Doc.</div>
												<select id="combo-tipodocumento" class="form-control formulario"
													data-bind="
                      value : IdTipoDocumento,
                      options : TiposDocumento,
                      optionsValue : 'IdTipoDocumento' ,
                      optionsText : 'NombreAbreviado' ,
                      event : { change : OnChangeTipoDocumento , focus : OnFocus , keydown : OnKeyEnter }">
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<div class="input-group formulario-venta">
												<div class="input-group-addon formulario-venta">Serie</div>
												<input id="SerieDocumento" type="text" name=""
													class="form-control formulario" value=""
													data-bind="value: SerieDocumento, event : {  focus : OnFocus , focusout : ValidarSerieDocumento , keydown : OnKeyEnter}, attr: {'maxlength': TamanoSerieCompra()}"
													data-validation="required"
													data-validation-error-msg="Debe Ingresar una Serie">
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario-venta">Numero</div>
												<input id="NumeroDocumento" class="form-control formulario" type="text"
													data-bind="value: NumeroDocumento , event : {  focus : OnFocus , focusout : ValidarNumeroDocumento, keydown : OnKeyEnter}"
													data-validation="number"
													data-validation-allowing="range[1;99999999]"
													data-validation-error-msg="El número documento debe ser numérico y tener como máximo 8 digitos">
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario-venta">Guia Remision</div>
												<input id="GuiaRemision" class="form-control formulario" type="text"
													data-bind="value: GuiaRemision ,event : { focus : OnFocus , keydown : OnKeyEnter}">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario-venta">OC:</div>
												<input id="OrdenCompra" class="form-control formulario" type="text"
													data-bind="value: OrdenCompra, event : { focus : OnFocus ,keydown : OnKeyEnter }">
											</div>
										</div>
									</div>
									<div class="col-md-9">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario-venta">Observación</div>
												<input id="Observacion" class="form-control formulario" type="text"
													data-bind="value: Observacion, event : { focus : OnFocus ,keydown : OnKeyEnter }">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<fieldset style="padding: 2px !important;">
											<legend>
												<div class="radio radio-inline">
													<input id="rad6" type="radio" name="radio" class="no-tab" value="0"
														data-bind="checked : EstadoPendienteNota,event : { change : OnChangeEstadoPendienteNota }">
													<label for="rad6">Afecta Kardex</label>
												</div>
												<div class="radio radio-inline">
													<input id="rad7" type="radio" name="radio" class="no-tab" value="2"
														data-bind="checked : EstadoPendienteNota,event : { change : OnChangeEstadoPendienteNota }">
													<label for="rad7">Pendiente entrega con Nota de Entrada</label>
												</div>
											</legend>
											<div class="col-md-6">
												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon formulario-venta">Almacen</div>
														<select id="combo-almacen" class="form-control formulario"
															data-bind="
                            value : IdAsignacionSede,
                            options : Sedes,
                            optionsValue : 'IdAsignacionSede' ,
                            optionsText : 'NombreSede' ,
                            event : { focus : OnFocus ,change : (data,event) => OnChangeComboAlmacen(data,event,$parent) , keydown : OnKeyEnter }"
															data-validation="required"
															data-validation-error-msg="No tiene almacen asignado">
														</select>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon formulario-venta">Fecha</div>
														<input id="FechaMovimientoAlmacen" name="FechaMovimientoAlmacen"
															class="form-control formulario"
															data-inputmask-clearmaskonlostfocus="false"
															data-bind="value: FechaMovimientoAlmacen, event : {focus : OnFocus , focusout : ValidarFechaMovimientoAlmacen,  keydown : OnKeyEnter}"
															data-validation="date" data-validation-format="dd/mm/yyyy"
															data-validation-error-msg="La fecha de nota de entrada es invalida" />
													</div>
												</div>
											</div>
										</fieldset>
									</div>
									<!-- ko if: (ParametroCaja() == 1)-->
									<div class="col-md-6" data-bind="visible : IdFormaPago() == ID_FORMA_PAGO_CONTADO">
										<fieldset style="padding: 2px !important;">
											<legend>Caja</legend>
											<div class="col-md-12">
												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon formulario-venta">Cajas</div>
														<select id="combo-caja" class="form-control formulario"
															data-bind="event:{ change: OnChangeCajas, focus : OnFocus, keydown : OnKeyEnter}"
															data-validation="required"
															data-validation-error-msg="No tiene caja asignado">
														</select>
													</div>
												</div>
											</div>
										</fieldset>
									</div>
									<!-- /ko -->
									<!-- ko if: (ParametroDocumentoSalidaZofra() != 0 && IdTipoDocumento() == ParametroTipoDocumentoSalidaZofra()) || (IdTipoDocumento() == ID_TIPO_DOCUMENTO_DUA || IdTipoDocumento() == ParametroTipoDocumentoDuaAlternativo())-->
									<div id="PanelDocumentoSalidaZofra" class="col-md-4">
										<fieldset style="padding: 2px !important;">
											<legend>
												<div class="checkbox">
													<input id="CheckDocumentoSalidaZofra"
														name="CheckDocumentoSalidaZofra" type="checkbox"
														data-bind="checked: CheckDocumentoSalidaZofra, event : {focus : OnFocus , keydown : OnKeyEnter, change: OnChangeCheckDocumentoSalidaZofra}">
													<label for="CheckDocumentoSalidaZofra">Documento Ingreso y/o
														Control</label>
												</div>
											</legend>
											<div class="col-md-12">
												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon formulario-venta">Doc. Ingreso /
															Control</div>
														<input readonly tabindex="-1" id="NumeroDocumentoSalidaZofra"
															name="NumeroDocumentoSalidaZofra"
															class="form-control formulario no-tab"
															data-inputmask-clearmaskonlostfocus="false"
															data-bind="value: NumeroDocumentoSalidaZofra, event : {focus : OnFocus , keydown : OnKeyEnter, focusout:ValidarDocumentoSalida}"
															data-validation="required"
															data-validation-error-msg="Debe ingresar un número de documento" />
														<div class="input-group-btn">
															<button type="button"
																class="btn-buscar btn btn-default no-tab"
																id="BtnDocumentoIngreso"
																data-bind="click: BuscarDocumentoIngreso"><span
																	class="glyphicon glyphicon-zoom-in"></span></button>
														</div>
													</div>
												</div>
											</div>
										</fieldset>
									</div>
									<!-- /ko -->
								</div>
								<div class="row" id="content_detracciones">
									<div class="col-md-12">
										<fieldset style="padding: 2px !important;">
											<legend>
												<div class="checkbox">
													<input name="CheckDetraccion" id="CheckDetraccion" type="checkbox"
														class="form-control formulario"
														data-bind="checked: CheckDetraccion, event: {focus : OnFocus , keydown : OnKeyEnter, change: OnChangeDetraccion}" />
													<label for="CheckDetraccion"> <b>Con detracción</b> </label>
												</div>
											</legend>
											<div id="GrupoAlmacen" class="col-md-4">
												<div class="form-group">
													<label for=""><b>Quien Paga : </b>&nbsp;</label>
													<div class="radio radio-inline">
														<input disabled tabindex="-1" id="radioCliente" type="radio"
															name="radioD" class="no-tab" value="1"
															data-bind="checked : PagadorDetraccion, event : {focus : OnFocus, keydown : OnKeyEnter} ">
														<label for="radioCliente"
															style="font-size : 13px">Cliente</label>
													</div>
													<div class="radio radio-inline">
														<input disabled tabindex="-1" id="radioProveedor" type="radio"
															name="radioD" class="no-tab" value="2"
															data-bind="checked : PagadorDetraccion, event : {focus : OnFocus, keydown : OnKeyEnter}">
														<label for="radioProveedor"
															style="font-size : 13px">Proveedor</label>
													</div>
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon formulario-venta">Nro Documento
														</div>
														<input id="DocumentoDetraccion"
															class="form-control formulario no-tab" type="text"
															data-bind="value: NumeroDocumentoDetraccion, event : {  focus : OnFocus , focusout : ValidarDocumentoDetraccion, keydown : OnKeyEnter}"
															data-validation="required"
															data-validation-error-msg="Debe ingresar un número de documento detracción">
													</div>
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon">Fecha</div>
														<input disabled id="FechaDetraccion" name="Fecha"
															class="form-control formulario no-tab" type="text"
															data-inputmask-clearmaskonlostfocus="false"
															data-bind="value: FechaDetraccion, event : {focus : OnFocus, focusout : ValidarFechaDetraccion, keydown : OnKeyEnter}"
															data-validation="date" data-validation-format="dd/mm/yyyy"
															data-validation-error-msg="La fecha de detracción es invalida" />
													</div>
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon">Monto</div>
														<input disabled id="Monto"
															class="form-control formulario numeric text-mumeric no-tab"
															type="text"
															data-bind="value : MontoDetraccion, numbertrim : MontoDetraccion, event : {  focus : OnFocus , keydown : OnKeyEnter }">
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
						<div class="btn-group">
							<button type="button" class="btn btn-primary no-tab" href="#"
								data-bind="click : function(data,event) {  return OnClickBtnBuscadorMercaderiaListaSimple(data,event,$parent);}">
								<span class="glyphicon glyphicon-search"></span> Buscar Mercadería con Lista
							</button>
						</div>
						<div class="text-right">
							<div class="radio radio-inline">
								<input id="DesdeBase" type="radio" name="radioCalculoIGV" value="0"
									data-bind="checked : IndicadorTipoCalculoIGV, event : { change : OnChangeIndicadorTipoCalculoIGV, keydown : OnKeyEnter }">
								<label for="DesdeBase">IGV DESDE BASE IMPONIBLE</label>
							</div>
							<div class="radio radio-inline">
								<input id="DesdeTotal" type="radio" name="radioCalculoIGV" value="1"
									data-bind="checked : IndicadorTipoCalculoIGV, event : { change : OnChangeIndicadorTipoCalculoIGV, keydown : OnKeyEnter }">
								<label for="DesdeTotal">IGV DESDE EL TOTAL</label>
							</div>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-12">
							<div class="row detalle-comprobante">
								<div class="col-md-12">
									<fieldset>
										<table class="datalist__table table display grid-detail-body table-border"
											width="100%" id="tablaDetalleComprobanteCompra">
											<thead>
												<tr>
													<th class="">#</th>
													<th class="col-sm-1 products__id">
														<c>Código(Producto)</c>
													</th>
													<th class="col-sm-4 products__title">Descripción</th>
													<th class="col-sm-1 products__title op-mercaderia"></th>
													<!-- ko if: ParametroCodigoProductoProveedor() != 0 -->
													<th class="col-sm-1 products__title op-codigoproductoproveedor">
														Código(Prov.)</th>
													<!-- /ko -->
													<!-- ko if: TipoDocumentoCompra() == ID_TIPO_DOCUMENTO_DUA || TipoDocumentoCompra() == ParametroTipoDocumentoDuaAlternativo() -->
													<th class="col-sm-1 products__title op-dua">Item DUA</th>
													<!-- /ko -->

													<!-- ko if: ParametroLote() != 0 -->
													<th class="col-sm-2 products__title op-lote">Lote</th>
													<th class="col-sm-1 products__title op-lote">F. Vencimiento</th>
													<!-- /ko -->

													<th class="products__title">
														<c>Unid.</c>
													</th>
													<th class="col-sm-1 products__title">
														<c>Cantidad</c>
													</th>
													<!-- ko if: IndicadorTipoCalculoIGV() == 0 -->
													<th class="col-sm-1 products__title">
														<c>Dscto (%)</c>
													</th>
													<!-- /ko -->
													<!-- ko if: IndicadorTipoCalculoIGV() == 1 -->
													<th class="col-sm-1 products__title">
														<c>P. U.</c>
													</th>
													<!-- /ko -->
													<!-- ko if: IndicadorTipoCalculoIGV() == 0 -->
													<th class="col-sm-1 products__title">
														<c>Costo Unit.</c>
													</th>
													<!-- /ko -->
													<th class="col-sm-1 products__title">
														<center>Afecto IGV</center>
													</th>
													<!-- ko if: IndicadorTipoCalculoIGV() == 0 -->
													<th class="col-sm-1 products__title">
														<c>Desc. Unit.</c>
													</th>
													<!-- /ko -->
													<th class="col-sm-1 products__title">
														<c>Importe</c>
													</th>
													<th class="col-sm-1 products__title"></th>
												</tr>
											</thead>
											<tbody>
												<!-- ko foreach : DetallesComprobanteCompra -->
												<tr name="Fila" class="clickable-row" style="min-height: 80px;"
													data-bind="attr : { id: IdDetalleComprobanteCompra }, click :  function(data,event) { return OnClickFila(data,event,$parent.OnRefrescar); } ">
													<td data-bind="text: NumeroItem" style="vertical-align: middle;">
													</td>
													<td class="col-sm-1">
														<div class="input-group">
															<input class="form-control formulario"
																data-bind="
																value: CodigoMercaderia,
																valueUpdate : 'keyup',
																attr : { id : IdDetalleComprobanteCompra() + '_input_CodigoMercaderia'},
																event : {focus : function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnterCodigoMercaderia(data,event,$parent.OnKeyEnter); }  }"
																type="text" data-validation="validacion_producto"
																data-validation-error-msg="Cod. Inválido"
																data-validation-found="false"
																data-validation-text-found="">
															<!-- , focusout : ValidarCodigoMercaderia -->
														</div>
													</td>
													<td class="col-sm-4">
														<div class="input-group">
															<!-- ko if: $parent.ParametroRubroRepuesto() == 0-->
															<input class="form-control formulario" data-bind="
																	value: NombreProducto,
																	attr : { id : IdDetalleComprobanteCompra() + '_input_NombreProducto', 'data-validation-reference' : IdDetalleComprobanteCompra() + '_input_CodigoMercaderia'  },
																	event: { focus : function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); }  }"
																type="text" data-validation="autocompletado_producto"
																data-validation-error-msg="No se han encontrado resultados para tu búsqueda de producto">
															<!-- /ko -->
															<!-- ko if: $parent.ParametroRubroRepuesto() == 1-->
															<input class="form-control formulario" data-bind="
																value: NombreLargoProducto,
																attr : { id : IdDetalleComprobanteCompra() + '_input_NombreProducto', 'data-validation-reference' : IdDetalleComprobanteCompra() + '_input_CodigoMercaderia'  },
																event: { focus : function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); }  }"
																type="text" data-validation="autocompletado_producto"
																data-validation-error-msg="No se han encontrado resultados para tu búsqueda de producto">
															<!-- /ko -->
														</div>
													</td>
													<td>
														<button type="button" style="background: none; border: none;"
															class="btn-default btn-buscar btn-control" name="button"
															data-bind="
															click : function(data,event) {  return OnClickBtnNuevaMercaderia(data,event,$root.data.Mercaderia);},
															attr : { id : IdDetalleComprobanteCompra() + '_opcion_mercaderia'}"> <i class="fas fa-plus"></i>
														</button>
													</td>
													<!-- ko if: $parent.ParametroCodigoProductoProveedor() != 0 -->
													<td class="col-sm-1">
														<div class="input-group">
															<input name="CodigoProductoProveedor"
																class="form-control formulario inputs"
																data-bind="
																	value : CodigoProductoProveedor ,
																	attr : { id : IdDetalleComprobanteCompra() + '_input_CodigoProductoProveedor' },
																	event: { focus: function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnterTotales); } , focusout : ValidarCodigoPorProvedor}"
																type="text" data-validation="required"
																data-validation-error-msg="Requerido ">
														</div>
													</td>
													<!-- /ko -->
													<!--PARA DUA-->
													<!-- ko if: $parent.TipoDocumentoCompra() == ID_TIPO_DOCUMENTO_DUA || $parent.TipoDocumentoCompra() == $parent.ParametroTipoDocumentoDuaAlternativo() -->
													<td class="col-sm-1 op-dua">
														<div class="input-group">
															<input name="NumeroDua"
																class="form-control formulario inputs"
																data-bind="
																value : NumeroItemDua ,
																attr : { id : IdDetalleComprobanteCompra() + '_input_ItemDua' },
																event: { focus: function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnterTotales); } }"
																type="text" data-validation="number"
																data-validation="number"
																data-validation-allowing="float,positive,range[1;9999999]"
																data-validation-error-msg="De 0 a más">
														</div>
													</td>
													<!-- /ko -->
													<!--PARA LOTE-->
													<!-- ko if: $parent.ParametroLote() != 0 -->
													<td class="col-sm-2">
														<div class="input-group">
															<input name="NumeroLote"
																class="form-control formulario inputs"
																data-bind="value : NumeroLote ,
																attr : { id : IdDetalleComprobanteCompra() + '_input_NumeroLote' },
																event: { focus: function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnterTotales); } , focusout: ValidarLote}"
																type="text">
														</div>
													</td>
													<td class="col-sm-1">
														<div class="input-group">
															<input name="FechaVencimientoLote"
																class="form-control formulario inputs"
																data-inputmask-clearmaskonlostfocus="false"
																data-bind="value : FechaVencimiento ,
																attr : { id : IdDetalleComprobanteCompra() + '_input_FechaVencimientoLote' },
																event: { focus: function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnterTotales); } , focusout: ValidarFechaVencimiento}"
																type="text" data-validation-format="dd/mm/yyyy"
																data-validation-error-msg="La fecha de nota de entrada es invalida">
														</div>
													</td>
													<!-- /ko -->
													<!--FIN PARA LOTE-->
													<td class="text-center">
														<span class=""
															data-bind="text : AbreviaturaUnidadMedida, attr : { id : IdDetalleComprobanteCompra() + '_span_AbreviaturaUnidadMedida'}"></span>
													</td>
													<td class="col-sm-1">
														<input name="Cantidad"
															class="form-control formulario numeric text-mumeric"
															data-bind="
															value : Cantidad,
															attr : { id : IdDetalleComprobanteCompra() + '_input_Cantidad', 'data-cantidad-decimal' : DecimalCantidad() },
															event: {
																focus : function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); }, 
																keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnterTotales); }, 
																focusout : ValidarCantidad, 
																blur: function (data,event){return CalculoSubTotal(data,event,$parent.OnRefrescar);} }, 
															numberdecimal : Cantidad, /*ko_autocomplete_cantidad: { source: DataPrecios(), raleo: DataRaleo(),*/ precio: PrecioUnitario}"
															type="text" data-validation="number_calc"
															data-validation-allowing="float,positive,range[0.001;9999999]"
															data-validation-decimal-separator="."
															data-validation-error-msg="De 0 a más">
													</td>
													<!-- ko if: $parent.IndicadorTipoCalculoIGV() == 0 -->
													<td class="col-sm-1">
														<input name="PrecioUnitario"
															class="form-control  formulario numeric text-mumeric inputs"
															data-bind="
															value : TasaDescuentoUnitario ,
															attr : { id : IdDetalleComprobanteCompra() + '_input_TasaDescuentoUnitario'},
															numbertrim: TasaDescuentoUnitario, 
															event: { 
																focus: function(data,event) { return OnFocusTasaDescuentoUnitario(data,event,$parent.OnRefrescar); }, 
																keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnterTotales); }, 
																focusout : ValidarTasaDescuentoUnitario, 
																blur: function (data,event){return CalculoSubTotal(data,event,$parent.OnRefrescar);}}" type="text" data-validation="number_desc"
															data-validation-allowing="float,positive,range[0.001;9999999]"
															data-validation-error-msg="Solo positivo">
													</td>
													<!-- /ko -->
													<!-- ko if: $parent.IndicadorTipoCalculoIGV() == 1 -->
													<td class="col-sm-1">
														<input name="PrecioUnitario"
															class="form-control  formulario numeric text-mumeric inputs"
															data-bind="
															value : PrecioUnitario ,
															attr : { id : IdDetalleComprobanteCompra() + '_input_PrecioUnitario', 'data-cantidad-decimal' : DecimalPrecioUnitario()},
															event: { 
																focus: function(data,event) { return OnFocusPrecioUnitario(data,event,$parent.OnRefrescar); }, 
																keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnterTotales); }, 
																focusout : ValidarPrecioUnitario, 
																blur: function (data,event){return CalculoSubTotal(data,event,$parent.OnRefrescar);} },
															numberdecimal : PrecioUnitario, /*ko_autocomplete: { source: DataPrecios(), raleo: DataRaleo(),*/
															precio: PrecioUnitario}" type="text" data-validation="number_desc"
															data-validation-allowing="float,positive,range[0.001;9999999]"
															data-validation-decimal-separator="."
															data-validation-error-msg="Solo positivo">
													</td>
													<!-- /ko -->
													<!-- ko if: $parent.IndicadorTipoCalculoIGV() == 0 -->
													<td class="col-sm-1">
														<input name="CostoUnitario"
															class="form-control  formulario numeric text-mumeric inputs"
															data-bind="value : CostoUnitario ,
															attr : { id : IdDetalleComprobanteCompra() + '_input_CostoUnitario', 'data-cantidad-decimal' : DecimalCostoUnitario()},
															event: { 
																focus: function(data,event) { return OnFocusPrecioUnitario(data,event,$parent.OnRefrescar); } , 
																keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnterTotales); } , 
																focusout : ValidarCostoUnitario, 
																blur: function (data,event){return CalculoSubTotal(data,event,$parent.OnRefrescar);} } ,
															numberdecimal : CostoUnitario, /*ko_autocomplete: { source: DataPrecios(), raleo: DataRaleo(),*/
															precio: CostoUnitario}" type="text" data-validation="number_desc"
															data-validation-allowing="float,positive,range[0.001;9999999]"
															data-validation-decimal-separator="."
															data-validation-error-msg="Solo positivo">
													</td>
													<!-- /ko -->
													<td class="col-sm-1">
														<div class="form-group">
															<select name="AfectoIGV" id="AfectoIGV"
																class="form-control formulario" data-bind="
																value : AfectoIGV,
																attr : { id : IdDetalleComprobanteCompra() + '_input_AfectoIGV' },
																disable: AfectoBonificacion() == 1 && $parent.ParametroBonificacion() == 1,
																event: { 
																change: function (data,event){return CalculoSubTotal(data,event,$parent.OnRefrescar);}, 
																blur: function (data,event){return CalculoSubTotal(data,event,$parent.OnRefrescar);}, 
																focus: function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , 
																keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnterTotales); } , 
																focusout : ValidarAfectoIGV }">
																<option value="1">SI</option>
																<option value="0">NO</option>
															</select>
														</div>
													</td>
													<!-- ko if: $parent.IndicadorTipoCalculoIGV() == 0 -->
													<td class="col-sm-1">
														<input name="DescuentoUnitario"
															class="form-control formulario numeric text-mumeric inputs"
															data-bind="value : DescuentoUnitario ,
															attr : { id : IdDetalleComprobanteCompra() + '_input_DescuentoUnitario','data-cantidad-decimal' : DecimalDescuentoUnitario() },
															event: {
																change: function (data,event){return CalculoSubTotal(data,event,$parent.OnRefrescar);},
																blur: function (data,event){return CalculoSubTotal(data,event,$parent.OnRefrescar);},
																focus: function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } ,
																keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnterTotales); } ,
																focusout : ValidarDescuentoUnitario},
															numberdecimal : DescuentoUnitario" type="text" data-validation="number_desc"
															data-validation-allowing="float,positive,range[0;9999999]"
															data-validation-decimal-separator="."
															data-validation-error-msg="De 0 a más">
													</td>
													<!-- /ko -->
													<!-- ko if: $parent.IndicadorTipoCalculoIGV() == 1 -->
													<td class="col-sm-1">
														<div class="input-group">
															<input class="form-control  formulario text-right"
																data-bind="
															value : PrecioItem,
															attr : { id : IdDetalleComprobanteCompra() + '_input_PrecioCosto'},
															event : { 
																focus: function(data,event) { return CalculoSubTotal(data,event,$parent.OnRefrescar); }, 
																keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); }, 
																focusout: ValidarPrecioCosto, 
																change: function (data,event){return CalculoPrecioCosto(data,event,$parent.OnRefrescar);} },
															numbertrim:PrecioItem" type="text" data-validation="number_desc"
																data-validation-allowing="float,positive,range[0.001;9999999]"
																data-validation-decimal-separator="."
																data-validation-error-msg="De 0 a más">
														</div>
													</td>
													<!-- /ko -->
													<!-- ko if: $parent.IndicadorTipoCalculoIGV() == 0 -->
													<td class="col-sm-1">
														<div class="input-group">
															<input class="form-control  formulario text-right"
																data-bind="
															value : CostoItem,
															attr : { id : IdDetalleComprobanteCompra() + '_input_PrecioCosto'},
															event : { focus: function(data,event) { return CalculoSubTotal(data,event,$parent.OnRefrescar); }, keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); }, focusout: ValidarPrecioCosto, change: function (data,event){return CalculoPrecioCosto(data,event,$parent.OnRefrescar);} },
															numbertrim: CostoItem" type="text" data-validation="number_desc"
																data-validation-allowing="float,positive,range[0.001;9999999]"
																data-validation-decimal-separator="."
																data-validation-error-msg="De 0 a más">
														</div>
													</td>
													<!-- /ko -->
													<!-- ko if: $parent.IndicadorReferenciaCostoAgregado().length == '0' -->
													<td class="col-sm-auto">
														<div class="input-group ajuste-opcion-plusminus">
															<button type="button"
																class="btn btn-default focus-control glyphicon glyphicon-minus no-tab"
																data-bind="click : function(data,event) {  return OnClickBtnOpcion(data,event,$parent.OnQuitarFila);  },
																event : { focus: function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } ,keydown : function(data,event) { return OnKeyEnterOpcion(data,event,$parent.OnKeyEnterTotales); }  },
																attr : { id : IdDetalleComprobanteCompra() + '_a_opcion'}"></button>
														</div>
													</td>
													<!-- /ko -->
												</tr>
												<!-- /ko -->
											</tbody>
										</table>
									</fieldset>
								</div>
							</div>
						</div>
					</div>

					<!-- <div class="row">
            <div class="col-md-12 denominacion-moneda-nacional">
              <span data-bind="html : DenominacionTotal()" id="nletras"></span>
            </div>
		  </div> -->
					<div class="row">
						<div class="col-md-10"></div>
						<div class="col-md-2 text-right">
							<span data-bind="html : 'CANT. TOTAL : '+TotalCantidades()"></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<div class="addon-top">Op. Gravada</div>
								<input id="ValorCompraGravado" tabindex="-1" readonly
									class="form-control formulario numeric text-mumeric input-totales no-tab"
									type="text" placeholder="Op. Grav." data-bind="value: CalculoTotalCompraGravado()">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<!-- <div class="addon-top">Op. Exonerado</div> -->
								<div class="addon-top">Op. No Gravada</div>
								<input id="ValorCompraNoGravado" tabindex="-1" readonly
									class="form-control formulario numeric text-mumeric input-totales no-tab"
									type="text" placeholder="Op. No Grav."
									data-bind="value: CalculoTotalCompraNoGravado()">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<div class="addon-top">Op. Inafecto</div>
								<input id="ValorCompraNoGravado" tabindex="-1" readonly
									class="form-control formulario numeric text-mumeric input-totales no-tab"
									type="text" placeholder="Op. Inafecto."
									data-bind="value: CalculoTotalVentaInafecto()">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<div class="addon-top">(Descto. Global)</div>
								<input id="DescuentoGlobal"
									class="form-control formulario numeric text-mumeric input-totales" type="text"
									placeholder="Des. Global" data-bind="
									value: DescuentoGlobal,
									numbertrim: DescuentoGlobal,
									enable: OnEnableDescuentoGlobal,
									event: { focus: OnFocus , change: ValidarDescuentoGlobal , keydown: OnKeyEnter }">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<div class="addon-top">IGV</div>
								<input id="IGV" readonly tabindex="-1"
									class="form-control formulario numeric text-mumeric input-totales no-tab"
									type="text" placeholder="IGV" data-bind="value: CalculoTotalIGV()">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<div class="addon-top">Total</div>
								<input id="Total" readonly tabindex="-1"
									class="form-control formulario numeric text-mumeric input-totales no-tab"
									type="text" placeholder="Total" data-bind="value : CalculoTotalCompra()">
							</div>
						</div>
						<!-- ko if:(ParametroCampoACuenta() == 1) -->
						<div class="col-md-2">
							<div class="form-group">
								<div class="addon-top">A Cuenta</div>
								<input id="ACuenta" class="form-control formulario numeric text-mumeric input-totales"
									type="text" placeholder="0.00"
									data-bind="value : MontoACuenta, numbertrim :MontoACuenta, event:{ focus: OnFocus, keydown : OnKeyEnter}">
							</div>
						</div>
						<!-- /ko -->
					</div>
					<div class="row">
						<div class="col-md-6">
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<div class="addon-top">Tasa Percep. %</div>
								<input id="TasaPercepcionPorcentaje"
									class="form-control formulario numeric text-mumeric input-totales no-tab"
									type="text" data-bind="
                value: TasaPercepcionPorcentaje,
                numbertrim: TasaPercepcionPorcentaje,
                event: { focus: OnFocus, change : CalcularMontosPercepcion }">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<div class="addon-top">Monto Percep.</div>
								<input id="MontoPercepcion" readonly tabindex="-1"
									class="form-control formulario numeric text-mumeric input-totales no-tab"
									type="text" data-bind="
                value : MontoPercepcion">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<div class="addon-top">Total + Percep.</div>
								<input id="TotalMasPercepcion" readonly tabindex="-1"
									class="form-control formulario numeric text-mumeric input-totales no-tab"
									type="text" data-bind="
                value : TotalMasPercepcion">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<br>
							<strong class="alert-info">* Grabar = ALT + G</strong>
						</div>
						<div class="col-md-6">
							<center>
								<br>
								<button type="button" id="btn_Grabar" class="btn btn-success focus-control"
									data-bind="click : Guardar">Grabar</button> &nbsp;
								<button type="button" id="btn_Limpiar" class="btn btn-default focus-control"
									data-bind="click : Limpiar, visible : opcionProceso() == 1">Limpiar</button> &nbsp;
								<button type="button" id="BtnDeshacer" class="btn btn-default "
									data-bind="click : Deshacer, visible : opcionProceso() == 2">Deshacer</button>
								&nbsp;
								<button type="button" id="btn_Cerrar" class="btn btn-default "
									data-bind="click : OnClickBtnCerrar , visible :  opcionProceso() == 2">Cerrar</button>
								<br>
								<p>
							</center>
						</div>
						<div class="col-md-3">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<!-- /ko -->
