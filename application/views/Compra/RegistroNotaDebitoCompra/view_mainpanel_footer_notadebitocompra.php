<script>
    //var NUMERO_DECIMALES_VENTA = 2;
    var data=<?php echo json_encode($data); ?>;

    window.DataMotivosNotaDebitoCompra = data.data.MotivosNotaDebitoCompra;
    window.CamposNotaDebitoCompra = data.data.CamposNotaDebitoCompra;
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/RegistroNotaDebitoCompra/Motivos.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/VistaModeloTipoDocumentoIdentidad.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/MappingConfiguracionCatalogo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/TipoCambio/ModeloTipoCambio.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/TipoCambio/VistaModeloTipoCambio.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/MappingConfiguracionGeneral.js"></script>
<!-- <script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/AutoCompletadoClientes.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/ModeloCliente.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/VistaModeloCliente.js"></script> -->
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Proveedor/AutoCompletadoProveedor.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Proveedor/ModeloProveedor.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Proveedor/VistaModeloProveedor.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/AutoCompletadoProductos.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/ModeloProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/VistaModeloProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/MappingCatalogo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/RegistroNotaDebitoCompra/MappingCompra.js"></script>

<!-- <script src="<?php echo base_url()?>assets/js/ViewModel/Compra/DocumentoReferencia/ModeloBusquedaComprobantesCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/DocumentoReferencia/ModeloFiltros.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/DocumentoReferencia/ModeloMiniComprobantesCompra.js"></script> -->

<!-- <script src="<?php echo base_url()?>assets/js/ViewModel/Compra/ComprobanteCompra/ModeloComprobanteCompra.js"></script> -->
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/NotaDebitoCompra/ModeloDetalleNotaDebitoCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/NotaDebitoCompra/VistaModeloDetalleNotaDebitoCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/NotaDebitoCompra/ModeloNotaDebitoCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/NotaDebitoCompra/VistaModeloNotaDebitoCompra.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/RegistroNotaDebitoCompra/OtrosModeloNotaDebitoCompra.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/RegistroNotaDebitoCompra/VistaModeloRegistroNotaDebitoCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/template/comprobantesventa.js"></script>



<script>
    var ViewModels = new VistaModeloRegistroNotaDebitoCompra(data);
    ko.applyBindingsWithValidation(ViewModels);
</script>

<script type="text/javascript">
$("#BusquedaComprobantesCompraModel").on("hidden.bs.modal", function(e){
  if(ViewModels.data.NotaDebitoCompra.MiniComprobantesCompraND().length <= 0)
  {
    $("#btn_buscardocumentoreferencia").focus();
    $("#BusquedaComprobantesCompraModel").resetearValidaciones();
  }
});

$(document).ready(function () {

  });

  ViewModels.Inicializar();
</script>
