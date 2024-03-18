<!-- ko with : vmgReporteListaPrecios -->
<div class="main__cont">
	<div class="container-fluid half-padding">
		<div class="row">
			<div class="col-md-2 col-xs-12">
			</div>
			<div class="col-md-8 col-xs-12">
				<!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Lista de Precios</h3>
					</div>
					<div class="panel-body">
						<div class="datalist__result">
							<!-- ko with : Buscador -->
							<form id="FormReporteListaPrecios">
								<div class="container-fluid">
									<div class="row">
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-12">
													<fieldset>
														<legend>Filtros</legend>
														<div class="col-md-12">
															<div class="row">
																<div class="col-md-6">
																	<div class="form-group">
																		<div class="input-group">
																			<div class="input-group-addon formulario-venta">Sede</div>
																			<select name="combo-sede" id="combo-sede" class="form-control formulario"
																				data-bind="
                                              value : IdSede,
                                              options : Sedes,
                                              optionsValue : 'IdSede' ,
                                              optionsText : 'NombreSede',                          
                                              event: {}">
																			</select>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-12">
															<div class="row">
																<div class="col-md-6">
																	<div class="form-group">
																		<div class="input-group">
																			<div class="input-group-addon formulario">Familias</div>
																			<select id="ComboFamilias" class="form-control formulario" data-bind="
                                      value: IdFamilia,
                                      options: Familias,
                                      optionsValue: 'IdFamiliaProducto' ,
                                      optionsText: 'NombreFamiliaProducto',
                                      optionsCaption: 'Todos',
                                      event: { change: ObtenerSubFamiliasDesdeFamilia }">
																			</select>
																		</div>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<div class="input-group ">
																			<div class="input-group-addon formulario">Sub Familias</div>
																			<select id="ComboSubFamilias" class="form-control formulario" data-bind="
                                      value: IdSubFamilia,
                                      options: DataSubFamilias,
                                      optionsValue: 'IdSubFamiliaProducto' ,
                                      optionsText: 'NombreSubFamiliaProducto',
                                      optionsCaption: 'Todos',
                                      event: {}">
																			</select>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="col-md-6">
																	<div class="form-group">
																		<div class="input-group">
																			<div class="input-group-addon formulario">Marcas</div>
																			<select id="ComboMarcas" class="form-control formulario" data-bind="
                                      value: IdMarca,
                                      options: Marcas,
                                      optionsValue: 'IdMarca' ,
                                      optionsText: 'NombreMarca',
                                      optionsCaption: 'Todos',
                                      event: { change: ObtenerModelosDesdeMarca }">
																			</select>
																		</div>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<div class="input-group ">
																			<div class="input-group-addon formulario formulario"> Modelos </div>
																			<select id="ComboModelos" class="form-control formulario" data-bind="
                                      value: IdModelo,
                                      options: DataModelos,
                                      optionsValue: 'IdModelo' ,
                                      optionsText: 'NombreModelo',
                                      optionsCaption: 'Todos',
                                      event: {}">
																			</select>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="col-md-12">
																	<div class="form-group">
																		<div class="input-group">
																			<div class="input-group-addon formulario">Lineas Producto</div>
																			<select id="ComboLineasProducto" class="form-control formulario" data-bind="
                                      value: IdLineaProducto,
                                      options: LineasProducto,
                                      optionsValue: 'IdLineaProducto' ,
                                      optionsText: 'NombreLineaProducto',
                                      optionsCaption: 'Todos',
                                      event: { }">
																			</select>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="col-md-12">
																	<div class="form-group">
																		<div class="input-group">
																			<div class="input-group-addon formulario">Descripción</div>
																			<input class="form-control formulario" data-bind="value: TextoFiltro">
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
													<br>
													<div class="col-md-12">
														<div class="row text-center">
															<hr>
															<button id="btnexcel" type="button" name="excel" class="btn btn-default"
																data-bind="event: { click: DescargarReporteExcel }"> E<b><u>x</u></b>cel </button>
															&nbsp;
															<button id="btnpdf" type="button" name="pdf" class="btn btn-default"
																data-bind="event: { click: DescargarReportePdf }"> PDF </button> &nbsp;
															<button id="btnpantalla" type="button" class="btn btn-default"
																data-bind="event: { click: MostrarReportePantalla }"> Pantalla </button> &nbsp;
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
