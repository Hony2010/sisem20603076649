<script>
  var data = <?php echo json_encode($data); ?>;
</script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Catalogo/Transportista/AutoCompletadoTransportista.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Catalogo/Cliente/AutoCompletadoClientes.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Venta/ComprobanteVenta/AutoCompletadoComprobantesVenta.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Catalogo/Producto/AutoCompletadoProductos.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Venta/GuiaRemisionRemitente/VistaModeloGuiaRemisionRemitente.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Venta/GuiaRemisionRemitente/ModeloGuiaRemisionRemitente.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Venta/GuiaRemisionRemitente/VistaModeloDetalleGuiaRemisionRemitente.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Venta/GuiaRemisionRemitente/ModeloDetalleGuiaRemisionRemitente.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Venta/EmisionGuiaRemisionRemitente/VistaModeloEmisionGuiaRemisionRemitente.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Venta/MappingGuiaRemisionRemitente.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Venta/GuiaRemisionRemitente/BusquedaFacturasGuiaModel.js"></script>
<script src="<?php echo base_url() ?>assets/js/ViewModel/Venta/GuiaRemisionRemitente/BusquedaFacturasGuiaViewModel.js"></script>
<script>
  var koNode = document.getElementById('maincontent');
  var ViewModels = new VistaModeloEmisionGuiaRemisionRemitente(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(ViewModels, koNode);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>

<script type="text/javascript">
  AccesoKey.Agregar("Nueva_Factura", TECLA_N);
</script>