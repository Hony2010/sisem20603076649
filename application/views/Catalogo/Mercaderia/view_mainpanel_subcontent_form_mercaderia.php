<div class="modal fade bd-example-modal-lg" data-backdrop-limit="1" tabindex="-1" role="dialog" id="modalMercaderia">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"
					data-bind="event:{click: $root.Cerrar}"><span aria-hidden="true">&times;</span></button>
				<h4 class="panel-title"><span data-bind="text: $root.MostrarTitulo()"></span></h4>
			</div>
			<div class="modal-body">
				<!-- ko with : $root.data.Mercaderia  -->
				<form class="form products-new" enctype="multipart/form-data" id="form" name="form" action="" method="post">
					<div class="container-fluid">
						<input type="hidden" name="IdProducto" id="IdProducto" data-bind="value : IdProducto">
						<div class="row">
							<div class="col-md-9">
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Código <strong class="alert-info">(*)</strong></div>
												<input id="CodigoMercaderia" class="form-control formulario" type="text"
													data-bind="value: CodigoMercaderia, event: {focus : OnFocus, keydown : OnKeyEnter}">
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<div class="input-group">
												<div class="checkbox">
													<input name="CheckNumeroDocumento" id="CheckCodigoMercaderia" type="checkbox"
														class="form-control formulario"
														data-bind="checked : CodigoAutomatico, event: { change : OnChangeCheckNumeroDocumento , focus : OnFocus , keydown : OnKeyEnter}">
													<label for="CheckNumeroDocumento">Editar</label>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Tipo Existencia</div>
												<select disabled id="combo-tipoexistencia" class="form-control formulario no-tab" tabindex="-1"
													data-bind="
                          value : IdTipoExistencia,
                          options : $root.data.TiposExistencia,
                          optionsValue : 'IdTipoExistencia' ,
                          optionsText : 'NombreTipoExistencia' ">
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Familia</div>
												<select id="combo-familia" class="form-control formulario"
													data-bind="value : IdFamiliaProducto, event: { change : $root.OnChangeFamilia , focus : OnFocus, keydown : OnKeyEnter}">
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Sub Familia</div>
												<select id="combo-subfamiliaproducto" class="form-control formulario"
													data-bind="event:{ change: $root.OnChangeSubFamilia, focus : OnFocus, keydown : OnKeyEnter}">
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Marca</div>
												<select id="combo-marca" class="form-control formulario"
													data-bind="value : IdMarca, event: { change : $root.OnChangeMarca, focus : OnFocus, keydown : OnKeyEnter}">
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Modelo</div>
												<select id="combo-modelo" class="form-control formulario"
													data-bind="event:{change: $root.OnChangeModelo, focus : OnFocus, keydown : OnKeyEnter}">
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Linea Producto</div>
												<select id="combo-lineaproducto" class="form-control formulario" data-bind="
                          value : IdLineaProducto,
                          options : $root.data.LineasProducto,
                          optionsValue : 'IdLineaProducto' ,
                          optionsText : 'NombreLineaProducto',
                          event : {focus : OnFocus, keydown : OnKeyEnter, change: $root.OnChangeLineaProducto} ">
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Fabricante</div>
												<select id="combo-fabricante" class="form-control formulario" data-bind="
                          value : IdFabricante,
                          options : $root.data.Fabricantes,
                          optionsValue : 'IdFabricante' ,
                          optionsText : 'NombreFabricante',
                          event : {focus : OnFocus, keydown : OnKeyEnter}">
												</select>
											</div>
										</div>
									</div>
								</div>
								<!-- ko if: ParametroRubroRepuesto() == 1 -->
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Codigo Nuevo</div>
												<input class="form-control formulario" type="text"
													data-bind="value: CodigoMercaderia2, event : {focus : OnFocus, keydown : OnKeyEnter, change : $root.CrearNombreLargoProducto}">
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Codigo Alterno</div>
												<input class="form-control formulario" type="text"
													data-bind="value: CodigoAlterno, event : {focus : OnFocus, keydown : OnKeyEnter, change : $root.CrearNombreLargoProducto}">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Referencia</div>
												<input class="form-control formulario" type="text"
													data-bind="value: Referencia, event : {focus : OnFocus, keydown : OnKeyEnter, change : $root.CrearNombreLargoProducto}">
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Proveedor</div>
												<input id="ReferenciaProveedor" class="form-control formulario" type="text" data-bind="
                        value: ReferenciaProveedor, 
                        event: { focus: OnFocus, keydown: OnKeyEnter, change: $root.CrearNombreLargoProducto }">
												<!-- <input id="NombreProveedor" class="form-control formulario" type="text" data-bind="
                        value: NombreProveedor, 
                        event : {change: ValidarProveedor}" data-validation="autocompletado_proveedor" data-validation-error-msg="" data-validation-text-found=""> -->
											</div>
										</div>
									</div>
								</div>
								<!-- /ko -->
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Nº Serie</div>
												<input class="form-control formulario" type="text"
													data-bind="value: NumeroSerie, event : {focus : OnFocus, keydown : OnKeyEnter}">
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Nº Motor</div>
												<input class="form-control formulario" type="text" data-bind="
                        value: NumeroMotor, 
                        event: { focus: OnFocus, keydown: OnKeyEnter, change: $root.CrearNombreLargoProducto}">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Nº Placa</div>
												<input class="form-control formulario" type="text"
													data-bind="value: NumeroPlaca, event : {focus : OnFocus, keydown : OnKeyEnter}">
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Año</div>
												<input class="form-control formulario" type="text"
													data-bind="value: Ano, event : {focus : OnFocus, keydown : OnKeyEnter}">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Color</div>
												<input class="form-control formulario" type="text"
													data-bind="value: Color, event : {focus : OnFocus, keydown : OnKeyEnter, change: $root.OnChangeInputColor}">
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Textura</div>
												<input class="form-control formulario" type="text"
													data-bind="value: Textura, event : {focus : OnFocus, keydown : OnKeyEnter}">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Talla</div>
												<input class="form-control formulario" type="text"
													data-bind="value: Talla, event : {focus : OnFocus, keydown : OnKeyEnter}">
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Tamaño</div>
												<input class="form-control formulario" type="text"
													data-bind="value: Tamano, event : {focus : OnFocus, keydown : OnKeyEnter}">
											</div>
										</div>
									</div>
								</div>
								<!-- ko if: ParametroRubroRepuesto() == 1 -->
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Descrip. Larga <strong
														class="alert-info">(*)</strong></i></div>
												<input id="NombreLargoProducto" class="form-control formulario" type="text"
													data-bind="value: NombreLargoProducto, event : {focus : OnFocus, keydown : OnKeyEnter}">
											</div>
										</div>
									</div>
								</div>
								<!-- /ko -->
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Descripción <strong
														class="alert-info">(*)</strong></i></div>
												<input id="Descripcion" class="form-control formulario" type="text"
													data-bind="value: NombreProducto, event : {focus : OnFocus, keydown : OnKeyEnter}">
											</div>
										</div>
									</div>
									<!-- ko if: ParametroRestaurante() != 0 -->
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="multiselect-native-select formulario">
													<button type="button" class="multiselect dropdown-toggle btn btn-default btn-control"
														data-toggle="dropdown">
														<span class="multiselect-selected-text">Agregados</span><span
															id="numero_items_anotacionesplato" class="badge"
															data-bind="text: TotalAnotacionesPlatoSeleccionados"></span>
														<b class="caret"></b>
													</button>
													<ul class="multiselect-container dropdown-menu">
														<li>
															<div class="checkbox">
																<input id="selector_anotacionesplato_todos" type="checkbox"
																	data-bind="checked: SeleccionarTodosAnotacionesPlato, event: {change: ChangeTodosAnotacionesPlato}" />
																<label class="checkbox" for="selector_anotacionesplato_todos"> Seleccionar Todos</label>
															</div>
														</li>
														<!-- ko foreach: AnotacionesPlatoProducto -->
														<li>
															<div class="checkbox">
																<input type="checkbox"
																	data-bind="checked: Seleccionado, event: {change: $parent.CambioAnotacionesPlato}, attr : { id: IdAnotacionPlato() +'_anotacionesplato' }" />
																<label class="checkbox"
																	data-bind="text: NombreAnotacionPlato(), attr:{ for : IdAnotacionPlato() +'_anotacionesplato'}"></label>
															</div>
														</li>
														<!-- /ko -->
													</ul>
												</div>
											</div>
										</div>
									</div>
									<!-- /ko -->
									<!-- ko if: ParametroBonificacion() != 0  -->
									<div class="col-md-3">
										<div class="checkbox">
											<input type="checkbox" id="CheckBonificacion" data-bind="checked: IndicadorAfectoBonificacion" />
											<label class="checkbox" for="CheckBonificacion">Bonificacion</label>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<div class="input-group">
												<div class="multiselect-native-select formulario">
													<button type="button" class="multiselect dropdown-toggle btn btn-default btn-control"
														data-toggle="dropdown"
														data-bind="enable: IndicadorAfectoBonificacion, event: { click:OnClickBtnBonificaciones }">
														<span class="multiselect-selected-text">Bonificaciones</span>
														<!-- <span class="badge" data-bind="text: Bonificaciones().length "></span> -->
														<b class="caret"></b>
													</button>
													<ul class="multiselect-container dropdown-menu dropdown-menu-bonificaciones"
														style="width: 450px;">
														<li>
															<div class="form-group">
																<button type="button" class="btn btn-primary btn-control" title="Nueva Dirección"
																	data-bind="event: { click: OnClickBtnNuevaBonificacionMercaderia }">Nuevo</button>
															</div>
														</li>
														<li>
															<table class="table">
																<thead>
																	<tr>
																		<th width="55">Cantidad</th>
																		<th>Producto</th>
																		<th>Bonificaciones</th>
																		<th width="41"> </th>
																	</tr>
																</thead>
																<tbody>
																	<!-- ko foreach: Bonificaciones -->
																	<tr>
																		<td>
																			<div class="form-group">
																				<input type="text" class="form-control formulario text-right" data-bind="
                                        value: Cantidad,
                                        numbertrim: Cantidad,
                                        event: {focus: OnFocus, keydown: OnKeyEnter }" style="width: 50px">
																			</div>
																		</td>
																		<td>
																			<div class="form-group">
																				<input type="text" class="form-control formulario"
																					data-bind="
                                        value: ProductoBonificacion,
                                        attr: { id : 'inputProductoBonificacion_' + IdBonificacion(), 'data-validation-text-found': ProductoBonificacion } ,
                                        event: { focus: OnFocus, keydown: ValidarProductoBonificacion, blur: ValidarProductoBonificacion }"
																					data-validation="autocompletado_producto" data-validation-error-msg="">
																			</div>
																		</td>
																		<td>
																			<div class="form-group">
																				<input type="text" class="form-control formulario text-right" data-bind="
                                        value: CantidadBonificacion,
                                        numbertrim: CantidadBonificacion,
                                        event: {focus: OnFocus, keydown: OnKeyEnter }" style="width: 100px">
																			</div>
																		</td>
																		<td>
																			<button type="button" class="btn btn-danger btn-operaciones"
																				data-bind="event: { click: OnClickBtnRemoverBonificacionMercaderia }">X</button>
																		</td>
																	</tr>
																	<!-- /ko -->
																</tbody>
															</table>
														</li>
													</ul>
												</div>
											</div>
										</div>
									</div>
									<!-- /ko -->
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Beneficios</div>
												<input class="form-control formulario" type="text"
													data-bind="value: Beneficio, event : {focus : OnFocus, keydown : OnKeyEnter}">
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Aplicación</div>
												<input class="form-control formulario" type="text"
													data-bind="value: Aplicacion, event : {focus : OnFocus, keydown : OnKeyEnter, change: $root.CrearNombreLargoProducto}">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Origen Mercadería <strong
														class="alert-info">(*)</strong></div>
												<select id="combo-origenmercaderia" class="form-control formulario" data-bind="
                          value : IdOrigenMercaderia,
                          options : $root.data.OrigenMercaderia,
                          optionsValue : 'IdOrigenMercaderia' ,
                          optionsText : 'NombreOrigenMercaderia',
                          event : {focus : OnFocus, keydown : OnKeyEnter}">
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Otros Datos</i></div>
												<input class="form-control formulario" type="text"
													data-bind="value: OtroDato, event : {focus : OnFocus, keydown : OnKeyEnter}">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Unidad de Medida <strong
														class="alert-info">(*)</strong></div>
												<select id="combo-unidadmedida" class="form-control formulario" data-bind="
                                      value : IdUnidadMedida,
                                      options : $root.data.UnidadesMedida,
                                      optionsValue : 'IdUnidadMedida' ,
                                      optionsText : 'NombreUnidadMedida',
                                      event : {focus : OnFocus, keydown : OnKeyEnter} ">
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Peso (KG)</div>
												<input class="form-control formulario" type="text"
													data-bind="value: PesoUnitario, event : {focus : OnFocus, keydown : OnKeyEnter}">
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Nro Piezas</div>
												<input class="form-control formulario" type="text"
													data-bind="value: NumeroPiezas, event : {focus : OnFocus, keydown : OnKeyEnter}">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Fecha Vencimiento</div>
												<input class="form-control formulario" type="text"
													data-bind="value: FechaVencimiento, event : {focus : OnFocus, keydown : OnKeyEnter}">
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="input-group">
											<div class="input-group-addon formulario">Moneda</div>
											<select id="combo-moneda" class="form-control formulario" data-bind="
                              value : IdMoneda,
                              options : $root.data.Monedas,
                              optionsValue : 'IdMoneda' ,
                              optionsText : 'NombreMoneda',
                              event : {focus : OnFocus, keydown : OnKeyEnter} ">
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<div class="input-group">
											<div class="input-group-addon formulario">Precio Unit. Venta</div>
											<input class="form-control formulario" type="text"
												data-bind="value: PrecioUnitario, event : {focus : OnFocus, keydown : OnKeyEnter}">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Stock Minimo</div>
												<input class="form-control formulario" type="text"
													data-bind="value: StockMinimo, event : {focus : OnFocus, keydown : OnKeyEnter}">
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Stock Máximo</div>
												<input class="form-control formulario" type="text"
													data-bind="value: StockMaximo, event : {focus : OnFocus, keydown : OnKeyEnter}">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="radio radio-inline">
											<input id="rad1" type="radio" name="radio" class="no-tab" value="1"
												data-bind="checked : IdTipoAfectacionIGV, event : { change : $root.OnChangeTipoAfectacionIGV }">
											<label for="rad1">Afecto IGV</label>
										</div>
										<div class="radio radio-inline">
											<input id="rad2" type="radio" name="radio" class="no-tab" value="2"
												data-bind="checked : IdTipoAfectacionIGV, event : { change : $root.OnChangeTipoAfectacionIGV }">
											<label for="rad2">Exonerado IGV</label>
										</div>
										<div class="radio radio-inline">
											<input id="rad3" type="radio" name="radio" class="no-tab" value="3"
												data-bind="checked : IdTipoAfectacionIGV, event : { change : $root.OnChangeTipoAfectacionIGV }">
											<label for="rad3">Inafecto IGV</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Tipo Sistema Calculo ISC</div>
												<select id="combo-tiposistemaisc" class="form-control formulario" data-bind="
                                value : IdTipoSistemaISC,
                                options : $root.data.TiposSistemaISC,
                                optionsValue : 'IdTipoSistemaISC' ,
                                optionsText : 'NombreTipoSistemaISC',
                                event : { change : $root.OnChangeTipoSistemaISC } ">
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="checkbox checkbox-inline">
											<input id="indicadoricbper" type="checkbox" class="no-tab"
												data-bind="checked : IndicadorAfectoICBPER">
											<label for="indicadoricbper">Afecto ICBPER</label>
										</div>
										<div class="checkbox checkbox-inline">
											<input id="IndicadorEstadoProducto" type="checkbox" class="no-tab"
												data-bind="checked : IndicadorEstadoProducto">
											<label for="IndicadorEstadoProducto">Estado Mercaderia</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-addon formulario">Forma de calculo</div>
												<select id="combo-tiposistemaisc" class="form-control formulario"
                          data-bind="value : EstadoCampoCalculo">
                          <option value="3">Bloquear P.U., Cantidad, Menos importe</option>
                          <option value="2">Bloquear Cantidad, Importe, Menos P.U.</option>
                          <option value="1">Bloquear P.U., Importe, Menos cantidad</option>
                          <option value="0">Ningun bloqueo</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<!-- ko if: ParametroRestaurante() != 0 -->
								<div class="row">
									<div class="col-md-12">
										<fieldset>
											<legend>Beneficios de Tarjeta 7</legend>
											<div class="col-md-6">
												<div class="form-group">
													<div class="radio radio-inline">
														<input id="TipoDescuentoPorcentual" type="radio" name="TipoDescuento" class="no-tab"
															value="0"
															data-bind="checked : TipoDescuento, event : { change : $root.OnChangeTipoAfectacionIGV }">
														<label for="TipoDescuentoPorcentual">Porcentual</label>
													</div>
													<div class="radio radio-inline">
														<input id="TipoDescuentoMonto" type="radio" name="TipoDescuento" class="no-tab" value="1"
															data-bind="checked : TipoDescuento, event : { change : $root.OnChangeTipoAfectacionIGV }">
														<label for="TipoDescuentoMonto">Monto</label>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon formulario">Valor Descuento</div>
														<input class="form-control formulario" type="text"
															data-bind="value: ValorDescuento, event : {focus : OnFocus, keydown : OnKeyEnter}">
													</div>
												</div>
											</div>
										</fieldset>
									</div>
								</div>
								<!-- /ko -->
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<strong class="alert-info">(*) Campos Obligatorios</strong>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-3">
								<div class="row">
									<div class="col-md-12">
										<center>
											<div class="form-group">
												<div class="">
													<a data-toogle="modal" data-bind="click : AbrirPreview">
														<!--  data-bind="click : AbrirPreview"  -->
														<img src="" width="150" height="150" class="img-rounded foto" id="img_FileFoto">
													</a>
													<input type="hidden" id="InputFileName" name="InputFileName" value="FileFoto">
												</div>
												<div tabindex="500" class="btn btn-default btn-file" style="width: 150px;">
													<span class="hidden-xs glyphicon glyphicon-folder-open"></span> &nbsp <label> Foto</label>
													<input type="file" id="FileFoto" name="FileFoto"
														data-bind="event : { change : OnChangeInputFile }" />
												</div>
											</div>
											<div class="form-group">
												<button type="button" class="btn btn-default btn-control no-tab" tabindex="-1"
													style="width: 150px;" name="button" data-bind="event{ click: BuscarGoogle}">Buscar imagen en
													Internet</button>
											</div>
										</center>
										<br>
									</div>
								</div>
								<!-- ko if: ParametroCodigoBarras() != 0 -->
								<div class="row">
									<div class="col-md-12">
										<center>
											<div class="form-group">
												<div class="">
													<img src="" width="115" height="63" class="img-rounded foto" id="img_BFileFoto">
													<div id="barcode"></div>
												</div>
												<input type="hidden" id="InputFileCode" name="InputFileCode" value="CodigoBarrasImg">
												<input type="hidden" id="CodigoBarrasImage" name="CodigoBarrasImage" value="" />
												<div tabindex="500" class="btn btn-default btn-file" style="width: 115px;"
													data-bind="event : { click: $root.GenerarCodigoBarra }">
													<i class="glyphicon glyphicon-folder-open"></i>&nbsp;
													<span>Codigo Barras</span>
												</div>
											</div>
										</center>
									</div>
								</div>
								<div id="div-codigobarras" class="hidden">
								</div>
								<!-- /ko -->
							</div>
						</div>

						<div class="row">
							<center>
								<button id="btn_Grabar" type="button" class="btn btn-success focus-control no-tab" tabindex="-1"
									data-bind="click : $root.Guardar">Grabar</button> &nbsp;
								<button id="btn_Limpiar" type="button" class="btn btn-default focus-control no-tab" tabindex="-1"
									data-bind="click : $root.Deshacer">Deshacer</button> &nbsp;
								<button id="btn_Cerrar" type="button" class="btn btn-default focus-control no-tab" tabindex="-1"
									data-bind="click : $root.Cerrar">Cerrar</button>
							</center>
						</div>
						<div class="row">
							<div class="col-md-12">
								<strong class="alert-info">* Grabar = ALT + G</strong>
							</div>
						</div>
					</div>

				</form>
				<!-- /ko -->
				<!--<center>
            <img src="" width="60%" height="60%" id="foto_previa" name="foto_previa">
        </center>-->
			</div>
		</div>
	</div>
</div>
