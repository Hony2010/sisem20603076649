<div class="modal fade" data-backdrop-limit="1" tabindex="-1" 
role="dialog" id="modalCuotasPagoClienteComprobanteVenta" data-bind="
bootstrapmodal: showCuotasPago,
show: showCuotasPago,
onhiden :  function() { return HideCuotasPago(window)}, backdrop: 'static'">
	<div class="modal-dialog-mm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span></button>
				<h4 class="panel-title">Cuotas de Pago</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-3" style="padding-top:5px;padding-bottom:5px">
							
						</div>
						<div class="col-md-1">
						
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
						</div>
						<div class="col-md-8">
							<table class="table" id="tablaCuotasPagoClienteComprobanteVenta">
								<thead>
									<tr>
										<th class="col-md-1 text-center"> #Cuota</th>
										<th class="col-md-3">Fecha Cuota</th>
										<th class="col-md-3">Monto Cuota</th>
										<th class="col-md-1">	<button class="btn btn-success btn-control" title="Nueva Cuota"
												data-bind="event: { click: OnClickBtnNuevaCuotaPagoClienteComprobanteVenta }">
												<i class="fas fa-plus"></i>
							</button></th>
									</tr>
								</thead>
								<tbody>
									<!-- ko foreach : CuotasPagoClienteComprobanteVenta -->
								<tr>			
										<td class="col-md-1 text-center" data-bind="text: IdentificadorCuota" style="vertical-align: middle;">
										</td>
										<td class="col-md-3">											
												<input name="FechaPagoCuota"
														class="form-control formulario inputs"																
														data-bind="
														value : FechaPagoCuota ,
														attr : { id : IdCuotaPagoClienteComprobanteVenta() + '_input_FechaPagoCuota' },
														event: { 
															focus:  OnFocus ,
															keydown : OnKeyEnter ,
															focusout:  function(data,event) { return ValidarFechaPagoCuota(data,event,$parent.ActualizarResumenCuotasPago) },
															change: OnChangeFechaPagoCuota
														}"
														data-validation="date"
														data-inputmask-clearmaskonlostfocus="false"														
														data-validation-format="dd/mm/yyyy"
														data-validation-error-msg="Fecha invalida">
												</input>											
										</td>
										<td class="col-md-3">
												<input name="MontoCuota"
														class="form-control formulario numeric text-mumeric inputs"														
														data-bind="value : MontoCuota,                                                
														attr : { 
															id : IdentificadorCuota() + '_input_MontoCuota', 
															'data-cantidad-decimal' : DecimalMontoCuota(),															
														},
														event: { 
															focus : OnFocus ,
															keydown : OnKeyEnter, 
															focusout : function(data,event) { return ValidarMontoCuota(data,event,$parent.ActualizarResumenCuotasPago) }, 														
															numberdecimal : MontoCuota"
														type="text" data-validation="number_desc"
														data-validation-allowing="float,positive,range[0;9999999]"
														data-validation-decimal-separator="."
														data-validation-error-msg="De 0 a más">
										</td>
										<td class="col-md-1">
											<button class="btn btn-danger btn-control" title="Eliminar Cuota"
												data-bind="event: { click: $parent.OnClickBtnRemoverCuotaPagoClienteComprobanteVenta }">
												<i class="fas fa-trash-alt"></i>
											</button>
										</td>										
									</tr>
									<!-- /ko -->
								</tbody>
								<tfoot>
									<tr>
										<th class="col-md-1 text-center"></th>
										<th class="col-md-3 text-right">TOTAL</th>
										<th class="col-md-3 text-right" data-bind="text : TotalMontoCuotasPago()"></th>
										<th class="col-md-1"></th>			
									</tr>
								</tfoot>
							</table>
						</div>
						<div class="col-md-2">
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-12 text-center">
							<button class="btn btn-default" type="button" data-dismiss="modal">Cerrar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>