<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog" id="modalVehiculosCliente" data-bind="
bootstrapmodal: showVehiculos,
show: showVehiculos,
onhiden :  function() { return HideVehiculo(window)}">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">×</span></button>
				<h4 class="panel-title">Vehiculos Cliente</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<table class="table">
								<thead>
									<tr>
										<th>NUMERO PLACA</th>
										<th width="50">
											<button class="btn btn-success btn-control" title="Nuevo Numero Placa"
												data-bind="event: { click: OnClickBtnNuevoVehiculoCliente }">
												<i class="fas fa-plus"></i>
											</button>
										</th>
									</tr>
								</thead>
								<tbody>
									<!-- ko foreach : VehiculosCliente -->
									<tr>
										<td>
											<div class="form-group">
												<!-- Enlazar con plantilla data bind -->
												<input type="text" class="form-control formulario" data-bind="value:NumeroPlaca">
											</div>
										</td>
										<td>
											<button class="btn btn-danger btn-control" title="Eliminar Vehiculo"
												data-bind="event: { click: $parent.OnClickBtnRemoverVehiculoCliente }">
												<i class="fas fa-trash-alt"></i>
											</button>
										</td>
									</tr>
									<!-- /ko -->
								</tbody>
							</table>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-12 text-center">
							<button class="btn btn-success" type="button"
								data-bind="visible: IndicadorGuardarVehiculoCliente, event: { click: OnClickGuardarVehiculosCliente }">Guardar</button>
							<button class="btn btn-default" type="button" data-dismiss="modal">Cerrar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
