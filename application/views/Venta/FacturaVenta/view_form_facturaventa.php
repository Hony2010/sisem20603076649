<!-- ko with : FacturaVenta -->
<form id="formFacturaVenta" name="formFacturaVenta" role="form" autocomplete="off">
	<div class="datalist__result">
		<input id="IdTipoDocumento" class="form-control" type="hidden" placeholder="Documento"
			data-bind="value: IdTipoDocumento">
		<input id="IdTipoVenta" class="form-control" type="hidden" placeholder="Documento"
			data-bind="value: IdTipoVenta">
		<!-- <input id="IdTipoOperacion" class="form-control" type="hidden" placeholder="TipoOperacion" data-bind="value: IdTipoOperacion">-->
		<input id="IdComprobanteVenta" class="form-control" type="hidden" placeholder="IdComprobanteVenta">
		<input id="IdCliente" class="form-control" type="hidden" placeholder="RUC/DNI:" data-bind="value: IdCliente">
		<a id="Nueva_Factura" class="ocultar" target="_blank"
			href="<?php echo site_url() ?>/Venta/cEmisionFacturaVenta/"></a>
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
												<select name="combo-seriedocumento" id="combo-seriedocumento"
													class="form-control formulario" data-bind="
													value : IdCorrelativoDocumento,
													options : SeriesDocumento,
													optionsValue : 'IdCorrelativoDocumento' ,
													optionsText : 'SerieDocumento',
													event : { focus : OnFocus , change : OnChangeSerieDocumento , keydown : OnKeyEnter}"
													data-validation="required"
													data-validation-error-msg="No tiene serie asignada">
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario-venta">Número</div>
												<input id="NumeroDocumento" class="form-control formulario no-tab"
													type="text" tabindex="-1"
													data-bind="
													value: NumeroDocumento ,
													attr : { readonly : CheckNumeroDocumento }, event : {  focus : OnFocus , focusout : ValidarNumeroDocumento , keydown : OnKeyEnter }"
													data-validation="number"
													data-validation-allowing="range[1;99999999]"
													data-validation-error-msg="El número documento debe ser numérico y tener como máximo 8 digitos"
													data-validation-depends-on="CheckNumeroDocumento"
													data-validation-optional="true">
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<div class="input-group">
												<div class="checkbox">
													<input name="CheckNumeroDocumento" id="CheckNumeroDocumento"
														type="checkbox" class="form-control formulario"
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
												<div class="input-group-addon formulario-venta">F. Emisión</div>
												<input id="FechaEmision" name="FechaEmision"
													class="form-control formulario"
													data-bind="
													value: FechaEmision, 
													event: {focus : OnFocus , focusout : ValidarFechaEmision, keydown : OnKeyEnter, change: OnChangeFechaEmision}"
													data-inputmask-clearmaskonlostfocus="false" data-validation="date"
													data-validation-format="dd/mm/yyyy"
													data-validation-error-msg="La fecha de emision en invalida" />
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario-venta">RUC - Cliente</div>
												<input id="Cliente" name="Cliente" class="form-control formulario"
													type="text"
													data-bind="value : RUCDNICliente(),event : { focus : OnFocus }"
													data-validation="autocompletado_cliente"
													data-validation-error-msg="No se han encontrado resultados para tu búsqueda de cliente"
													data-validation-text-found="">
												<div class="input-group-btn">
													<button type="button" class="btn-buscar btn btn-default no-tab"
														id="BtnNuevoCliente"
														data-bind="click : function(data,event) {  return OnClickBtnNuevoCliente(data,event,$parent.Cliente); }"><span
															class="fa fa-plus-circle"></span></button>
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
									<div class="col-md-3" data-bind="visible : IdFormaPago() == ID_FORMA_PAGO_CREDITO">
<!-- 										<div class="form-group">
											<div class="input-group"> -->
												<!-- <div class="input-group-addon formulario-venta"></div> -->
