<script>
    //var NUMERO_DECIMALES_VENTA = 2;
    var data=<?php echo json_encode($data); ?>;

    window.DataMotivosNotaCreditoCompra = data.data.MotivosNotaCreditoCompra;
    window.CamposNotaCreditoCompra = data.data.CamposNotaCreditoCompra;
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/EmisionNotaCreditoCompra/Motivos.js"></script>

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
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/EmisionNotaCreditoCompra/MappingCompra.js"></script>

<!-- <script src="<?php echo base_url()?>assets/js/ViewModel/Compra/DocumentoReferencia/ModeloBusquedaComprobantesCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/DocumentoReferencia/ModeloFiltros.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/DocumentoReferencia/ModeloMiniComprobantesCompra.js"></script> -->

<!-- <script src="<?php echo base_url()?>assets/js/ViewModel/Compra/ComprobanteCompra/ModeloComprobanteCompra.js"></script> -->
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/NotaCreditoCompra/ModeloDetalleNotaCreditoCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/NotaCreditoCompra/VistaModeloDetalleNotaCreditoCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/NotaCreditoCompra/ModeloNotaCreditoCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/NotaCreditoCompra/VistaModeloNotaCreditoCompra.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/EmisionNotaCreditoCompra/OtrosModeloNotaCreditoCompra.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/EmisionNotaCreditoCompra/VistaModeloEmisionNotaCreditoCompra.js"></script>
<script src="<?php echo base_url()?>assets/js/template/comprobantesventa.js"></script>



<script>
    var ViewModels = new VistaModeloEmisionNotaCreditoCompra(data);
    ko.applyBindingsWithValidation(ViewModels);
</script>

<script type="text/javascript">
$("#BusquedaComprobantesCompraModel").on("hidden.bs.modal", function(e){
  if(ViewModels.data.NotaCreditoCompra.MiniComprobantesCompraNC().length <= 0)
  {
    $("#btn_buscardocumentoreferencia").focus();
    $("#BusquedaComprobantesCompraModel").resetearValidaciones();
  }
});

$(document).ready(function () {

  });

  ViewModels.Inicializar();
</script>
