<script>
    var data=<?php echo json_encode($data); ?>;
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/AutoCompletadoClientes.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaCobranza/MappingCuentaCobranza.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaCobranza/CobranzaCliente/ModeloCobranzaCliente.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaCobranza/CobranzaCliente/VistaModeloCobranzaCliente.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaCobranza/CobranzaCliente/VistaModeloDetalleCobranzaCliente.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaCobranza/CobranzaCliente/VistaModeloRegistroCobranzaCliente.js"></script>
<script>
    var ViewModels = new VistaModeloRegistroCobranzaCliente(data);
    ko.applyBindingsWithValidation(ViewModels);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>
