<!-- ko with : vmgProductosPorFamilia.dataProductosPorFamilia -->
<div class="main__cont">
	<div class="container-fluid half-padding">
		<div class="row">
			<div class="col-md-2 col-xs-12">
			</div>
			<div class="col-md-8 col-xs-12">
				<!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Reporte de Productos por Familias</h3>
					</div>
					<div class="panel-body">
						<div class="datalist__result">
							<!-- ko with : Buscador -->
							<form class="form products-new" enctype="multipart/form-data" id="form_PF" name="form"
								action="" method="post">
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
																			<input id="FechaInicio_PF"
																				name="FechaInicio_PF"
																				class="form-control formulario fecha-reporte"
																				type="text"
																				data-inputmask-clearmaskonlostfocus="false"
																				data-validation="date"
																				data-validation-format="dd/mm/yyyy"
																				data-validation-error-msg="La fecha es invalida"
																				data-bind="value:FechaInicio_PF, event:{focusout : ValidarFecha}">
																		</div>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<div class="input-group">
																			<div class="input-group-addon">Al</div>
																			<input id="FechaFinal_PF"
																				name="FechaFinal_PF"
																				class="form-control formulario fecha-reporte"
																				type="text"
																				data-inputmask-clearmaskonlostfocus="false"
																				data-validation="date"
																				data-validation-format="dd/mm/yyyy"
																				data-validation-error-msg="La fecha es invalida"
																				data-bind="value:FechaFinal_PF, event:{focusout : ValidarFecha}">
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<!-- ko if: ParametroHoraReporte_PF() == 1 -->
														<div class="col-md-12">
															<div class="row">
																<div class="col-md-6">
																	<div class="form-group">
																		<div class="input-group">
																			<div class="input-group-addon">Hora I.</div>
																			<input
																				class="form-control formulario hora-reporte"
																				type="text"
																				data-bind="value:HoraInicio_PF">
																		</div>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<div class="input-group ">
																			<div class="input-group-addon">Hora F.</div>
																			<input
																				class="form-control formulario hora-reporte"
																				type="text"
																				data-bind="value:HoraFinal_PF">
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<!-- /ko -->
													</fieldset>
													<br>
													<fieldset>
														<div class="input-group">
															<div class="input-group-addon formulario-venta">Almacén
															</div>
															<select id="combo-almacen" class="form-control formulario"
																data-bind="
																value: IdAsignacionSede,
																options: Almacenes,
																optionsValue: 'IdAsignacionSede' ,
																optionsText: 'NombreSede',
																optionsCaption: 'Todos'">
															</select>
														</div>
													</fieldset>
													<br>
													<fieldset>
														<div class="input-group">
															<div class="input-group-addon formulario-venta">Familias
															</div>
															<select id="ComboFamilia" class="form-control formulario"
																data-bind="
																value: IdFamiliaProducto,
																options: FamiliasProducto,
																optionsValue: 'IdFamiliaProducto' ,
																optionsText: 'NombreFamiliaProducto',
																optionsCaption: 'Todos'">
															</select>
														</div>
													</fieldset>
													<br>
													<div class="col-md-12">
														<div class="row">
															<center>
																<hr>
																<button id="btnexcel_PF" type="button" name="excel"
																	class="btn btn-default"
																	data-bind="event:{click:DescargarReporte_PF}">
																	E<b><u>x</u></b>cel </button> &nbsp;
																<button id="btnpdf_PF" type="button" name="pdf"
																	class="btn btn-default"
																	data-bind="event:{click:DescargarReporte_PF}"> PDF
																</button> &nbsp;
																<button id="btnpantalla_PF" type="button"
																	class="btn btn-default"
																	data-bind="event:{click:Pantalla_PF}"> Pantalla
																</button> &nbsp;
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
