<script>
    var data=<?php echo json_encode($data); ?>;
    var url_json = SERVER_URL + URL_JSON_MERCADERIAS;
    var data_mercaderia = ObtenerJSONCodificadoDesdeURL(url_json);
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/MappingConfiguracionCatalogo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/MappingConfiguracionGeneral.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/AutoCompletadoProductos.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/ModeloProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/VistaModeloProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/MappingCatalogo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/MappingTransferenciaAlmacen.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/TransferenciaAlmacen/ModeloTransferenciaAlmacen.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/TransferenciaAlmacen/ModeloDetalleTransferenciaAlmacen.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/TransferenciaAlmacen/VistaModeloTransferenciaAlmacen.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/TransferenciaAlmacen/VistaModeloDetalleTransferenciaAlmacen.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/TransferenciaAlmacen/VistaModeloTransferenciaAlmacen.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/TransferenciaAlmacen/VistaModeloCreacionTransferenciaAlmacen.js"></script>

<script>
    var ViewModels = new VistaModeloCreacionTransferenciaAlmacen(data);
    ko.applyBindingsWithValidation(ViewModels);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>
