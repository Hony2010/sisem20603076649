<script>
    var data=<?php echo json_encode($data); ?>;
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/AutoCompletadoClientes.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaCobranza/CuentaCobranza/VistaModeloCuentaCobranza.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaCobranza/CanjeLetraCobrar/VistaModeloCanjeLetraCobrar.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaCobranza/CanjeLetraCobrar/ModeloCanjeLetraCobrar.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaCobranza/CanjeLetraCobrar/VistaModeloDetalleCanjeLetraCobrar.js"></script>


<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaCobranza/MappingCuentaCobranza.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaCobranza/ConsultaCanjeLetraCobrar/ModeloConsultaCanjeLetraCobrar.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaCobranza/ConsultaCanjeLetraCobrar/VistaModeloConsultaCanjeLetraCobrar.js"></script>


<script>
  var koNode = document.getElementById('maincontent');
  var ViewModels = new VistaModeloConsultaCanjeLetraCobrar(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(ViewModels, koNode);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>
