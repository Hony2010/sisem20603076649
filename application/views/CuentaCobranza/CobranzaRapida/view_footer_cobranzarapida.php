<script>
    var data=<?php echo json_encode($data); ?>;
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Vendedor/AutoCompletadoVendedores.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/AutoCompletadoClientes.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaCobranza/MappingCuentaCobranza.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaCobranza/CobranzaRapida/AutoCompletadoPendientesCobranzaCliente.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaCobranza/CobranzaRapida/VistaModeloCobranzaRapida.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaCobranza/CobranzaRapida/ModeloRegistroCobranzaRapida.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaCobranza/CobranzaRapida/VistaModeloRegistroCobranzaRapida.js"></script>
<script>
    var ViewModels = new VistaModeloRegistroCobranzaRapida(data);
    ko.applyBindingsWithValidation(ViewModels);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>
