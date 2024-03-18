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
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/CompraMasiva/MappingMasivo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/CompraMasiva/ModeloCompraMasiva.js"></script>
<!-- <script src="<?php echo base_url()?>assets/js/ViewModel/Compra/CompraMasiva/ModeloDetalleCompraMasiva.js"></script> -->
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/CompraMasiva/VistaModeloCompraMasiva.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/CompraMasiva/VistaModeloDetalleCompraMasiva.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/CompraMasiva/VistaModeloCabeceraCompraMasiva.js"></script>
<!-- <script src="<?php echo base_url()?>assets/js/ViewModel/Compra/CompraMasiva/VistaModeloCompraMasiva.js"></script> -->
<script src="<?php echo base_url()?>assets/js/ViewModel/Compra/RegistroCompraMasiva/VistaModeloRegistroCompraMasiva.js"></script>
<script src="<?php echo base_url()?>assets/js/template/comprobantesventa.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Mercaderia.js"></script>

<script>
    var ViewModels = new VistaModeloRegistroCompraMasiva(data);
    ko.applyBindingsWithValidation(ViewModels);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>
