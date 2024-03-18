<!-- ko with : vmgVentasMensuales.dataVentasMensuales -->
<div class="main__cont">
	<div class="container-fluid half-padding">
		<div class="row">
			<div class="col-md-2 col-xs-12">
			</div>
			<div class="col-md-8 col-xs-12">
				<!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Reporte de Ventas Mensuales</h3>
					</div>
					<div class="panel-body">
						<div class="datalist__result">
							<!-- ko with : Buscador -->
							<form class="form products-new" enctype="multipart/form-data" id="form_Mensual" name="form" action=""
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
																			<input id="FechaInicio_Mensual" name="FechaInicio_Mensual"
																				class="form-control formulario año-reporte" type="text"
																				data-inputmask-clearmaskonlostfocus="false" data-validation="date"
																				data-validation-format="yyyy" data-validation-error-msg="El año es invalido"
																				data-bind="value:FechaInicio_Mensual, event:{focusout : ValidarFecha}">
																		</div>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<div class="input-group">
																			<div class="input-group-addon">Al</div>
																			<input id="FechaFinal_Mensual" name="FechaFinal_Mensual"
																				class="form-control formulario año-reporte" type="text"
																				data-inputmask-clearmaskonlostfocus="false" data-validation="date"
																				data-validation-format="yyyy" data-validation-error-msg="El año es invalido"
																				data-bind="value:FechaFinal_Mensual, event:{focusout : ValidarFecha}">
																		</div>
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
																<button id="btnexcel_Mensual" type="button" name="excel" class="btn btn-default"
																	data-bind="event:{click:DescargarReporte_Mensual}"> E<b><u>x</u></b>cel </button>
																&nbsp;
																<button id="btnpdf_Mensual" type="button" name="pdf" class="btn btn-default"
																	data-bind="event:{click:DescargarReporte_Mensual}"> PDF </button> &nbsp;
																<button id="btnpantalla_Mensual" type="button" class="btn btn-default"
																	data-bind="event:{click:Pantalla_Mensual}"> Pantalla </button> &nbsp;
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
