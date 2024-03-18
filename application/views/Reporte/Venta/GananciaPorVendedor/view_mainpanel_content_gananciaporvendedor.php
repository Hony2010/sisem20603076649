<!-- ko with : vmgReporteGananciaPorVendedor.dataReporteGananciaPorVendedor -->
<div class="main__cont">
	<div class="container-fluid half-padding">
		<div class="row">
			<div class="col-md-2 col-xs-12">
			</div>
			<div class="col-md-8 col-xs-12">
				<!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Reporte de Ganancia Por Producto</h3>
					</div>
					<div class="panel-body">
						<div class="datalist__result">
							<!-- ko with : Buscador -->
							<form class="form products-new" enctype="multipart/form-data" id="form_Gananciaporvendedor" name="form"
								action="" method="post">
								<div class="container-fluid">
									<div class="row">
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-12">
													<fieldset>
														<div class="col-md-12">
															<div class="row">
																<div class="col-md-12">
																	<div class="form-group">
																		<div class="input-group">
																			<div class="input-group-addon formulario">Agencias</div>
																			<select id="Agencia_Gananciaporvendedor" name="Agencia_Gananciaporvendedor"
																				class="form-control formulario" data-bind="
                                            options :Agencia,
                                            optionsValue :'IdSede',
                                            optionsText : 'NombreSede',
                                            optionsCaption : 'Todos' ">
																			</select>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</fieldset>
													<fieldset>
														<legend>Rango de fecha</legend>
														<div class="col-md-12">
															<div class="row">
																<div class="col-md-6">
																	<div class="form-group">
																		<div class="input-group">
																			<div class="input-group-addon">Del</div>
																			<input id="FechaInicio_Gananciaporvendedor" name="FechaInicio_Gananciaporvendedor"
																				class="form-control formulario fecha-reporte" type="text"
																				data-inputmask-clearmaskonlostfocus="false" data-validation="date"
																				data-validation-format="dd/mm/yyyy"
																				data-validation-error-msg="La fecha es invalida" type="date"
																				data-bind="value:FechaInicio_Gananciaporvendedor, event:{focusout : ValidarFecha}">
																		</div>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<div class="input-group ">
																			<div class="input-group-addon">Al</div>
																			<input id="FechaFinal_Gananciaporvendedor" name="FechaFinal_Gananciaporvendedor"
																				class="form-control formulario fecha-reporte" type="text"
																				data-inputmask-clearmaskonlostfocus="false" data-validation="date"
																				data-validation-format="dd/mm/yyyy"
																				data-validation-error-msg="La fecha es invalida"
																				data-bind="value:FechaFinal_Gananciaporvendedor, event:{focusout : ValidarFecha}">
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</fieldset>
													<fieldset>
														<legend>Producto</legend>
														<div class="col-md-12">
															<div class="row">
																<div class="col-md-4">
																	<div class="form-group">
																		<label class="input-group">
																			<div class="input-group-addon addonrd">
																				<input class="radiobtn" type="radio" name="IdProducto_Gananciaporvendedor"
																					value="0"
																					data-bind="checked:IdProducto_Gananciaporvendedor, event:{change:GrupoProducto_Gananciaporvendedor}">
																			</div>
																			<div class="form-group radiotxt">Todos</div>
																		</label>
																	</div>
																</div>
																<div class="col-md-8">
																	<div class="form-group">
																		<label class="input-group">
																			<div class="input-group-addon addonrd">
																				<input class="radiobtn" type="radio" name="IdProducto_Gananciaporvendedor"
																					value="1"
																					data-bind="checked:IdProducto_Gananciaporvendedor, event:{change:GrupoProducto_Gananciaporvendedor}">
																			</div>
																			<div id="DivBuscar_Gananciaporvendedor" class="form-group radiotxt">Buscar</div>
																			<input id="TextoBuscarOculto_Gananciaporvendedor" type="hidden"
																				name="TextoMercaderia_Gananciaporvendedor"
																				data-bind="value: TextoMercaderia_Gananciaporvendedor">
																			<input id="TextoBuscar_Gananciaporvendedor" class="form-control formulario "
																				name="" type="hidden" placeholder="escribir para buscar....">
																		</label>
																	</div>
																</div>
															</div>
														</div>
													</fieldset>
													<br>
													<fieldset>
														<div class="col-md-12">
															<div class="row">
																<div class="col-md-12">
																	<div class="form-group">
																		<div class="input-group">
																			<div class="input-group-addon formulario">Vendedor</div>
																			<select id="Vendedor_Gananciaporvendedor" name="Vendedor_Gananciaporvendedor"
																				class="form-control formulario" data-bind="
                                            options :Vendedor,
                                            optionsValue :'AliasUsuarioVenta',
                                            optionsText : 'AliasUsuarioVenta',
                                            optionsCaption : 'Todos' ">
																			</select>
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
																<button id="btnexcel_Gananciaporvendedor" type="button" name="excel"
																	class="btn btn-default" data-bind="event:{click:dataReporte}"> E<b><u>x</u></b>cel
																</button> &nbsp;
																<button id="btnpdf_Gananciaporvendedor" type="button" name="pdf" class="btn btn-default"
																	data-bind="event:{click:dataReporte}"> PDF </button> &nbsp;
																<button id="btnpantalla_Gananciaporvendedor" type="button" name="pantalla"
																	class="btn btn-default" data-bind="event:{click:dataReporte}"> Pantalla </button>
																&nbsp;
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
