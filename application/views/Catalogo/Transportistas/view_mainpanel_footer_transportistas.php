<script>
    var data=<?php echo json_encode($data); ?>
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Transportista/ModeloTransportista.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Transportista/VistaModeloTransportista.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Transportistas/ModeloTransportistas.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Transportistas/VistaModeloTransportistas.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/VistaModeloProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/ModeloProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/MappingCatalogo.js"></script>

<script>
  var koNode = document.getElementById('maincontent');
  var ViewModels = new VistaModeloTransportistas(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(ViewModels, koNode);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>

<script type="text/javascript">
AccesoKey.Agregar("btnNuevo", TECLA_N);
AccesoKey.AgregarKeyOption("#formtransportista", "#BtnGrabar", TECLA_G);
RecorrerTabla.Agregar("#TablaConsultaTransportistas tbody");
RecorrerPaginador.Agregar("#Paginador");
</script>
