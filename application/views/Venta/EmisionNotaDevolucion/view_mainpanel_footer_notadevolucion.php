<script>
    var data=<?php echo json_encode($data); ?>;
    window.DataMotivosNotaCredito = data.data.MotivosNotaCredito;
    window.CamposNotaCredito = data.data.CamposNotaCredito;
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/EmisionNotaCredito/Motivos.js"></script>
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
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/EmisionNotaCredito/MappingVenta.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/ComprobanteVenta/ModeloComprobanteVenta.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/NotaCredito/ModeloDetalleNotaCredito.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/NotaCredito/VistaModeloDetalleNotaCredito.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/NotaCredito/ModeloNotaCredito.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/NotaCredito/VistaModeloNotaCredito.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/EmisionNotaCredito/OtrosModeloNotaCredito.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/EmisionNotaCredito/VistaModeloEmisionNotaCredito.js"></script>
<script src="<?php echo base_url()?>assets/js/template/comprobantesventa.js"></script>

<script>
  var koNode = document.getElementById('maincontent');
  var ViewModels = new VistaModeloEmisionNotaCredito(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(ViewModels, koNode);
</script>

<script type="text/javascript">
$("#BusquedaComprobantesVentaModel").on("hidden.bs.modal", function(e){
  if(ViewModels.data.NotaCredito.MiniComprobantesVentaNC().length <= 0)
  {
    $("#btn_buscardocumentoreferencia").focus();
    $("#BusquedaComprobantesVentaModel").resetearValidaciones();
  }
});

$(document).ready(function () {

  });

  ViewModels.Inicializar();
</script>
