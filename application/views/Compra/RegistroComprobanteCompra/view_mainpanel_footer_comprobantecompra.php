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
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/AutoCompletadoProductos.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/ModeloProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/VistaModeloProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/MappingCatalogo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/MappingCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/ComprobanteCompra/ModeloComprobanteCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/ComprobanteCompra/ModeloDetalleComprobanteCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/ComprobanteCompra/VistaModeloComprobanteCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/ComprobanteCompra/VistaModeloDetalleComprobanteCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/ComprobanteCompra/VistaModeloFiltroDocumentoIngreso.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/ComprobanteCompra/ModeloDocumentoIngreso.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/RegistroComprobanteCompra/VistaModeloRegistroComprobanteCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/template/comprobantescompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Mercaderia.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/VistaModeloMercaderiaJSON.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/ModeloBusquedaAvanzadaProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/VistaModeloBusquedaAvanzadaProducto.js"></script>

<script>
    var ViewModels = new VistaModeloRegistroComprobanteCompra(data);
    ko.applyBindingsWithValidation(ViewModels);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();

  RecorrerTabla.Agregar("#TablaInventarioMercaderias tbody");
  RecorrerPaginador.Agregar("#PaginadorJSONParaListaSimple");

</script>
