<script>
    var data=<?php echo json_encode($data); ?>;
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/VistaModeloTipoDocumentoIdentidad.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/MappingConfiguracionCatalogo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/TipoCambio/ModeloTipoCambio.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/TipoCambio/VistaModeloTipoCambio.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/MappingConfiguracionGeneral.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Proveedor/AutoCompletadoProveedor.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Proveedor/ModeloProveedor.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Proveedor/VistaModeloProveedor.js"></script>
<!-- <script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/AutoCompletadoProductos.js"></script> -->
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/AutoCompletadoGastos.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/ModeloProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/VistaModeloProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/MappingCatalogo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/MappingCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/CompraGasto/ModeloCompraGasto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/CompraGasto/ModeloDetalleCompraGasto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/CompraGasto/VistaModeloCompraGasto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/CompraGasto/VistaModeloDetalleCompraGasto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/RegistroCompraGasto/VistaModeloRegistroCompraGasto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/ComprobanteCompra/VistaModeloFiltroDocumentoIngreso.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/ComprobanteCompra/ModeloDocumentoIngreso.js"></script>
<script src="<?php echo base_url()?>assets/js/template/comprobantescompra.js"></script>

<script>
    var ViewModels = new VistaModeloRegistroCompraGasto(data);
    ko.applyBindingsWithValidation(ViewModels);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>
