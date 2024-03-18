<!-- ko with : vmgReporteVentaGeneralLubricante.dataReporteVentaGeneral -->
<div class="main__cont">
	<div class="container-fluid half-padding">
		<div class="row">
			<div class="col-md-2 col-xs-12">
			</div>
			<div class="col-md-8 col-xs-12">
				<!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Reporte de Ventas General Lubricante</h3>
					</div>
					<div class="panel-body">
						<div class="datalist__result">
							<!-- ko with : Buscador -->
							<form class="form products-new" enctype="multipart/form-data" id="form_R" name="form" action=""
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
																			<input id="PrimerDia_R" name="FechaInicio_R"
																				class="form-control formulario fecha-reporte" type="text"
																				data-inputmask-clearmaskonlostfocus="false" data-validation="date"
																				data-validation-format="dd/mm/yyyy"
																				data-validation-error-msg="La fecha es invalida"
																				data-bind="value:FechaInicio_R, event:{focusout : ValidarFecha}">
																		</div>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<div class="input-group ">
																			<div class="input-group-addon">Al</div>
																			<input id="UltimoDia_R" name="FechaFinal_R"
																				class="form-control formulario fecha-reporte" type="text"
																				data-inputmask-clearmaskonlostfocus="false" data-validation="date"
																				data-validation-format="dd/mm/yyyy"
																				data-validation-error-msg="La fecha es invalida"
																				data-bind="value:FechaFinal_R, event:{focusout : ValidarFecha}">
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<!-- ko if: ParametroHoraReporte_R() == 1 -->
														<div class="col-md-12">
															<div class="row">
																<div class="col-md-6">
																	<div class="form-group">
																		<div class="input-group">
																			<div class="input-group-addon">Hora I.</div>
																			<input class="form-control formulario hora-reporte" type="text"
																				data-bind="value:HoraInicio_R">
																		</div>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<div class="input-group ">
																			<div class="input-group-addon">Hora F.</div>
																			<input class="form-control formulario hora-reporte" type="text"
																				data-bind="value:HoraFinal_R">
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<!-- /ko -->
													</fieldset>
													<fieldset>
														<legend>Vehiculo</legend>
														<div class="col-md-12">
															<div class="row">
																<div class="col-md-6">
																	<div class="form-group">
																		<div class="input-group">
																			<div class="input-group-addon">Placa</div>
																			<input class="form-control formulario" type="text" data-bind="value:Placa">
																		</div>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<div class="input-group ">
																			<div class="input-group-addon">Radio Taxi</div>
																			<input class="form-control formulario" type="text" data-bind="value:RadioTaxi">
																		</div>
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
																				<input class="radiobtn" type="radio" name="NumeroDocumentoIdentidad_R" value="0"
																					data-bind="checked:NumeroDocumentoIdentidad_R, event:{change:GrupoCliente_R}">
																			</div>
																			<div class="form-group radiotxt">Todos</div>
																		</label>
																	</div>
																</div>
																<div class="col-md-8">
																	<div class="form-group">
																		<label class="input-group">
																			<div class="input-group-addon addonrd">
																				<input class="radiobtn" type="radio" name="NumeroDocumentoIdentidad_R" value="1"
																					data-bind="checked:NumeroDocumentoIdentidad_R, event:{change:GrupoCliente_R}">
																			</div>
																			<div id="DivBuscar_R" class="form-group radiotxt">Buscar</div>
																			<input id="TextoBuscarOculto_R" type="hidden" name="TextoCliente_D"
																				data-bind="value: IdPersona">
																			<input id="TextoBuscar_R" class="form-control formulario " name="" type="hidden"
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
																					<input id="SelectorTipoDocumentos_RL" type="checkbox"
																						data-bind="event: { change: SeleccionarTodosComprobantesLubricante }" />
																					<label for="SelectorTipoDocumentos_RL" class="checkbox"> Seleccionar
																						Todos</label>
																				</div>
																			</li>
																			<!-- ko foreach: TiposDocumentoVenta -->
																			<li>
																				<div class="checkbox">
																					<input type="checkbox"
																						data-bind="attr : { id: IdTipoDocumento() +'_TipoDocumento_RL' }, event: {change: $parent.SeleccionarComprobanteLubricante}" />
																					<label class="checkbox"
																						data-bind="text: NombreTipoDocumento, attr:{ for : IdTipoDocumento() +'_TipoDocumento_RL'}"></label>
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
																<button id="btnexcel_R" name="excel" type="button" class="btn btn-default"
																	data-bind="event:{click:OnClickBtnReportes}"> E<b><u>x</u></b>cel </button> &nbsp;
																<button id="btnpdf_R" name="pdf" type="button" class="btn btn-default"
																	data-bind="event:{click:OnClickBtnReportes}"> PDF </button> &nbsp;
																<button id="btnpantalla_R" name="pantalla" type="button" class="btn btn-default"
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
<!-- /ko -->
