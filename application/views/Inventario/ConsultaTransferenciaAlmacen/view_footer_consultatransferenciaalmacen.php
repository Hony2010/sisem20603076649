<script>
    var data=<?php echo json_encode($data); ?>;
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/AutoCompletadoClientes.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/MappingConfiguracionCatalogo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/MappingConfiguracionGeneral.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/AutoCompletadoProductos.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/VistaModeloProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/ModeloProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/MappingCatalogo.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/TransferenciaAlmacen/VistaModeloTransferenciaAlmacen.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/TransferenciaAlmacen/ModeloTransferenciaAlmacen.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/TransferenciaAlmacen/ModeloDetalleTransferenciaAlmacen.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/TransferenciaAlmacen/VistaModeloDetalleTransferenciaAlmacen.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/TransferenciaAlmacen/VistaModeloCreacionTransferenciaAlmacen.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/MappingTransferenciaAlmacen.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/ConsultaTransferenciaAlmacen/ModeloConsultaTransferenciaAlmacen.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Inventario/ConsultaTransferenciaAlmacen/VistaModeloConsultaTransferenciaAlmacen.js"></script>
<script src="<?php echo base_url()?>assets/js/template/comprobantesventa.js"></script>


<script>
  var koNode = document.getElementById('maincontent');
  var ViewModels = new VistaModeloConsultaTransferenciaAlmacen(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(ViewModels, koNode);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>
