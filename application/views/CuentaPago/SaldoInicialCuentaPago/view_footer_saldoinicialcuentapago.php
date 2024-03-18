<script>
    var data=<?php echo json_encode($data); ?>;
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Proveedor/AutoCompletadoProveedor.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/AutoCompletadoProductos.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaPago/MappingCuentaPago.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaPago/SaldoInicialCuentaPago/ModeloSaldoInicialCuentaPago.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaPago/SaldoInicialCuentaPago/VistaModeloSaldoInicialCuentaPago.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaPago/SaldoInicialCuentaPago/ModeloRegistroSaldoInicialCuentaPago.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaPago/SaldoInicialCuentaPago/VistaModeloRegistroSaldoInicialCuentaPago.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaPago/SaldoInicialCuentaPago/VistaModeloDetalleSaldoInicialCuentaPago.js"></script>

<script>
  var koNode = document.getElementById('maincontent');
  var ViewModels = new VistaModeloRegistroSaldoInicialCuentaPago(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(ViewModels, koNode);
</script>
