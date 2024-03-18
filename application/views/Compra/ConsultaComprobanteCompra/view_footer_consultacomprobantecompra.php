<script>
    var data=<?php echo json_encode($data); ?>;
    window.DataMotivosNotaCreditoCompra = data.data.MotivosNotaCreditoCompra;
    window.CamposNotaCreditoCompra = data.data.CamposNotaCreditoCompra;
    window.DataMotivosNotaDebitoCompra = data.data.MotivosNotaDebitoCompra;
    window.CamposNotaDebitoCompra = data.data.CamposNotaDebitoCompra;
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/VistaModeloTipoDocumentoIdentidad.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/MappingConfiguracionCatalogo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/TipoCambio/ModeloTipoCambio.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/TipoCambio/VistaModeloTipoCambio.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/MappingConfiguracionGeneral.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Mercaderia.js"></script>
<!-- <script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/AutoCompletadoClientes.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/ModeloCliente.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/VistaModeloCliente.js"></script> -->
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Proveedor/AutoCompletadoProveedor.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Proveedor/ModeloProveedor.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Proveedor/VistaModeloProveedor.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/AutoCompletadoProductos.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/ModeloProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/VistaModeloProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/AutoCompletadoGastos.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/AutoCompletadoCostosAgregado.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/MappingCatalogo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/MappingCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/ComprobanteCompra/ModeloComprobanteCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/ComprobanteCompra/ModeloDetalleComprobanteCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/ComprobanteCompra/VistaModeloComprobanteCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/ComprobanteCompra/VistaModeloDetalleComprobanteCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/ComprobanteCompra/VistaModeloFiltroDocumentoIngreso.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/ComprobanteCompra/ModeloDocumentoIngreso.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/CompraGasto/ModeloCompraGasto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/CompraGasto/ModeloDetalleCompraGasto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/CompraGasto/VistaModeloCompraGasto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/CompraGasto/VistaModeloDetalleCompraGasto.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/BusquedaDocumentoCompra/ModeloDocumentoCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/BusquedaDocumentoCompra/ModeloBusquedaDocumentoCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/BusquedaDocumentoCompra/VistaModeloBusquedaDocumentoCompra.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/CompraCostoAgregado/ModeloDocumentoReferencia.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/CompraCostoAgregado/ModeloCompraCostoAgregado.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/CompraCostoAgregado/ModeloDetalleCompraCostoAgregado.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/CompraCostoAgregado/VistaModeloCompraCostoAgregado.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/CompraCostoAgregado/VistaModeloDetalleCompraCostoAgregado.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/RegistroCompraCostoAgregado/VistaModeloRegistroCompraCostoAgregado.js"></script>

<!--Para Nota Credito-->
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/EmisionNotaCreditoCompra/Motivos.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/NotaCreditoCompra/ModeloDetalleNotaCreditoCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/NotaCreditoCompra/VistaModeloDetalleNotaCreditoCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/NotaCreditoCompra/ModeloNotaCreditoCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/NotaCreditoCompra/VistaModeloNotaCreditoCompra.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/EmisionNotaCreditoCompra/OtrosModeloNotaCreditoCompra.js"></script>

<!--Para Nota Debito-->
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/RegistroNotaDebitoCompra/Motivos.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/NotaDebitoCompra/ModeloDetalleNotaDebitoCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/NotaDebitoCompra/VistaModeloDetalleNotaDebitoCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/NotaDebitoCompra/ModeloNotaDebitoCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/NotaDebitoCompra/VistaModeloNotaDebitoCompra.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/RegistroNotaDebitoCompra/OtrosModeloNotaDebitoCompra.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/ConsultaComprobanteCompra/ModeloConsultaComprobanteCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/ConsultaComprobanteCompra/VistaModeloConsultaComprobanteCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/template/comprobantescompra.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/VistaModeloMercaderiaJSON.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/ModeloBusquedaAvanzadaProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/VistaModeloBusquedaAvanzadaProducto.js"></script>

<script>
    var ViewModels = new VistaModeloConsultaComprobanteCompra(data);
    ko.applyBindingsWithValidation(ViewModels);

</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>