<!-- 												<input id="FechaVencimiento" name="FechaVencimiento"
													style="height:27px !important;" class="form-control formulario"
													data-inputmask-clearmaskonlostfocus="false"
													data-bind="value: FechaVencimiento, event : {focus : OnFocus , focusout : ValidarFechaVencimiento , keydown : OnKeyEnter}"
													data-validation="fecha_vencimiento"
													data-validation-format="dd/mm/yyyy"
													data-validation-error-msg="La fecha de vencimiento es incorrecta.Solo es obligatoria cuando es forma de pago credito" /> -->
												<div class="input-group">
													<button type="button" class="btn btn-primary no-tab" style="width: auto !important;padding-left : 10px !important;padding-right:10px;"
														id="BtnCuotasPagoClienteComprobanteVenta"
														data-bind="click : function(data,event) {  return OnClickBtnAbrirPlanCuotasPago(data,event); }"><span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;Agregar Cuotas de Pago</button>
												</div>													
											<!-- </div> -->
										<!-- </div> -->
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario-venta">Dirección</div>
												<select id="combo-direcciones" class="form-control formulario"
													data-bind="
													value : Direccion,
													options : DireccionesCliente,
													optionsValue : 'Direccion' ,
													optionsText : 'Direccion' ,
													event : { focus : OnFocus, keydown : OnKeyEnter, change : OnChangeDireccion  }">
												</select>
												<!-- <input readonly tabindex="-1" class="form-control formulario no-tab" id="Direccion" data-bind="value : Direccion" type="text"> -->
											</div>
										</div>
									</div>
									<!-- ko if:(MostrarCampos.GuiaRemision() == "1" ) -->
									<div class="col-md-3">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario-venta">Guia Remision</div>
												<input id="GuiaRemision" class="form-control formulario" type="text"
													data-bind="value: GuiaRemision ,event : { focus : OnFocus , keydown : OnKeyEnter}">
											</div>
										</div>
									</div>
									<!-- /ko -->
									<!-- ko if:(MostrarCampos.OrdenCompra() == "1" ) -->
									<div class="col-md-3">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario-venta">O.C.</div>
												<input id="OrdenCompra" class="form-control formulario" type="text"
													data-bind="value: OrdenCompra, event : { focus : OnFocus ,keydown : OnKeyEnter }">
											</div>
										</div>
									</div>
									<!-- /ko -->
									<!-- ko if:(MostrarCampos.Observacion() == "1" ) -->
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario-venta">Otros Datos</div>
												<input id="OrdenCompra" class="form-control formulario" type="text"
													data-bind="value: Observacion, event : { focus : OnFocus ,keydown : OnKeyEnter }">
											</div>
										</div>
									</div>
									<!-- /ko -->
									<!-- ko if:(ParametroProforma() == 1 ) -->
									<!-- ko if: ParametroSeleccionUnaProformaVentas() == 1 -->
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario-venta">Doc. Proforma</div>
												<input id="DocumentoVentaProforma" class="form-control formulario"
													type="text" data-bind="
													value: DocumentoVentaProforma,
													event: { focus : OnFocus, change: ValidarComprobanteVentaProforma }"
													data-validation="autocompletado_opcional"
													data-validation-error-msg="" data-validation-text-found="">
											</div>
										</div>
									</div>
									<!-- /ko -->
									<!-- ko if: ParametroSeleccionUnaProformaVentas() == 0 -->
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario-venta">Doc. Proformas</div>
												<select id="combo-direcciones" class="form-control formulario"
													data-bind="
													options : ProformasComprobanteVenta,
													optionsValue : 'IdProforma',
													optionsText : 'Documento',
													event : { focus : OnFocus, keydown : OnKeyEnter }">
												</select>
												<div class="input-group-btn">
													<button type="button" class="btn-buscar btn btn-default no-tab"
														data-bind="click : (data,event) => OnClickBtnBuscarProformas(data, event, $parent.FacturaVenta)">
														<span class="fa fa-search"></span>
													</button>
												</div>
											</div>
										</div>
									</div>
									<!-- /ko -->
									<!-- /ko -->
								</div>
								<!-- ko if:( ParametroTransporte() == "1" && TipoVenta() == TIPO_VENTA.SERVICIOS)-->
								<div class="row" id="DivTransporte">
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-12">
												<div class="col-md-3">
													<div class="form-group">
														<div class="input-group">
															<div class="input-group-addon formulario-venta">Fecha
																Expedición</div>
															<input id="FechaExpedicion"
																class="form-control formulario fecha" type="text"
																data-bind="value: FechaExpedicion, event : { focus : OnFocus ,keydown : OnKeyEnter }">
														</div>
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<div class="input-group">
															<div class="input-group-addon formulario-venta">Numero
																Asiento</div>
															<input id="NumeroAsiento" class="form-control formulario"
																type="text" data-bind="
																value: NumeroAsiento,
																event : { focus : OnFocus ,keydown : OnKeyEnter }" data-validation="required"
																data-validation-error-msg="Campo obligatorio">
														</div>
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<div class="input-group">
															<div class="input-group-addon formulario-venta">Origen</div>
															<select id="combo-lugarorigen"
																class="form-control formulario valid-producto"
																data-bind="
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
															<div class="input-group-addon formulario-venta">Destino
															</div>
															<select id="combo-lugardestino"
																class="form-control formulario valid-producto"
																data-bind="
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
												<div class="col-md-2">
													<div class="form-group">
														<div class="input-group">
															<div class="input-group-addon formulario-venta">Hora de
																Partida</div>
															<input id="HoraPartida" class="form-control formulario"
																type="text" data-bind="
																value: HoraPartida,
																event : { focus : OnFocus ,keydown : OnKeyEnter }" data-validation="required"
																data-validation-error-msg="Campo obligatorio">
														</div>
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<div class="radio radio-inline">
															<input id="IndicadorAm" type="radio" name="IndicadorAmPm"
																value="1"
																data-bind="checked : IndicadorAmPm, event : { change : $root.OnChangeTipoAfectacionIGV }">
															<label for="IndicadorAm">AM</label>
														</div>
														<div class="radio radio-inline">
															<input id="IndicadorPm" type="radio" name="IndicadorAmPm"
																value="2"
																data-bind="checked : IndicadorAmPm, event : { change : $root.OnChangeTipoAfectacionIGV }">
															<label for="IndicadorPm">PM</label>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- /ko -->
								<div class="row">
									<!-- ko if:(TipoVenta() == TIPO_VENTA.MERCADERIAS && MostrarCampos.GrupoAlmacen() == "1" ) -->
									<div id="GrupoAlmacen" class="col-md-7">
										<fieldset style="padding: 2px !important;">
											<legend>
												<div class="radio radio-inline">
													<input id="radFac1" type="radio" name="radio" class="" value="0"
														data-bind="checked : EstadoPendienteNota,event : { change : OnChangeEstadoPendienteNota, keydown : OnKeyEnter }">
													<label for="radFac1">Afecta Kardex</label>
												</div>
												<div class="radio radio-inline">
													<input id="radFac2" type="radio" name="radio" class="" value="1"
														data-bind="checked : EstadoPendienteNota,event : { change : OnChangeEstadoPendienteNota, keydown : OnKeyEnter }">
													<label for="radFac2">Pendiente entrega con Nota de Salida</label>
												</div>
												<div class="radio radio-inline">
													<input id="radFac3" type="radio" name="radio" class="" value="3"
														data-bind="checked : EstadoPendienteNota,event : { change : OnChangeEstadoPendienteNota, keydown : OnKeyEnter }">
													<label for="radFac3">Con Nota de salida</label>
												</div>
											</legend>
											<div class="col-md-6">
												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon formulario-venta">Almacén</div>
														<select id="combo-almacen" class="form-control formulario"
															data-bind="
															value : IdAsignacionSede,
															options : Sedes,
															optionsValue : 'IdAsignacionSede' ,
															optionsText : 'NombreSede' ,
															event : { focus : OnFocus , change : (data,event) => OnChangeComboAlmacen(data,event,$parent) , keydown : OnKeyEnter }"
															data-validation="required"
															data-validation-error-msg="No tiene almacen asignado">
														</select>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon formulario-venta">Fecha Movimiento
														</div>
														<input id="FechaMovimientoAlmacen" name="FechaMovimientoAlmacen"
															class="form-control formulario"
															data-inputmask-clearmaskonlostfocus="false"
															data-bind="value: FechaMovimientoAlmacen, event : {focus : OnFocus , focusout : ValidarFechaMovimientoAlmacen,  keydown : OnKeyEnter}"
															data-validation="date" data-validation-format="dd/mm/yyyy"
															data-validation-error-msg="La fecha de nota de salida es invalida" />
													</div>
												</div>
											</div>
										</fieldset>
									</div>
									<div class="col-md-5" data-bind="visible: EstadoPendienteNota() == 3">
										<fieldset style="padding: 2px !important;">
											<legend> Buscar Nota de salida </legend>
											<div class="col-md-5">
												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon formulario-venta">Serie</div>
														<select id="SeriesNotaSalida" class="form-control formulario"
															data-bind="
																value : SerieNotaSalida,
																options : SeriesNotaSalida,
																optionsValue : 'SerieDocumento' ,
																optionsText : 'SerieDocumento',
																enable : EstadoPendienteNota() == COMPROBANTE_CON_NOTA_SALIDA,
																event:{ change: OnChangeCajas, focus : OnFocus, keydown : OnKeyEnter}" data-validation="required"
															data-validation-error-msg="No tiene Serie asignado">
														</select>
													</div>
												</div>
											</div>
											<div class="col-md-5">
												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon formulario-venta">Numero</div>
														<input id="NumeroNotaSalida" type="text"
															class="form-control formulario" data-validation="required"
															data-validation-error-msg="Este campo es obligatorio"
															data-bind="value: NumeroNotaSalida, enable : EstadoPendienteNota() == COMPROBANTE_CON_NOTA_SALIDA, event:{focus : OnFocus, keydown : OnKeyEnter}">
													</div>
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<button type="button" class="btn btn-primary btn-control"
														name="button"
														data-bind="enable : EstadoPendienteNota() == COMPROBANTE_CON_NOTA_SALIDA, event: { click:OnClickBtnCargarNotaSalida }"><span
															class="glyphicon glyphicon-search"></span></button>
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
									<!-- ko if: (ParametroRubroLubricante() == 1)-->
									<div class="col-md-6" data-bind="visible : IdFormaPago() == ID_FORMA_PAGO_CONTADO">
										<fieldset style="padding: 2px !important;">
											<legend>Vehiculo</legend>
											<div class="col-md-6">
												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon formulario-venta">Nro Placa</div>
														<input id="NumeroPlaca" type="text"
															class="form-control formulario" data-bind="
															value: NumeroPlaca,
															event: { focus : OnFocus, change: ValidarNumeroPlaca" data-validation="autocompletado_opcional"
															data-validation-error-msg="" data-validation-text-found="">
														<div class="input-group-btn">
															<button type="button"
																class=" btn-buscar btn btn-default no-tab"
																data-bind="click : (data,event) => OnClickBtnNuevoVehiculoCliente(data,event,$parent.Cliente)">
																<span class="fa fa-plus-circle"></span>
															</button>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon formulario-venta">Kilometraje
														</div>
														<input id="KilometrajeVehiculo" type="text"
															class="form-control formulario text-right"
															data-validation="number_desc"
															data-bind="value: KilometrajeVehiculo, event:{focus : OnFocus, keydown : OnKeyEnter}">
													</div>
												</div>
											</div>
											<div class="col-md-12">
												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon formulario-venta">Radio Taxi</div>
														<input id="NombreRadioTaxi" class="form-control formulario"
															type="text" data-bind="
															value: NombreRadioTaxi,
															event: { focus : OnFocus, change: ValidarRadioTaxi }" data-validation="autocompletado_opcional"
															data-validation-error-msg="" data-validation-text-found="">
													</div>
												</div>
											</div>
										</fieldset>
									</div>
									<!-- /ko -->
								</div>
								<!-- ko if: (ParametroDetraccion() == 1)-->
								<div class="row">
									<div class="col-md-6" style="margin-top: 10px;">
									<fieldset style="padding: 2px !important;">
											<legend>
												<div class="checkbox">
													<input name="CheckEstadoDetraccion" id="CheckEstadoDetraccion" type="checkbox"
														class="form-control formulario"
														data-bind="checked: EstadoDetraccion, event: {focus : OnFocus , keydown : OnKeyEnter, change: OnChangeCheckEstadoDetraccion}" />
													<label for="CheckEstadoDetraccion"> <b>Con detracción</b> </label>
												</div>
											</legend>
											<div class="col-md-8">
												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon formulario-venta">Tipo de Detracción</div>
														<select id="combo-tipo-detraccion" class="form-control formulario"
															data-bind="
															value : CodigoBienProductoDetraccionSUNAT,
															options : TiposDetraccion,
															optionsValue : 'IdTipoDetraccion' ,
															optionsText : 'FullDescripcionTipoDetraccion' ,
															attr : { disabled : !EstadoDetraccion() },
															event : { focus : OnFocus , change : (data,event) => OnChangeTipoDetraccion(data,event,$parent) , keydown : OnKeyEnter }"
															data-validation="required"
															data-validation-error-msg="No un tipo de detracción asignado">
														</select>
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon formulario-venta">Porcentaje
														</div>
														<input id="PorcentajeDetraccion" name="PorcentajeDetraccion"
															class="form-control formulario"
															data-bind="
															value: PorcentajeDetraccion,
															attr : { disabled : !EstadoDetraccion() }, 
															event : {focus : OnFocus,  keydown : OnKeyEnter}"
															data-validation="required"
															data-validation-error-msg="El porcentaje es Obligtorio" />
													</div>
												</div>
											</div>
										</fieldset>
									</div>
								</div>
								<!-- /ko -->

							</div>
						</fieldset>
					</div>
					<br>
					<div class="row">
						<!-- ko if:(TipoVenta() == TIPO_VENTA.MERCADERIAS) -->
						<div class="btn-group">
							<button type="button" class="btn btn-primary no-tab" href="#"
								data-bind="disable: IndicadorCrearProducto() == 0, click : function(data,event) {  return OnClickBtnNuevaMercaderia(data,event,$parent.Mercaderia);}">
								<span class="glyphicon glyphicon-plus"></span> Nueva MercaderÍa
							</button>
						</div>
						<div class="btn-group">
							<button type="button" class="btn btn-primary no-tab" href="#"
								data-bind="click : function(data,event) {  return OnClickBtnBuscadorMercaderia(data,event,$parent);}">
								<span class="glyphicon glyphicon-search"></span> Buscar Mercadería con Imagen
							</button>
						</div>
						<!-- ko if:(ParametroRubroRepuesto() == 1) -->
						<div class="btn-group">
							<button type="button" class="btn btn-primary no-tab" href="#"
								data-bind="click : function(data,event) {  return OnClickBtnBuscadorMercaderiaLista(data,event,$parent);}">
								<span class="glyphicon glyphicon-search"></span> Buscar Mercadería con Lista
							</button>
						</div>
						<!-- /ko -->
						<!-- ko if:(ParametroRubroRepuesto() == 0) -->
						<div class="btn-group">
							<button type="button" class="btn btn-primary no-tab" href="#"
								data-bind="click : function(data,event) {  return OnClickBtnBuscadorMercaderiaListaSimple(data,event,$parent);}">
								<span class="glyphicon glyphicon-search"></span> Buscar Mercadería con Lista
							</button>
						</div>
						<!-- /ko -->
						<div><br></div>
						<!-- /ko -->
						<fieldset>
							<div class="col-md-12">
								<div class="row detalle-comprobante">
									<div class="col-md-12">
										<table class="datalist__table table display grid-detail-body table-border"
											width="100%" id="tablaDetalleComprobanteVenta">
											<thead>
												<tr>
													<th class="">#</th>
													<th class="col-sm-2 products__id">
														<center>Código</center>
													</th>
													<th class="col-sm-4 products__title">Descripción</th>
													<!-- ko if: TipoVenta() == TIPO_VENTA.MERCADERIAS -->
													<th class="col-sm-1 products__title op-mercaderia"></th>
													<!-- /ko -->
													<!-- ko if:(ParametroObservacionDetalle() == "1") -->
													<th class="col-sm-2 products__title">Otros Datos</th>
													<!-- /ko -->
													<!-- ko if:(TipoVenta() == TIPO_VENTA.MERCADERIAS && ParametroMarcaVenta() == 1) -->
													<th class="products__title">
														<center>Marca</center>
													</th>
													<!-- /ko -->
													<!-- ko if:(TipoVenta() == TIPO_VENTA.MERCADERIAS) -->
													<th class="products__title">
														<center>Unid.</center>
													</th>
													<!-- /ko -->
													<!-- ko if:(ParametroStockProductoVenta() != 0 && TipoVenta() == TIPO_VENTA.MERCADERIAS) -->
													<th class="col-sm-1 products__title">
														<center>Stock</center>
													</th>
													<!-- /ko -->
													<!-- ko if: (ParametroDua() != 0 && (TipoVenta() == TIPO_VENTA.MERCADERIAS))-->
													<th class="col-sm-1 products__title">
														<center>Documento D.U.A.</center>
													</th>
													<!-- /ko -->
													<!-- ko if: (ParametroLote() != 0 && (TipoVenta() == TIPO_VENTA.MERCADERIAS)) -->
													<th class="col-sm-1 products__title">
														<center>Lote</center>
													</th>
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
													<!-- ko if: (ParametroDescuentoUnitario() != 0 && ParametroCalculoIGVDesdeTotal() == 0) -->
													<th class="col-sm-1 products__title">
														<center>Desc. Unit.</center>
													</th>
													<!-- /ko -->
													<!-- ko if: (ParametroDescuentoUnitario() != 0 && ParametroCalculoIGVDesdeTotal() == 1) -->
													<th class="col-sm-1 products__title">
														<center>Desc. V. Unit.</center>
													</th>
													<!-- /ko -->
													<!-- ko if: (ParametroDescuentoItem() != 0 && TipoVenta() == TIPO_VENTA.MERCADERIAS) -->
													<th class="products__title">
														<center>Desc. Item</center>
													</th>
													<!-- /ko -->
													<th class="col-sm-1 products__title">
														<center>Importe</center>
													</th>
													<th class="col-sm-1 products__title"></th>
												</tr>
											</thead>
											<tbody>
												<!-- ko foreach : DetallesComprobanteVenta -->
												<tr name="Fila" class="clickable-row" style="min-height: 80px;"
													data-bind="attr : { id: IdDetalleComprobanteVenta()+'_tr_detalle'}, click :  function(data,event) { return OnClickFila(data,event,$parent.OnRefrescar); } ">
													<td data-bind="text: NumeroItem" style="vertical-align: middle;">
													</td>
													<td class="col-sm-2">
														<div class="input-group">
															<input class="form-control formulario"
																data-bind="
																value: CodigoMercaderia, valueUpdate : 'keyup',
																disable:  ($parent.ParametroTransporte() == 1 && $parent.TipoVenta() == TIPO_VENTA.SERVICIOS && BLOQUEO_DETALLE_TRANSPORTE == 1) || ProductoBonificado ,
																attr : { id : IdDetalleComprobanteVenta() + '_input_CodigoMercaderia'},
																event : {input:function(data,event) { return OnChangeText(data, event, $parent); } , focus : function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnterCodigoMercaderia(data,event,$parent.OnKeyEnter); }  }"
																type="text" data-validation="validacion_producto"
																data-validation-error-msg="Cod. Inválido"
																data-validation-found="false"
																data-validation-text-found="">
														</div>
													</td>
													<td class="col-sm-4">
														<div class="input-group">
															<!-- ko if: $parent.ParametroRubroRepuesto() == 0 || $parent.TipoVenta() != TIPO_VENTA.MERCADERIAS-->
															<input class="form-control formulario" data-bind="
																value: NombreProducto,
																disable:  ($parent.ParametroTransporte() == 1 && $parent.TipoVenta() == TIPO_VENTA.SERVICIOS && BLOQUEO_DETALLE_TRANSPORTE == 1) || ProductoBonificado,
																attr : { id : IdDetalleComprobanteVenta() + '_input_NombreProducto', 'data-validation-reference' : IdDetalleComprobanteVenta() + '_input_CodigoMercaderia'  },
																event: {
																	focus: (data,event) => OnFocus(data,event,$parent.OnRefrescar),
																	change: OnChangeNombreProducto  }" type="text" data-validation="autocompletado_producto"
																data-validation-error-msg="No se han encontrado resultados para tu búsqueda de producto">
															<!-- /ko -->
															<!-- ko if: $parent.ParametroRubroRepuesto() == 1 && $parent.TipoVenta() == TIPO_VENTA.MERCADERIAS -->
															<input class="form-control formulario" data-bind="
																value: NombreLargoProducto,
																disable: ProductoBonificado,
																attr : { id : IdDetalleComprobanteVenta() + '_input_NombreProducto', 'data-validation-reference' : IdDetalleComprobanteVenta() + '_input_CodigoMercaderia'  },
																event: { focus : function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); }  }"
																type="text" data-validation="autocompletado_producto"
																data-validation-error-msg="No se han encontrado resultados para tu búsqueda de producto">
															<!-- /ko -->
														</div>
													</td>
													<!-- ko if: $parent.TipoVenta() == TIPO_VENTA.MERCADERIAS -->
													<td>
														<button type="button" style="background: none; border: none;"
															class="btn-default btn-buscar btn-control" name="button"
															data-bind="
																disable: $parent.IndicadorCrearProducto() == 0 || ProductoBonificado,
																click : function(data,event) {  return OnClickBtnNuevaMercaderia(data, event, $root.data.Mercaderia);},
																attr : { id : IdDetalleComprobanteVenta() + '_a_opcion_mercaderia'},
																visible: $parent.TipoVenta() == TIPO_VENTA.MERCADERIAS"> <i class="fas fa-plus"></i>
														</button>
													</td>
													<!-- /ko -->
													<!-- ko if:($parent.ParametroObservacionDetalle() == "1" && ($parent.TipoVenta() == TIPO_VENTA.SERVICIOS || $parent.TipoVenta() == TIPO_VENTA.MERCADERIAS) ) -->
													<td class="col-sm-2">
														<input name="Observacion"
															class="form-control  formulario numeric text-mumeric inputs"
															data-bind="value : Observacion ,
                             									disable: ,
																attr : { id : IdDetalleComprobanteVenta() + '_input_Observacion'},
																event: { focus: function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout : ValidarObservacion}"
															type="text" data-validation=""
															data-validation-error-msg="requerido">
													</td>
													<!-- /ko -->
													<!-- ko if:($parent.TipoVenta() == TIPO_VENTA.MERCADERIAS && $parent.ParametroMarcaVenta() == 1) -->
													<td class="text-center">
														<span class=""
															data-bind="text : NombreMarca, attr : { id : IdDetalleComprobanteVenta() + '_span_Marca'} "></span>
													</td>
													<!-- /ko -->
													<!-- ko if:($parent.TipoVenta() == TIPO_VENTA.MERCADERIAS) -->
													<td class="text-center">
														<span class=""
															data-bind="text : AbreviaturaUnidadMedida, attr : { id : IdDetalleComprobanteVenta() + '_span_AbreviaturaUnidadMedida'} "></span>
													</td>
													<!-- /ko -->
													<!-- ko if:($parent.ParametroStockProductoVenta() != 0 && $parent.TipoVenta() == TIPO_VENTA.MERCADERIAS) -->
													<td class="text-center">
														<span class=""
															data-bind="text : StockProducto, attr : { id : IdDetalleComprobanteVenta() + '_span_StockProducto'}, css: ColorText , numbertrim :StockProducto "></span>
													</td>
													<!-- /ko -->
													<!-- ko if: ($parent.ParametroDua() != 0 && ($parent.TipoVenta() == TIPO_VENTA.MERCADERIAS)) -->
													<td class="col-sm-1">
														<input name="NumeroDua" class="form-control formulario"
															data-bind="
															value : NumeroDua, 
															disable: ProductoBonificado,
															attr : { id : IdDetalleComprobanteVenta() + '_input_NumeroDua'}, enable:OnEnableDua(),
                            									event: {focus : function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); }, focusout : ValidarNumeroDua } , ko_autocomplete_dua: { dua: DataListaDua($parent.IdAsignacionSede()),id: IdDuaProducto}"
															type="text" data-validation="required_dua"
															data-validation-error-msg="Requerido"
															data-validation-found="false">
													</td>
													<!-- /ko -->
													<!-- ko if: ($parent.ParametroLote() != 0 && ($parent.TipoVenta() == TIPO_VENTA.MERCADERIAS)) -->
													<td class="col-sm-1">
														<input name="NumeroLote" class="form-control formulario"
															data-bind="
															value : NumeroLote, 
															disable: ,
															attr : { id : IdDetalleComprobanteVenta() + '_input_NumeroLote'},
                           									event: {focus : function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); }, focusout : ValidarNumeroLote } , ko_autocomplete_lote: { lote: DataLotes($parent.IdAsignacionSede()),id: IdLoteProducto}"
															type="text" data-validation="required_lote"
															data-validation-error-msg="Requerido"
															data-validation-found="false">
													</td>
													<!-- /ko -->
													<td class="col-sm-1">
														<input name="Cantidad"
															class="form-control formulario numeric text-mumeric"
															data-bind="
																value : Cantidad,
																disable: OnDisableCantidad,
																attr : { id : IdDetalleComprobanteVenta() + '_input_Cantidad', 'data-cantidad-decimal' : DecimalCantidad() },
																event: {
																focus : (data,event) => OnFocus(data,event,$parent.OnRefrescar),
																keydown : (data,event) => OnKeyEnter(data,event,$parent.OnKeyEnter),
																focusout : ValidarCantidad,
																change: (data, event) => OnChangeCantidad(data, event, $parent) },
																numberdecimal : Cantidad,
																ko_autocomplete_cantidad: { source: DataPrecios(), raleo: DataRaleo(), precio: PrecioUnitario}"
															type="text" data-validation="number_calc"
															data-validation-allowing="float,positive,range[0.001;9999999]"
															data-validation-decimal-separator="."
															data-validation-error-msg="De 0 a más">
													</td>
													<!-- ko if: ($parent.ParametroCalculoIGVDesdeTotal() == 0) -->
													<td class="col-sm-1">
														<input name="PrecioUnitario" list="items"
															class="form-control  formulario numeric text-mumeric inputs"
															data-bind="
																value : PrecioUnitario,																
																disable: OnDisablePrecioUnitario,
																attr : { id : IdDetalleComprobanteVenta() + '_input_PrecioUnitario', 'data-cantidad-decimal' : DecimalPrecioUnitario()},
																event: {
																focus: (data,event) => OnFocusPrecioUnitario(data,event,$parent.OnRefrescar),
																keydown : (data,event) => OnKeyEnter(data,event,$parent.OnKeyEnter),
																focusout : ValidarPrecioUnitario,
																change: (data, event) => OnChangePrecioUnitario(data, event, $parent), },
																numberdecimal : PrecioUnitario,
																ko_autocomplete: { source: DataPrecios(), raleo: DataRaleo(), cantidad: Cantidad}" type="text"
															data-validation="number_calc"
															data-validation-allowing="float,positive,range[0.001;9999999]"
															data-validation-decimal-separator="."
															data-validation-error-msg="Solo positivo">
													</td>
													<!-- /ko -->
													<!-- ko if: ($parent.ParametroCalculoIGVDesdeTotal() == 1) -->
													<td class="col-sm-1">
														<input name="ValorUnitario" list="items"
															class="form-control  formulario numeric text-mumeric inputs"
															data-bind="
															value : ValorUnitario, 
															disable: ($parent.ParametroCalcularCantidad() == 1 && $parent.IndicadorEditarCampoPrecioUnitarioVenta() == 0) || ProductoBonificado,
															attr : { id : IdDetalleComprobanteVenta() + '_input_ValorUnitario', 'data-cantidad-decimal' : DecimalPrecioUnitario()},
															event: { focus: function(data,event) { return OnFocusValorUnitario(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout : ValidarPrecioUnitario, blur: function (data,event){return CalculoSubTotal(data,event,$parent.OnRefrescar);} } , numberdecimal : ValorUnitario"
															type="text" data-validation="number_calc"
															data-validation-allowing="float,positive,range[0.001;9999999]"
															data-validation-decimal-separator="."
															data-validation-error-msg="Solo positivo">
													</td>
													<!-- /ko -->
													<!-- ko if: ($parent.ParametroCalculoIGVDesdeTotal() == 0 && $parent.ParametroDescuentoUnitario() != 0) -->
													<td class="col-sm-1">
														<input name="DescuentoUnitario"
															class="form-control formulario numeric text-mumeric inputs"
															data-cantidad-decimal="2"
															data-bind="value : DescuentoUnitario ,
															disable: ProductoBonificado,
															attr : { id : IdDetalleComprobanteVenta() + '_input_DescuentoUnitario', 'data-cantidad-decimal' : DecimalDescuentoUnitario() },
															event: { focus: function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout : ValidarDescuentoUnitario, blur: function (data,event){return CalculoSubTotal(data,event,$parent.OnRefrescar);} } , numberdecimal : DescuentoUnitario"
															type="text" data-validation="number_desc"
															data-validation-allowing="float,positive,range[0;9999999]"
															data-validation-decimal-separator="."
															data-validation-error-msg="De 0 a más">
													</td>
													<!-- /ko -->
													<!-- ko if: ($parent.ParametroCalculoIGVDesdeTotal() == 1 && $parent.ParametroDescuentoUnitario() != 0) -->
													<td class="col-sm-1">
														<input name="DescuentoValorUnitario"
															class="form-control formulario numeric text-mumeric inputs"
															data-cantidad-decimal="2"
															data-bind="value : DescuentoValorUnitario ,
																disable: ProductoBonificado,
															attr : { id : IdDetalleComprobanteVenta() + '_input_DescuentoValorUnitario', 'data-cantidad-decimal' : DecimalDescuentoValorUnitario() },
															event: { focus: function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); } , focusout : ValidarDescuentoValorUnitario, blur: function (data,event){return CalculoSubTotal(data,event,$parent.OnRefrescar);} } , numberdecimal : DescuentoValorUnitario"
															type="text" data-validation="number_desc"
															data-validation-allowing="float,positive,range[0;9999999]"
															data-validation-decimal-separator="."
															data-validation-error-msg="De 0 a más">
													</td>
													<!-- /ko -->
													<!-- ko if:($parent.ParametroDescuentoItem() != 0 && $parent.TipoVenta() == TIPO_VENTA.MERCADERIAS) -->
													<td class="text-center">
														<span class=""
															data-bind="text : CalcularDescuentoItem(), attr : { id : IdDetalleComprobanteVenta() + '_span_DescuentoItem'}"></span>
													</td>
													<!-- /ko -->
													<!-- ko if: ($parent.ParametroCalculoIGVDesdeTotal() == 0) -->
													<td class="col-sm-1">
														<div class="input-group">
															<input class="form-control  formulario text-right"
																data-bind="
																	value : SubTotal,
																	disable: OnDisableSubTotal,
																	attr : { id : IdDetalleComprobanteVenta() + '_span_SubTotal'},
																	event : {
																		focus: (data,event) => OnFocusSubtotal(data,event,$parent.OnRefrescar),
																		change: (data, event) => OnChangeSubTotal(data, event, $parent),
																		keydown : (data,event) => OnKeyEnter(data,event,$parent.OnKeyEnter),
																		focusout: ValidarSubTotal},
																	numbertrim: SubTotal" type="text" data-validation="number_calc"
																data-validation-allowing="float,positive,range[0.001;9999999]"
																data-validation-decimal-separator="."
																data-validation-error-msg="De 0 a más">
														</div>
													</td>
													<!-- /ko -->
													<!-- ko if: ($parent.ParametroCalculoIGVDesdeTotal() == 1) -->
													<td class="col-sm-1">
														<div class="input-group">
															<input class="form-control  formulario text-right"
																data-bind="value : ValorVentaItem,
																disable: ProductoBonificado,
																attr : { id : IdDetalleComprobanteVenta() + '_span_ValorVentaItem'},
																event : { focus: function(data,event) { return OnFocusValorVentaItem(data,event,$parent.OnRefrescar); }, keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); }, focusout: ValidarValorVentaItem, change: function (data,event){return CalculoValorUnitario(data,event,$parent.OnRefrescar);} }, numbertrim: ValorVentaItem"
																type="text" data-validation="number_calc"
																data-validation-allowing="float,positive,range[0.001;9999999]"
																data-validation-decimal-separator="."
																data-validation-error-msg="De 0 a más">
														</div>
													</td>
													<!-- /ko -->

													<td class="col-sm-auto">
														<div class="input-group ajuste-opcion-plusminus">
															<button type="button"
																class="btn btn-default focus-control glyphicon glyphicon-minus no-tab"
																data-bind="
															click : function(data,event) {  return OnClickBtnOpcion(data,event,$parent.OnQuitarFila);  },
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
						<div class="col-md-7 denominacion-moneda-nacional">
							<span data-bind="html : DenominacionTotal()" id="nletras"></span>
						</div>
						<div class="col-md-2 text-right">
							<!-- ko if: (TipoVenta() == TIPO_VENTA.MERCADERIAS) -->
							<span data-bind="html : 'CANT. TOTAL : '+TotalCantidades()"></span>
							<!-- /ko -->
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
								<input id="ValorVentaGravado" tabindex="-1" readonly
									class="form-control formulario numeric text-mumeric no-tab input-totales"
									type="text" placeholder="Op. Grav." data-bind="value: CalculoTotalVentaGravado()">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<div class="addon-top">Op. Exonerado</div>
								<input id="ValorVentaNoGravado" tabindex="-1" readonly
									class="form-control formulario numeric text-mumeric no-tab input-totales"
									type="text" placeholder="Op. No Grav."
									data-bind="value: CalculoTotalVentaNoGravado()">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<div class="addon-top">Op. Inafecto</div>
								<input id="ValorVentaInafecto" tabindex="-1" readonly
									class="form-control formulario numeric text-mumeric no-tab input-totales"
									type="text" placeholder="Op. Ina." data-bind="value: CalculoTotalVentaInafecto()">
							</div>
						</div>
						<!-- ko if:(IdTipoVenta() != TIPO_VENTA.SERVICIOS) -->
						<!-- ko if: (ParametroDescuentoUnitario() != 0 && ParametroCalculoIGVDesdeTotal() == 0) -->
						<div class="col-md-2">
							<div class="form-group">
								<div class="addon-top">Descto. Item</div>
								<input id="DescuentoGlobal"
									class="form-control formulario numeric text-mumeric input-totales" readonly type="text"
									placeholder="Des. Item" data-bind="
										value: DescuentoTotalItem,
										numbertrim: DescuentoTotalItem,
										event: { focus: OnFocus , focusout: ValidarDescuentoGlobal , keydown: OnKeyEnter }">
							</div>
						</div>
						<!-- /ko -->
						<!-- ko if: (ParametroDescuentoUnitario() != 0 && ParametroCalculoIGVDesdeTotal() == 1) -->
						<div class="col-md-2">
							<div class="form-group">
								<div class="addon-top">Descto. V. Item</div>
								<input id="DescuentoGlobal"
									class="form-control formulario numeric text-mumeric input-totales" readonly type="text"
									placeholder="Des. Item" data-bind="
										value: DescuentoTotalItem,
										numbertrim: DescuentoTotalValorItem,
										event: { focus: OnFocus , focusout: ValidarDescuentoGlobal , keydown: OnKeyEnter }">
							</div>
						</div>
						<!-- /ko -->
						<!-- /ko -->
						<div class="col-md-1">
							<div class="form-group">
								<div class="addon-top">ICBPER</div>
								<input id="ICBPER" readonly tabindex="-1"
									class="form-control formulario numeric text-mumeric no-tab input-totales"
									type="text" placeholder="ICBPER" data-bind="value: ICBPER">
							</div>
						</div>
						<div class="col-md-1">
							<div class="form-group">
								<div class="addon-top">IGV</div>
								<input id="IGV" readonly tabindex="-1"
									class="form-control formulario numeric text-mumeric no-tab input-totales"
									type="text" placeholder="IGV" data-bind="value: CalculoTotalIGV()">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<div class="addon-top">Total</div>
								<input id="Total" readonly tabindex="-1"
									class="form-control formulario numeric text-mumeric no-tab input-totales"
									type="text" placeholder="Total" data-bind="value : CalculoTotalVenta()">
							</div>
						</div>
						
						<!-- ko if:(EstadoDetraccion() == 1) -->
						<div class="col-md-2">
							<div class="form-group">
								<div class="addon-top">Detracción (<span data-bind="text: PorcentajeDetraccion"></span>%) </div>
								<input id="Total" readonly tabindex="-1"
									class="form-control formulario numeric text-mumeric no-tab input-totales"
									type="text" placeholder="Detracción" data-bind="value : MontoDetraccion()">
							</div>
						</div>
						<!-- /ko -->

						<!-- ko if:(ParametroCampoACuenta() == 1) -->
						<div class="col-md-2">
							<div class="form-group">
								<div class="addon-top">A Cuenta</div>
								<input id="ACuenta" class="form-control formulario numeric text-mumeric input-totales"
									type="text" placeholder="0.00"
									data-bind="value : MontoACuenta, numbertrim : MontoACuenta, event:{ focus: OnFocus, keydown : OnKeyEnter}">
							</div>
						</div>
						<!-- /ko -->
						<!-- ko if:(ParametroCampoCampoMontoPendienteVenta() == 1) -->
						<div class="col-md-2" data-bind="visible : IdFormaPago() == ID_FORMA_PAGO_CREDITO">
							<div class="form-group">
								<div class="addon-top">Monto Pendiente</div>
								<input id="ACuenta" class="form-control formulario numeric text-mumeric input-totales"
									type="text" placeholder="0.00"
									data-bind="value : MontoPendientePago, numbertrim :MontoPendientePago, event:{ focus: OnFocus, keydown : OnKeyEnter}">
							</div>
						</div>
						<!-- /ko -->
						<!-- ko if:(ParametroRubroClinica() == 1) -->
						<div class="col-md-2">
							<div class="form-group">
								<div class="addon-top">Porcentaje Comisión(%)</div>
								<input id="PorcentajeComision"
									class="form-control formulario numeric text-mumeric input-totales" type="text"
									placeholder="0.00" data-bind="
                value: PorcentajeComision,
                numbertrim: PorcentajeComision,
                event:{ focus: OnFocus, keydown : OnKeyEnter, change : OnPorcentajeComision}"
									data-validation="number_desc" data-validation-allowing="float,range[0.0000;100]"
									data-validation-decimal-separator="." data-validation-error-msg="Mayor o igual a 0">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<div class="addon-top">Monto Comisión</div>
								<input id="MontoComision"
									class="form-control formulario numeric text-mumeric input-totales" type="text"
									placeholder="0.00" data-bind="
                value: MontoComision,
                numbertrim: MontoComision,
                event:{ focus: OnFocus, keydown : OnKeyEnter}" data-validation="number_desc"
									data-validation-allowing="float,range[0.000;9999999]"
									data-validation-decimal-separator="." data-validation-error-msg="Mayor o igual a 0">
							</div>
						</div>
						<!-- /ko -->
					</div>
					<div class="row">
						<div class="col-md-3">
							<br>
							<strong class="alert-info">* Grabar = ALT + G</strong>
							<strong class="separador"> | </strong>
							<strong class="alert-info">* Nuevo = ALT + N</strong>
						</div>
						<div class="col-md-6">
							<center>
								<br>
								<button type="button" id="btn_Grabar" class="btn btn-success focus-control"
									data-bind="click : Guardar">Grabar</button> &nbsp;
								<button type="button" id="btn_Limpiar" class="btn btn-default focus-control"
									data-bind="click : Limpiar, visible : opcionProceso() == 1">Limpiar</button> &nbsp;
								<button type="button" id="BtnDeshacer" class="btn btn-default"
									data-bind="click : Deshacer, visible : opcionProceso() == 2">Deshacer</button>
								&nbsp;
								<button type="button" id="btn_Cerrar" class="btn btn-default"
									data-bind="click : OnClickBtnCerrar , visible :  opcionProceso() == 2">Cerrar</button>
								<p>
							</center>
						</div>
						<div class="col-md-3">
							&nbsp;
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<!-- /ko -->
