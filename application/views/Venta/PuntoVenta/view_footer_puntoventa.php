<script>
    var data=<?php echo json_encode($data); ?>;
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/VistaModeloTipoDocumentoIdentidad.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/MappingConfiguracionCatalogo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/TipoCambio/ModeloTipoCambio.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/TipoCambio/VistaModeloTipoCambio.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/MappingConfiguracionGeneral.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/AutoCompletadoClientes.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/ModeloCliente.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/VistaModeloCliente.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/VistaModeloDireccionCliente.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/AutoCompletadoProductos.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/ModeloProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/VistaModeloProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/MappingCatalogo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/MappingVenta.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/ComprobanteVenta/ModeloComprobanteVenta.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/ComprobanteVenta/ModeloDetalleComprobanteVenta.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/ComprobanteVenta/VistaModeloComprobanteVenta.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/ComprobanteVenta/VistaModeloDetalleComprobanteVenta.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/PuntoVenta/VistaModeloPuntoVenta.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/PuntoVenta/VistaModeloEmisionPuntoVenta.js"></script>
<script src="<?php echo base_url()?>assets/js/template/comprobantesventa.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Mercaderia.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/BusquedaCategorizada/VistaModeloMercaderiaJSON.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/BusquedaCategorizada/ModeloBusquedaAvanzadaProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/BusquedaCategorizada/VistaModeloBusquedaAvanzadaProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/PuntoVenta/TecladoVirtual/VistaModeloTecladoVirtual.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/ComprobanteVenta/AutoCompletadoRadioTaxis.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/ComprobanteVenta/AutoCompletadoComprobanteVentaProforma.js"></script>
<script>
  var koNode = document.getElementById('maincontent');
  var ViewModels = new VistaModeloEmisionPuntoVenta(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(ViewModels, koNode);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>
