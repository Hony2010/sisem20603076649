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
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/AutoCompletadoCostosAgregado.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/ModeloProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/VistaModeloProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/MappingCatalogo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/MappingCompra.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/BusquedaDocumentoCompra/ModeloDocumentoCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/BusquedaDocumentoCompra/ModeloBusquedaDocumentoCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/BusquedaDocumentoCompra/VistaModeloBusquedaDocumentoCompra.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/CompraCostoAgregado/ModeloDocumentoReferencia.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/CompraCostoAgregado/ModeloCompraCostoAgregado.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/CompraCostoAgregado/ModeloDetalleCompraCostoAgregado.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/CompraCostoAgregado/VistaModeloCompraCostoAgregado.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/CompraCostoAgregado/VistaModeloDetalleCompraCostoAgregado.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/RegistroCompraCostoAgregado/VistaModeloRegistroCompraCostoAgregado.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/ComprobanteCompra/VistaModeloFiltroDocumentoIngreso.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/ComprobanteCompra/ModeloDocumentoIngreso.js"></script>
<script src="<?php echo base_url()?>assets/js/template/comprobantescompra.js"></script>

<script>
    var ViewModels = new VistaModeloRegistroCompraCostoAgregado(data);
    ko.applyBindingsWithValidation(ViewModels);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>
