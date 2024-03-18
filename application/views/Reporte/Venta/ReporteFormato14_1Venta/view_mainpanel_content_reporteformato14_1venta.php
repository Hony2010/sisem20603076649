<!-- ko with : vmgReporteFormato14_1Venta.dataReporteFormato14_1Venta -->
<div class="main__cont">
	<div class="container-fluid half-padding">
		<div class="row">
			<div class="col-md-2 col-xs-12">
			</div>
			<div class="col-md-8 col-xs-12">
				<!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Reporte de Formato 14.1 Ventas</h3>
					</div>
					<div class="panel-body">
						<div class="datalist__result">
							<!-- ko with : Buscador -->
							<form class="form products-new" enctype="multipart/form-data" id="form_Formato14" name="form" action=""
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
																			<input id="FechaInicio_Formato14" name="FechaInicio_Formato14"
																				class="form-control formulario fecha-reporte" type="text"
																				data-inputmask-clearmaskonlostfocus="false" data-validation="date"
																				data-validation-format="dd/mm/yyyy"
																				data-validation-error-msg="La fecha es invalida"
																				data-bind="value:FechaInicio_Formato14, event:{focusout : ValidarFecha}">
																		</div>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<div class="input-group">
																			<div class="input-group-addon">Al</div>
																			<input id="FechaFinal_Formato14" name="FechaFinal_Formato14"
																				class="form-control formulario fecha-reporte" type="text"
																				data-inputmask-clearmaskonlostfocus="false" data-validation="date"
																				data-validation-format="dd/mm/yyyy"
																				data-validation-error-msg="La fecha es invalida"
																				data-bind="value:FechaFinal_Formato14, event:{focusout : ValidarFecha}">
																		</div>
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
																					<input id="SelectorTipoDocumentos_14" type="checkbox"
																						data-bind="event: { change: SeleccionarTodosComprobantes }" />
																					<label for="SelectorTipoDocumentos_14" class="checkbox"> Seleccionar
																						Todos</label>
																				</div>
																			</li>
																			<!-- ko foreach: TiposDocumentoVenta -->
																			<li>
																				<div class="checkbox">
																					<input type="checkbox"
																						data-bind="attr : { id: IdTipoDocumento() +'_TipoDocumento_14' }, event: {change: $parent.SeleccionarComprobante}" />
																					<label class="checkbox"
																						data-bind="text: NombreTipoDocumento, attr:{ for : IdTipoDocumento() +'_TipoDocumento_R'}"></label>
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
																<button id="btnexcel_Formato14" type="button" name="excel" class="btn btn-default"
																	data-bind="event:{click:OnClickBtnReportes}"> E<b><u>x</u></b>cel </button> &nbsp;
																<button id="btnpdf_Formato14" type="button" name="pdf" class="btn btn-default"
																	data-bind="event:{click:OnClickBtnReportes}"> PDF </button> &nbsp;
																<button id="btnpantalla_Formato14" type="button" name="pantalla" class="btn btn-default"
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
