<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog" id="modalGuiaRemisionRemitente"
	data-backdrop="static"
	data-bind="bootstrapmodal : GuiaRemisionRemitente.showComprobanteVenta, show : GuiaRemisionRemitente.showComprobanteVenta, onhiden :  function(){GuiaRemisionRemitente.Hide(window)}, backdrop: 'static'">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="panel-title">Guia de Remision</h3>
				<button type="button" class="close" data-bind="click : GuiaRemisionRemitente.OnClickBtnCerrar"
					aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<?php echo $view_form_guiaremisionremitente; ?>
			</div>
		</div>
	</div>
</div>
