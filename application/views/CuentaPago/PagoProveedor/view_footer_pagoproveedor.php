<script>
    var data=<?php echo json_encode($data); ?>;
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Proveedor/AutoCompletadoProveedor.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaPago/MappingCuentaPago.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaPago/PagoProveedor/ModeloPagoProveedor.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaPago/PagoProveedor/VistaModeloPagoProveedor.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaPago/PagoProveedor/VistaModeloDetallePagoProveedor.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaPago/PagoProveedor/VistaModeloRegistroPagoProveedor.js"></script>
<script>
    var ViewModels = new VistaModeloRegistroPagoProveedor(data);
    ko.applyBindingsWithValidation(ViewModels);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>
