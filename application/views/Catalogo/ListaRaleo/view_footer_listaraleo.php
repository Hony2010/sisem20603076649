<script>
    var data=<?php echo json_encode($data); ?>;
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/MappingCatalogo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/ListaRaleo/ModeloListaRaleo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/ListaRaleo/ModeloDetalleListaRaleo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/ListaRaleo/VistaModeloListaRaleo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/ListaRaleo/VistaModeloDetalleListaRaleo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/ListaRaleo/VistaModeloListaRaleo.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/ListaRaleo/VistaModeloRegistroListaRaleo.js"></script>

<script>
    var koNode = document.getElementById('maincontent');
    var ViewModels = new VistaModeloRegistroListaRaleo(data);
    ko.cleanNode(koNode);
    ko.applyBindingsWithValidation(ViewModels, koNode);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>
