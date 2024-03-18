<fieldset>
	<table class="datalist__table table display table-border" width="100%" data-products="brand">
		<thead>
			<tr>
				<th class="col-md-auto text-center">Documento</th>
				<th class="col-md-auto text-center">Fecha Emisión</th>
				<th class="col-md-1">RUC/DNI</th>
				<th class="col-md-3 text-center">Cliente</th>
				<th class="col-md-1 text-center">Total</th>
				<!-- ko if: ComprobanteVenta.ParametroSauna() == 1 -->
				<th class="col-md-1 text-center">Horario</th>
				<!-- /ko -->
				<!-- ko if: ComprobanteVenta.ParametroSauna() != 1 -->
				<th class="col-md-auto text-center">Forma Pago</th>
				<!-- /ko -->
				<th class="col-md-1 text-center">Estado</th>
				<th class="col-md-1 text-center">Usuario</th>
				<th class="col-md-1">Situacion CPE</th>
				<!-- ko if: ComprobanteVenta.ParametroSauna() == 1 -->
				<th class="col-md-1">Genero</th>
				<th class="col-md-1">Casillero</th>
				<!-- /ko -->
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<!-- ko if: ComprobanteVenta.ParametroSauna() == 1 -->
				<th>&nbsp;</th>
				<!-- /ko -->
			</tr>
		</thead>
		<tbody>
			<!-- ko foreach : ComprobantesVenta -->
			<tr class="clickable-row"
				data-bind="click : $root.Seleccionar, attr : { id: IdComprobanteVenta }, css: IndicadorEstado() == 'N' ? 'anulado' : ''">
				<td class="col-md-auto col-md-auto-height text-center" data-bind="text : Numero()"></td>
				<td class="col-md-auto col-md-auto-height text-center">
					<span data-bind="text : FechaEmision"></span>
				</td>
				<td class="col-md-1 col-md-auto-height" data-bind="text : NumeroDocumentoIdentidad"></td>
				<td class="col-md-3 col-md-auto-height" data-bind="text : RazonSocial"></td>
				<td class="col-md-1 text-right col-md-auto-height" data-bind="text : TotalComprobante()"></td>
				<!-- ko if: $parent.ComprobanteVenta.ParametroSauna() == 1 -->
				<td class="col-md-auto col-md-auto-height" data-bind="text : HoraOcupacionCasillero() + ' - ' + HoraLiberacionCasillero()"> </td>
				<!-- /ko -->
				<!-- ko if: $parent.ComprobanteVenta.ParametroSauna() != 1 -->
				<td class="col-md-auto col-md-auto-height text-center" data-bind="text : NombreFormaPago"> </td>
				<!-- /ko -->
				<td class="col-md-1 col-md-auto-height" data-bind="text : EstadoComprobante"></td>
				<td class="col-md-1 col-md-auto-height" data-bind="text : AliasUsuarioVenta"></td>
				<td class="col-md-1 col-md-auto-font col-md-auto-height" data-bind="text: AbreviaturaSituacionCPE"></td>
				<!-- ko if: $parent.ComprobanteVenta.ParametroSauna() == 1 -->
				<td class="col-md-1" data-bind="text: NombreGenero"></td>
				<td class="col-md-1" data-bind="text: NombreCasillero"></td>
				<!-- /ko -->
				<td class="col-md-auto col-md-auto-height text-center">
					<button data-bind="click : $root.OnClickBtnVer , attr : { id : IdComprobanteVenta() + '_btnVer' }  "
						class="btn btn-sm btn-info btn-consulta" data-toogle="tooltip" title="Ver Comprobante Venta">
						<span class="fa fa-fw fa-eye"></span>
					</button>
				</td>
				<td class="col-md-auto col-md-auto-height text-center">
					<button
						data-bind="click : $root.OnClickBtnEditar , attr : { id : IdComprobanteVenta() + '_btnEditar' },enable: OnEnableBtnEditar() "
						class="btn btn-sm btn-warning btn-consulta" data-toogle="tooltip"
						title="Editar Comprobante Venta">
						<span class="glyphicon glyphicon-pencil"></span>
					</button>
				</td>
				<td class="col-md-auto col-md-auto-height text-center">
					<button
						data-bind="click : $root.Anular , attr : { id : IdComprobanteVenta() + '_btnAnular' } ,enable:  OnEnableBtnAnular()"
						class="btn btn-sm btn-baja btn-consulta" data-toogle="tooltip" title="Anular Comprobante Venta">
						<span class="fa fa-fw fa-times"></span>
					</button>
				</td>
				<td class="col-md-auto col-md-auto-height text-center">
					<button data-bind="click : $root.Eliminar, enable: OnEnableBtnEliminar()"
						class="btn btn-sm btn-danger btn-consulta" data-toogle="tooltip"
						title="Eliminar Comprobante Venta">
						<span class="glyphicon glyphicon-trash"></span>
					</button>
				</td>
				<td class="col-md-auto col-md-auto-height text-center">
					<button data-bind="click : $root.OnClickBtnImprimir" class="btn btn-sm btn-print btn-consulta"
						data-toogle="tooltip" title="Imprimir Comprobante Venta">
						<span class="glyphicon glyphicon-print"></span>
					</button>
				</td>
				<!-- ko if: $parent.ComprobanteVenta.ParametroSauna() == 1 -->
				<td class="col-md-auto col-md-auto-height text-center">
					<button
						data-bind="click : $root.OnClickBtnLiberarCasilleroGenero, enable: OnEnableBtnLiberarCasillero()"
						class="btn btn-sm btn-danger btn-consulta" data-toogle="tooltip" title="Liberar Casillero">
						<span class="fas fa-unlock"></span>
					</button>
				</td>
				<!-- /ko -->
			</tr>
			<!-- /ko -->
		</tbody>
	</table>
</fieldset>
