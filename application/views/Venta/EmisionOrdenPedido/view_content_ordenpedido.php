<!-- ko with : data -->
<div class="main__scroll scrollbar-macosx">
	<div class="main__cont">
		<div class="form-group">
			<?php echo $view_tipoventa_ordenpedido ?>
		</div>
		<div class="container-fluid half-padding">
			<div class="datalist page page_products products float-right">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<?php echo $view_panel_ordenpedido; ?>
					</div>
				</div>
			</div>
			<div class="row">
				<?php echo $view_modal_cliente; ?>
				<?php echo $view_modal_preview_foto_cliente; ?>
				<?php echo $view_modal_form_mercaderia; ?>
				<?php echo $view_modal_buscador_mercaderia; ?>
				<?php echo $view_modal_buscador_mercaderia_lista; ?>
				<?php echo $view_modal_buscador_mercaderia_lista_simple; ?>
				<?php echo $view_modal_casilleroporgenero; ?>
				<?php echo $view_modal_buscadorproforma; ?>
			</div>
		</div>
	</div>
</div>
<!-- /ko -->
