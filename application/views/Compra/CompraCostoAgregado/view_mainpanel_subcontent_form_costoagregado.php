<!-- ko with : CompraCostoAgregado -->
<form id="formCompraCostoAgregado" name="formCompraCostoAgregado" role="form" autocomplete="off">
	<div class="datalist__result">
		<input id="IdTipoDocumento" class="form-control" type="hidden" placeholder="Documento"
			data-bind="value: IdTipoDocumento">
		<input id="IdTipoCompra" class="form-control" type="hidden" placeholder="Documento"
			data-bind="value: IdTipoCompra">
		<!-- <input id="IdTipoOperacion" class="form-control" type="hidden" placeholder="TipoOperacion" data-bind="value: IdTipoOperacion">-->
		<input id="IdCompraCostoAgregado" class="form-control" type="hidden" placeholder="IdCompraCostoAgregado">
		<input id="IdProveedor" class="form-control" type="hidden" placeholder="RUC/DNI:"
			data-bind="value: IdProveedor">

		<div class="tab-pane active" id="brand" role="tabpanel">
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
											<input id="FechaEmision" name="FechaEmision" class="form-control formulario"
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
												data-validation="fecha_vencimiento" data-validation-format="dd/mm/yyyy"
												data-validation-error-msg="La fecha de vencimiento es incorrecta.Solo es obligatoria cuando es forma de pago credito" />
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<div class="input-group">
											<div class="input-group-addon formulario-venta">Tipo Documento</div>
											<select id="combo-tipodocumento" class="form-control formulario" data-bind="
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
										<div class="input-group">
											<div class="input-group-addon formulario-venta">Serie</div>
											<input id="SerieDocumento" type="text" name=""
												class="form-control formulario" value=""
												data-bind="value: SerieDocumento, event : {  focus : OnFocus , focusout : ValidarSerieDocumento , keydown : OnKeyEnter }"
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
												data-bind="value: NumeroDocumento , event : {  focus : OnFocus , focusout : ValidarNumeroDocumento , keydown : OnKeyEnter }"
												data-validation="number" data-validation-allowing="range[1;99999999]"
												data-validation-error-msg="El número documento debe ser numérico y tener como máximo 8 digitos">
										</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<div class="input-group">
											<div class="input-group-addon formulario-venta">Observacion:</div>
											<input id="Observacion" class="form-control formulario" type="text"
												data-bind="value: Observacion, event : { focus : OnFocus ,keydown : OnKeyEnter }">
										</div>
									</div>
								</div>
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
														data-bind="checked : PagadorDetraccion, event : {focus : OnFocus, keydown : OnKeyEnter}">
													<label for="radioCliente" style="font-size : 13px">Cliente</label>
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
													<div class="input-group-addon formulario-venta">Nro Documento</div>
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
														data-bind="value : MontoDetraccion, event : {  focus : OnFocus , keydown : OnKeyEnter },,numbertrim : MontoDetraccion">
												</div>
											</div>
										</div>
									</fieldset>
								</div>
							</div>
							<div class="row">
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
							</div>
						</div>
					</fieldset>
				</div>
				<br>
				<div class="row">

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
					<fieldset>
						<table class="datalist__table table display grid-detail-body table-border" width="100%"
							id="tablaDetalleCompraCostoAgregado">
							<thead>
								<tr>
									<th class="col-sm-6 products__title">Descripción</th>
									<th class="col-sm-2 products__title">Cantidad</th>
									<!-- ko if: IndicadorTipoCalculoIGV() == 1 -->
									<th class="col-sm-2 products__title">Precio Unitario</th>
									<!-- /ko -->
									<!-- ko if: IndicadorTipoCalculoIGV() == 0 -->
									<th class="col-sm-2 products__title">Valor Venta</th>
									<!-- /ko -->
									<th class="col-sm-1 products__title">Afecto IGV</th>
									<th class="col-sm-2 products__title">Importe</th>
									<th class="col-sm-1 products__title"></th>
								</tr>
							</thead>
							<tbody>
								<!-- ko foreach : DetallesCompraCostoAgregado -->
								<tr name="Fila" class="clickable-row" style="min-height: 80px;"
									data-bind="attr : { id: IdDetalleComprobanteCompra }, click :  function(data,event) { return OnClickFila(data,event,$parent.OnRefrescar); } ">
									<td class="col-sm-6">
										<input class="form-control formulario" data-bind="value: IdProducto, valueUpdate : 'keyup',
                        attr : { id : IdDetalleComprobanteCompra() + '_input_IdProducto'}," type="hidden"
											data-validation="validacion_producto"
											data-validation-error-msg="Cod. Inválido" data-validation-found="false"
											data-validation-text-found="">
										<div class="input-group">
											<input class="form-control formulario" data-bind="value: NombreProducto,
                          attr : { id : IdDetalleComprobanteCompra() + '_input_NombreProducto',
                          'data-validation-reference' : IdDetalleComprobanteCompra() + '_input_IdProducto','data-validation-value' : NombreProducto() },
                          event: { focus : function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); }  }"
												type="text" data-validation="autocompletado_producto"
												data-validation-value=""
												data-validation-error-msg="No se han encontrado resultados para tu búsqueda de producto">
										</div>
									</td>
									<td class="col-sm-2">
										<input name="Cantidad" class="form-control formulario numeric text-mumeric"
											data-bind="
                    value: Cantidad,
                    attr: { id : IdDetalleComprobanteCompra() + '_input_Cantidad', 'data-cantidad-decimal' : DecimalCantidad() },
                    event: {
                      focus: (data,event) => OnFocus(data,event,$parent.OnRefrescar), 
                      keydown: (data,event) => OnKeyEnter(data,event,$parent.OnKeyEnterTotales), 
                      focusout: ValidarCantidad,
                      blur: (data,event) => CalculoSubTotal(data,event,$parent.OnRefrescar) },
                    numberdecimal: Cantidad " type="text" data-validation="number_calc"
											data-validation-allowing="float,positive,range[0.001;9999999]"
											data-validation-decimal-separator="."
											data-validation-error-msg="De 0 a más">
									</td>
									<!-- ko if: $parent.IndicadorTipoCalculoIGV() == 1 -->
									<td class="col-sm-2">
										<input name="PrecioUnitario"
											class="form-control  formulario numeric text-mumeric inputs" data-bind="
                        value : PrecioUnitario ,
                        attr : { id : IdDetalleComprobanteCompra() + '_input_PrecioUnitario', 'data-cantidad-decimal' : DecimalPrecioUnitario()},
                        event: {  
                          focus: function(data,event) { return OnFocusPrecioUnitario(data,event,$parent.OnRefrescar); } , 
                          keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnterTotales); } , 
                          focusout : ValidarPrecioUnitario, 
                          blur: function (data,event){return CalculoSubTotal(data,event,$parent.OnRefrescar);} },
                        numberdecimal : PrecioUnitario,
                        precio: PrecioUnitario" type="text" data-validation="number_calc"
											data-validation-allowing="float,positive,range[0.001;9999999]"
											data-validation-decimal-separator="."
											data-validation-error-msg="Solo positivo">
									</td>
									<!-- /ko -->
									<!-- ko if: $parent.IndicadorTipoCalculoIGV() == 0 -->
									<td class="col-sm-2">
										<input name="CostoUnitario"
											class="form-control  formulario numeric text-mumeric inputs" data-bind="
                        value : CostoUnitario ,
                        attr : { id : IdDetalleComprobanteCompra() + '_input_CostoUnitario', 'data-cantidad-decimal' : DecimalCostoUnitario() },
                        event: { 
                          focus: function(data,event) { return OnFocusPrecioUnitario(data,event,$parent.OnRefrescar); } , 
                          keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnterTotales); } , 
                          focusout : ValidarCostoUnitario,
                          blur: function(data,event) { return CalculoSubTotal(data,event,$parent.OnRefrescar);} }, 
                        numberdecimal : CostoUnitario,
                        precio : CostoUnitario" type="text" data-validation="number_calc"
											data-validation-allowing="float,positive,range[1;9999999]"
											data-validation-decimal-separator="."
											data-validation-error-msg="Solo positivo">
									</td>
									<!-- /ko -->
									<td class="col-sm-1">
										<div class="form-group">
											<select name="AfectoIGV" id="AfectoIGV" class="form-control formulario"
												data-bind="
                          value : AfectoIGV,
                          attr : { id : IdDetalleComprobanteCompra() + '_input_AfectoIGV' },
                          event: { 
                            change: (data,event) => CalculoSubTotal(data,event,$parent.OnRefrescar),
                            blur: (data,event) => CalculoSubTotal(data,event,$parent.OnRefrescar),
                            focus: (data,event) => OnFocus(data,event,$parent.OnRefrescar), 
                            keydown: (data,event) => OnKeyEnter(data,event,$parent.OnKeyEnterTotales),
                            focusout : ValidarAfectoIGV }">
												<option value="1">SI</option>
												<option value="0">NO</option>
											</select>
										</div>
									</td>
									<!-- ko if: $parent.IndicadorTipoCalculoIGV() == 1 -->
									<td class="col-sm-2">
										<div class="input-group">
											<input class="form-control  formulario text-right" data-bind="
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
									<td class="col-sm-2">
										<div class="input-group">
											<input class="form-control  formulario text-right" data-bind="
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
									<td class="col-sm-auto">
										<div class="input-group ajuste-opcion-plusminus">
											<button type="button"
												class="btn btn-default focus-control glyphicon glyphicon-minus no-tab"
												data-bind="click : function(data,event) {  return OnClickBtnOpcion(data,event,$parent.OnQuitarFila);  },
                          event : { focus: function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); }   },
                          attr : { id : IdDetalleComprobanteCompra() + '_a_opcion'}"></button>
										</div>
									</td>
								</tr>
								<!-- /ko -->
							</tbody>
						</table>
					</fieldset>
				</div>

				<div class="row">
					<fieldset>
						<legend>Documento Referencia(Compra del Producto)</legend>
						<!-- ko with : $parent.FiltrosCostoAgregado -->
						<div class="col-md-4">
							<div class="form-group">
								<div class="input-group">
									<div class="input-group-addon">Proveedor</div>
									<input id="ProveedorDocumentoCompra"
										class="form-control formulario ProveedorDocumentoCompra" type="text"
										data-bind="">
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<div class="input-group">
									<div class="input-group-addon">Tipo Doc.</div>
									<select id="combo-tipodocumentoreferencia" class="form-control formulario"
										data-bind="
                      value : IdTipoDocumento,
                      options : $parent.TiposDocumento,
                      optionsValue : 'IdTipoDocumento' ,
                      optionsText : 'NombreAbreviado' ,
                      event : { change : OnChangeTipoDocumento , focus : $parent.OnFocus , keydown : $parent.OnKeyEnter }">
									</select>
								</div>
							</div>
						</div>
						<div class="col-md-1">
							<button type="button" id="btn_Buscar" class="btn btn-primary focus-control btn-control"
								data-bind="event:{click: Buscar(data, event, $parent.RetornoDocumentoReferencia)}">Buscar</button>
						</div>
						<!-- /ko -->
						<div class="col-md-3">
							<div class="form-group">
								<div class="input-group">
									<div class="input-group-addon">Forma Prorrateo</div>
									<select id="combo-formaprorrateo" class="form-control formulario" data-bind="
                      value : IdMetodoProrrateo,
                      options : MetodosProrrateo,
                      optionsValue : 'IdMetodoProrrateo' ,
                      optionsText : 'NombreMetodoProrrateo' ,
                      event : { focus : $parent.OnFocus , keydown : $parent.OnKeyEnter}">
									</select>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<button type="button" id="btn_Prorrateo" class="btn btn-primary focus-control btn-control"
								data-bind="event:{click: CalcularProrrateo}">Calcular Prorrateo</button>
						</div>
						<div class="row">
							<div class="col-md-12">
								<table class="datalist__table table display table-border" width="100%"
									id="tablaDetalleCompraDocumentoReferencia">
									<thead>
										<tr>
											<th class="col-sm-3 products__title">Proveedor</th>
											<th class="col-sm-2 products__title">
												<c>Documento</c>
											</th>
											<th class="col-sm-6 products__title">
												<c>Producto</c>
											</th>
											<th class="col-sm-1 products__title">
												<c>Unidad</c>
											</th>
											<th class="col-sm-2 products__title">
												<c>Cantidad</c>
											</th>
											<th class="col-sm-1 products__title">
												<c>Costo Unitario</c>
											</th>
											<th class="col-sm-1 products__title">
												<c>Distribución</c>
											</th>
											<th class="col-sm-1 products__title">
												<c>%Prorrateo</c>
											</th>
											<th class="col-sm-1 products__title">
												<c>Prorrateo</c>
											</th>
											<th class="col-sm-1 products__title">
												<c>Costo Agreg. Unit.</c>
											</th>
											<th class="col-sm-1 products__title"></th>
										</tr>
									</thead>
									<tbody>
										<!-- ko foreach : DetallesDocumentoReferencia -->
										<tr name="Fila" class="clickable-row" style="min-height: 80px;"
											data-bind="attr : { id: IdDetalleComprobanteCompra }">
											<td class="col-sm-3"><span data-bind="text: NombreProveedor"></span></td>
											<td class="col-sm-2"><span data-bind="text: Documento"></span></td>
											<td class="col-sm-6"><span data-bind="text: NombreProducto"></span></td>
											<td class="col-sm-1"><span data-bind="text: AbreviaturaUnidadMedida"></span>
											</td>
											<td class="col-sm-2">
												<input class="form-control  formulario numeric text-mumeric inputs"
													data-bind="
                          value: Cantidad,
                          event: { keydown : $parent.OnKeyEnter, blur: ChangeCantidad},
                          attr : { 'data-cantidad-decimal' : DecimalCantidad() },
                          numberdecimal: Cantidad" type="text" data-validation="number_calc"
													data-validation-allowing="float,positive,range[1;9999999]"
													data-validation-decimal-separator="."
													data-validation-error-msg="Solo positivo">
											</td>
											<td class="col-sm-1" align="right"><span
													data-bind="text: CostoUnitarioCalculado"></span></td>
											<td class="col-sm-1" align="right"><span
													data-bind="text: BaseDistribucion"></span></td>
											<!-- <td data-bind="text: PorcentajeDistribucion"></td> -->
											<td class="col-sm-1" align="right"><span
													data-bind="text: Porcentaje"></span></td>
											<td class="col-sm-1" align="right"><span
													data-bind="text: MontoProrrateadoTotal"></span></td>
											<td class="col-sm-1" align="right"><span
													data-bind="text: MontoProrrateadoPorUnidad"></span></td>
											<td class="col-sm-auto">
												<div class="input-group ajuste-opcion-plusminus">
													<button type="button"
														class="btn btn-default focus-control glyphicon glyphicon-minus no-tab"
														data-bind="event: {click: BorrarFila}"></button>
												</div>
											</td>
										</tr>
										<!-- /ko -->
									</tbody>
								</table>
							</div>
						</div>
					</fieldset>
				</div>
				<div class="row">
					<div class="col-md-2">
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<div class="addon-top">Total Distribución</div>
							<input id="Total" readonly tabindex="-1"
								class="form-control formulario numeric input-totales text-mumeric no-tab" type="text"
								placeholder="Total" data-bind="value : CalculoTotalDistribucion()">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<div class="addon-top">Op. Gravada</div>
							<input id="ValorCompraGravado" tabindex="-1" readonly
								class="form-control formulario numeric input-totales text-mumeric no-tab" type="text"
								placeholder="Op. Grav." data-bind="value: CalculoTotalCompraGravado()">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<div class="addon-top">Op. No Gravada</div>
							<input id="ValorCompraNoGravado" tabindex="-1" readonly
								class="form-control formulario numeric input-totales text-mumeric no-tab" type="text"
								placeholder="Op. No Grav." data-bind="value: CalculoTotalCompraNoGravado()">
						</div>
					</div>
					<!-- <div class="col-md-2">
            <div class="form-group">
              <div class="addon-top">Op. Inafecto</div>
              <input id="ValorCompraNoGravado" tabindex="-1" readonly class="form-control formulario numeric text-mumeric input-totales no-tab" type="text" placeholder="Op. Inafecto." data-bind="value: CalculoTotalVentaInafecto()">
            </div>
          </div> -->
					<div class="col-md-2">
						<div class="form-group">
							<div class="addon-top">IGV</div>
							<input id="IGV" readonly tabindex="-1"
								class="form-control formulario numeric input-totales text-mumeric no-tab" type="text"
								placeholder="IGV" data-bind="value: CalculoTotalIGV()">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<div class="addon-top">Total</div>
							<input id="Total" readonly tabindex="-1"
								class="form-control formulario numeric input-totales text-mumeric no-tab" type="text"
								placeholder="Total" data-bind="value : CalculoTotalCompra()">
						</div>
					</div>
				</div>
				<div class="row">
					<center>
						<br>
						<button type="button" id="btn_Grabar" class="btn btn-success focus-control"
							data-bind="click : Guardar">Grabar</button> &nbsp;
						<button type="button" id="btn_Limpiar" class="btn btn-default focus-control"
							data-bind="click : Limpiar, visible : opcionProceso() == 1">Limpiar</button> &nbsp;
						<button type="button" id="BtnDeshacer" class="btn btn-default"
							data-bind="click : Deshacer, visible : opcionProceso() == 2">Deshacer</button> &nbsp;
						<button type="button" id="btn_Cerrar" class="btn btn-default"
							data-bind="click : OnClickBtnCerrar , visible :  opcionProceso() == 2">Cerrar</button>
						<br>
						<p>
					</center>
				</div>
			</div>
		</div>
	</div>
</form>
<!-- /ko -->
