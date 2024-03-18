<script>
    var data=<?php echo json_encode($data); ?>;

    var url_json = SERVER_URL + URL_JSON_MERCADERIAS;
    var data_mercaderia = ObtenerJSONCodificadoDesdeURL(url_json);
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/VistaModeloTipoDocumentoIdentidad.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/MappingConfiguracionCatalogo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/TipoCambio/ModeloTipoCambio.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/TipoCambio/VistaModeloTipoCambio.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/MappingConfiguracionGeneral.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/AutoCompletadoProductos.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/ModeloProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/VistaModeloProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/MappingCatalogo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/MappingInventarioInicial.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/InventarioInicial/ModeloInventarioInicial.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/InventarioInicial/ModeloDetalleInventarioInicial.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/InventarioInicial/VistaModeloInventarioInicial.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/InventarioInicial/VistaModeloDetalleInventarioInicial.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/InventarioInicial/VistaModeloInventarioInicial.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/InventarioInicial/VistaModeloCreacionInventarioInicial.js"></script>
<!-- <script src="<?php echo base_url()?>assets/js/template/comprobantesventa.js"></script> -->

<script>
    var ViewModels = new VistaModeloCreacionInventarioInicial(data);
    ko.applyBindingsWithValidation(ViewModels);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();

  $("body").on("click","#Descargar_Plantilla",function functionName() {
    window.location = data.data.InventarioInicial.RutaPlantillaExcel;
  });
</script>
