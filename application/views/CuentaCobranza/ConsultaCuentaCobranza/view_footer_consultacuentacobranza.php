<script>
    var data=<?php echo json_encode($data); ?>;
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/AutoCompletadoClientes.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaCobranza/CuentaCobranza/VistaModeloCuentaCobranza.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaCobranza/CobranzaCliente/VistaModeloCobranzaCliente.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaCobranza/CobranzaCliente/ModeloCobranzaCliente.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaCobranza/CobranzaCliente/VistaModeloDetalleCobranzaCliente.js"></script>


<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaCobranza/MappingCuentaCobranza.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaCobranza/ConsultaCuentaCobranza/ModeloConsultaCuentaCobranza.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaCobranza/ConsultaCuentaCobranza/VistaModeloConsultaCuentaCobranza.js"></script>


<script>
  var koNode = document.getElementById('maincontent');
  var ViewModels = new VistaModeloConsultaCuentaCobranza(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(ViewModels, koNode);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>
