<script>
    var data=<?php echo json_encode($data); ?>;
    window.DataMotivos = data.data.Motivos;
    window.CamposNotaEntrada = data.data.Campos;
    window.TiposDocumento= data.data.TiposDocumento;
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/EmisionNotaEntrada/Motivos.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/VistaModeloTipoDocumentoIdentidad.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/MappingConfiguracionCatalogo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/TipoCambio/ModeloTipoCambio.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/TipoCambio/VistaModeloTipoCambio.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/MappingConfiguracionGeneral.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/AutoCompletadoClientes.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/ModeloCliente.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/VistaModeloCliente.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Proveedor/AutoCompletadoProveedor.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Proveedor/ModeloProveedor.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/AutoCompletadoProductos.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/ModeloProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/VistaModeloProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/MappingCatalogo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/MappingNotaEntrada.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/NotaEntrada/ModeloNotaEntrada.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/NotaEntrada/ModeloDetalleNotaEntrada.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/NotaEntrada/VistaModeloNotaEntrada.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/NotaEntrada/VistaModeloDetalleNotaEntrada.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/EmisionNotaEntrada/OtrosModeloNotaEntrada.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/EmisionNotaEntrada/VistaModeloEmisionNotaEntrada.js"></script>
<script src="<?php echo base_url()?>assets/js/template/comprobantesventa.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/VistaModeloMercaderiaJSON.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/ModeloBusquedaAvanzadaProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/VistaModeloBusquedaAvanzadaProducto.js"></script>

<script>
    var ViewModels = new VistaModeloEmisionNotaEntrada(data);
    ko.applyBindingsWithValidation(ViewModels);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>

<script type="text/javascript">
  $("#BusquedaComprobantesVentaModel").on("hidden.bs.modal", function(e){
    if(ViewModels.data.NotaEntrada.MiniComprobantesVenta().length <= 0)
    {
      $("#btn_buscardocumentoreferencia").focus();
      $("#BusquedaComprobantesVentaModel").resetearValidaciones();
    }
  });
</script>
