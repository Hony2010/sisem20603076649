<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog" id="modalCasillerosPorGenero" data-backdrop="static"
	data-bind="bootstrapmodal: OrdenPedido.ShowCasillerosPorGenero, show : OrdenPedido.ShowCasillerosPorGenero, onhiden: () => OrdenPedido.OnHideCasillerosPorGenero(window)">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h5 class="modal-title">PANEL DE CASILLEROS POR GENERO</h5>
			</div>
			<div class="modal-body">
				<form action="">
					<div class="row">
						<div class="col-md-12">
							<fieldset>
								<div class="col-md-6">
									<div class="form-group text-center">
										<b>MUJERES</b>
									</div>
									<div class="form-group text-center casilleros">
										<!-- ko foreach : CasillerosPorGenero.Femenino -->
										<button class="btn" data-bind="
										text: NombreCasillero, 
										css: IndicadorCasilleroDisponible() == 1 ? 'btn-success' : 'btn-danger',
										event: { click: $parent.OrdenPedido.OnClickBtnCasillero }"></button>
										<!-- /ko -->
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group text-center">
										<b>HOMBRES</b>
									</div>
									<div class="form-group text-center casilleros">
										<!-- ko foreach : CasillerosPorGenero.Masculino -->
										<button class="btn" data-bind="
										text: NombreCasillero, 
										css: IndicadorCasilleroDisponible() == 1 ? 'btn-success' : 'btn-danger',
										event: { click: $parent.OrdenPedido.OnClickBtnCasillero }"></button>
										<!-- /ko -->
									</div>
								</div>
							</fieldset>

						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
