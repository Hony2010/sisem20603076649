<script>
    var NUMERO_DECIMALES_VENTA = 2;
    var data=<?php echo json_encode($data); ?>;

    window.DataMotivosNotaDebito = data.data.MotivosNotaDebito;
    window.CamposNotaDebito = data.data.CamposNotaDebito;
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/EmisionNotaDebito/Motivos.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/VistaModeloTipoDocumentoIdentidad.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/MappingConfiguracionCatalogo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/TipoCambio/ModeloTipoCambio.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/TipoCambio/VistaModeloTipoCambio.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/MappingConfiguracionGeneral.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/AutoCompletadoClientes.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/ModeloCliente.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/VistaModeloCliente.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/AutoCompletadoProductos.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/ModeloProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/VistaModeloProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/MappingCatalogo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/EmisionNotaDebito/MappingVenta.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/ComprobanteVenta/ModeloComprobanteVenta.js"></script>

<!-- <script src="<?php echo base_url()?>assets/js/ViewModel/Venta/DocumentoReferencia/ModeloBusquedaComprobantesVenta.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/DocumentoReferencia/ModeloFiltros.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/DocumentoReferencia/ModeloMiniComprobantesVenta.js"></script> -->

<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/NotaDebito/ModeloDetalleNotaDebito.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/NotaDebito/VistaModeloDetalleNotaDebito.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/NotaDebito/ModeloNotaDebito.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/NotaDebito/VistaModeloNotaDebito.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/EmisionNotaDebito/OtrosModeloNotaDebito.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/EmisionNotaDebito/VistaModeloEmisionNotaDebito.js"></script>
<script src="<?php echo base_url()?>assets/js/template/comprobantesventa.js"></script>



<script>
  var koNode = document.getElementById('maincontent');
  var ViewModels = new VistaModeloEmisionNotaDebito(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(ViewModels, koNode);
</script>

<script type="text/javascript">
$("#BusquedaComprobantesVentaModel").on("hidden.bs.modal", function(e){
  if(ViewModels.data.NotaDebito.MiniComprobantesVenta().length <= 0)
  {
    $("#btn_buscardocumentoreferencia").focus();
    $("#BusquedaComprobantesVentaModel").resetearValidaciones();
  }
});

$(document).ready(function () {

  });

  ViewModels.Inicializar();
</script>
