<script>
    var data=<?php echo json_encode($data); ?>;
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/AutoCompletadoClientes.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Producto/AutoCompletadoProductos.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaCobranza/MappingCuentaCobranza.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaCobranza/SaldoInicialeCuentaCobranza/ModeloSaldoInicialCuentaCobranza.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaCobranza/SaldoInicialeCuentaCobranza/VistaModeloSaldoInicialCuentaCobranza.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaCobranza/SaldoInicialeCuentaCobranza/ModeloRegistroSaldoInicialCuentaCobranza.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaCobranza/SaldoInicialeCuentaCobranza/VistaModeloRegistroSaldoInicialCuentaCobranza.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/CuentaCobranza/SaldoInicialeCuentaCobranza/VistaModeloDetalleSaldoInicialCuentaCobranza.js"></script>

<script>
  var koNode = document.getElementById('maincontent');
  var ViewModels = new VistaModeloRegistroSaldoInicialCuentaCobranza(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(ViewModels, koNode);

  ViewModels.Inicializar()
</script>
