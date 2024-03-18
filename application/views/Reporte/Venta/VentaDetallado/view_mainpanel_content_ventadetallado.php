<!-- ko with : vmgReporteVentaDetallado.dataReporteVentaDetallado -->
<div class="main__cont">
	<div class="container-fluid half-padding">
		<div class="datalist page page_products products">
			<div class="row">
				<div class="col-md-2 col-xs-12">

				</div>
				<div class="col-md-8 col-xs-12">
					<!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
					<div class="panel panel-info">
						<div class="panel-heading">
							<h3 class="panel-title">Reporte de Ventas Detallado</h3>
						</div>
						<div class="panel-body">
							<div class="datalist__result">
								<!-- ko with : Buscador-->
								<form class="form products-new" enctype="multipart/form-data" id="form" name="form" action=""
									method="post">
									<div class="container-fluid">
										<div class="row">
											<div class="col-md-12">
												<div class="row">
													<div class="col-md-12">
														<fieldset>
															<legend>Rango de fecha</legend>
															<div class="col-md-12">
																<div class="row">
																	<div class="col-md-6">
																		<div class="form-group">
																			<div class="input-group">
																				<div class="input-group-addon">Del</div>
																				<input id="PrimerDia_D" name="FechaInicio_D"
																					class="form-control formulario fecha-reporte" type="text"
																					data-inputmask-clearmaskonlostfocus="false" data-validation="date"
																					data-validation-format="dd/mm/yyyy"
																					data-validation-error-msg="La fecha es invalida"
																					data-bind="value:FechaInicio_D, event:{focusout : ValidarFecha}">
																			</div>
																		</div>
																	</div>
																	<div class="col-md-6">
																		<div class="form-group">
																			<div class="input-group ">
																				<div class="input-group-addon">Al</div>
																				<input id="UltimoDia_D" name="FechaFinal_D"
																					class="form-control formulario fecha-reporte" type="text"
																					data-inputmask-clearmaskonlostfocus="false" data-validation="date"
																					data-validation-format="dd/mm/yyyy"
																					data-validation-error-msg="La fecha es invalida"
																					data-bind="value:FechaFinal_D, event:{focusout : ValidarFecha}">
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</fieldset>
														<fieldset>
															<legend>Condición de venta</legend>
															<div class="col-md-12">
																<div class="row">
																	<div class="col-md-4">
																		<div class="form-group">
																			<label class="input-group">
																				<div class="input-group-addon addonrd">
																					<input class="radiobtn" type="radio" name="FormaPago_D" value="0"
																						data-bind="checked:FormaPago_D">
																				</div>
																				<div class="form-group radiotxt">Todos</div>
																			</label>
																		</div>
																	</div>
																	<div class="col-md-4">
																		<div class="form-group">
																			<label class="input-group">
																				<div class="input-group-addon addonrd">
																					<input class="radiobtn" type="radio" name="FormaPago_D" value="1"
																						data-bind="checked:FormaPago_D">
																				</div>
																				<div class="form-group radiotxt">Contado</div>
																			</label>
																		</div>
																	</div>
																	<div class="col-md-4">
																		<div class="form-group">
																			<label class="input-group">
																				<div class="input-group-addon addonrd">
																					<input class="radiobtn" type="radio" name="FormaPago_D" value="2"
																						data-bind="checked:FormaPago_D">
																				</div>
																				<div class="form-group radiotxt">Crédito</div>
																			</label>
																		</div>
																	</div>
																</div>
															</div>
														</fieldset>
														<fieldset>
															<legend>Ordenado por</legend>
															<div class="col-md-12">
																<div class="row">
																	<div class="col-md-4">
																		<div class="form-group">
																			<label class="input-group">
																				<div class="input-group-addon addonrd">
																					<input class="radiobtn" type="radio" name="Orden_D" value="0"
																						data-bind="checked:Orden_D">
																				</div>
																				<div class="form-group radiotxt">Fec.Emisión</div>
																			</label>
																		</div>
																	</div>
																	<div class="col-md-4">
																		<div class="form-group">
																			<label class="input-group">
																				<div class="input-group-addon addonrd">
																					<input class="radiobtn" type="radio" name="Orden_D" value="1"
																						data-bind="checked:Orden_D">
																				</div>
																				<div class="form-group radiotxt">Cliente</div>
																			</label>
																		</div>
																	</div>
																	<div class="col-md-4">
																		<div class="form-group">
																			<label class="input-group">
																				<div class="input-group-addon addonrd">
																					<input class="radiobtn" type="radio" name="Orden_D" value="2"
																						data-bind="checked:Orden_D">
																				</div>
																				<div class="form-group radiotxt">Documento</div>
																			</label>
																		</div>
																	</div>
																</div>
															</div>
														</fieldset>
														<fieldset>
															<legend>Cliente</legend>
															<div class="col-md-12">
																<div class="row">
																	<div class="col-md-4">
																		<div class="form-group">
																			<label class="input-group">
																				<div class="input-group-addon addonrd">
																					<input class="radiobtn" type="radio" name="NumeroDocumentoIdentidad_D"
																						value="0"
																						data-bind="checked:NumeroDocumentoIdentidad_D, event:{change:GrupoCliente_D}">
																				</div>
																				<div class="form-group radiotxt">Todos</div>
																			</label>
																		</div>
																	</div>
																	<div class="col-md-8">
																		<div class="form-group">
																			<label class="input-group">
																				<div class="input-group-addon addonrd">
																					<input class="radiobtn" type="radio" name="NumeroDocumentoIdentidad_D"
																						value="1"
																						data-bind="checked:NumeroDocumentoIdentidad_D, event:{change:GrupoCliente_D}">
																				</div>
																				<div id="DivBuscar_D" class="form-group radiotxt">Buscar</div>
																				<input id="TextoBuscarOculto_D" type="hidden" name="TextoCliente_D"
																					data-bind="value: IdPersona">
																				<input id="TextoBuscar_D" class="form-control formulario " name="" type="hidden"
																					placeholder="escribir para buscar....">
																			</label>
																		</div>
																	</div>
																</div>
															</div>
														</fieldset>
														<fieldset>
															<legend>Tipos de Documento</legend>
															<div class="col-md-12">
																<div class="form-group">
																	<div class="input-group">
																		<div class="multiselect-native-select formulario">
																			<button type="button"
																				class="multiselect dropdown-toggle btn btn-default btn-control"
																				data-toggle="dropdown">
																				<span class="multiselect-selected-text">DOCUMENTOS </span>
																				<span class="badge" data-bind="text: NumeroDocumentosSeleccionados"></span>
																				<b style="float: right;margin: 5px;" class="caret"></b>
																			</button>
																			<ul class="multiselect-container dropdown-menu">
																				<li>
																					<div class="checkbox">
																						<input id="SelectorTipoDocumentos_D" type="checkbox"
																							data-bind="event: { change: SeleccionarTodosComprobantes }" />
																						<label for="SelectorTipoDocumentos_D" class="checkbox"> Seleccionar
																							Todos</label>
																					</div>
																				</li>
																				<!-- ko foreach: TiposDocumentoVenta -->
																				<li>
																					<div class="checkbox">
																						<input type="checkbox"
																							data-bind="attr : { id: IdTipoDocumento() +'_TipoDocumento_D' }, event: {change: $parent.SeleccionarComprobante}" />
																						<label class="checkbox"
																							data-bind="text: NombreTipoDocumento, attr:{ for : IdTipoDocumento() +'_TipoDocumento_D'}"></label>
																					</div>
																				</li>
																				<!-- /ko -->
																			</ul>
																		</div>
																	</div>
																</div>
															</div>
														</fieldset>
														<br>
														<fieldset>
															<div class="input-group">
																<div class="input-group-addon formulario-venta">Almacén
																</div>
																<select id="combo-almacen" class="form-control formulario" data-bind="
																value: IdAsignacionSede,
																options: Almacenes,
																optionsValue: 'IdAsignacionSede' ,
																optionsText: 'NombreSede',
																optionsCaption: 'Todos'">
																</select>
															</div>
														</fieldset>
														<div class="col-md-12">
															<div class="row">
																<center>
																	<hr>
																	<!-- <br> -->
																	<button id="btnexcel_D" type="button" name="excel" class="btn btn-default"
																		data-bind="event:{click:OnClickBtnReportes}"> E<b><u>x</u></b>cel </button> &nbsp;
																	<button id="btnpdf_D" type="button" name="pdf" class="btn btn-default"
																		data-bind="event:{click:OnClickBtnReportes}"> PDF </button> &nbsp;
																	<button id="btnpantalla_D" type="button" name="pantalla" class="btn btn-default"
																		data-bind="event:{click:OnClickBtnReportes}"> Pantalla </button> &nbsp;
																</center>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</form>
								<!-- /ko -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /ko -->
