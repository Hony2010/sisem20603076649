<script>
    var data=<?php echo json_encode($data); ?>;
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/AutoCompletadoClientes.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaPago/CuentaPago/VistaModeloCuentaPago.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaPago/PagoProveedor/VistaModeloPagoProveedor.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaPago/PagoProveedor/ModeloPagoProveedor.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaPago/PagoProveedor/VistaModeloDetallePagoProveedor.js"></script>


<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaPago/MappingCuentaPago.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaPago/ConsultaCuentaPago/ModeloConsultaCuentaPago.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaPago/ConsultaCuentaPago/VistaModeloConsultaCuentaPago.js"></script>


<script>
  var koNode = document.getElementById('maincontent');
  var ViewModels = new VistaModeloConsultaCuentaPago(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(ViewModels, koNode);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>
