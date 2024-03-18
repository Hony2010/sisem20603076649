<script>
    var data=<?php echo json_encode($data); ?>;
</script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Cliente/AutoCompletadoClientes.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/ComprobanteCaja/VistaModeloComprobanteCaja.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/OtroDocumentoIngreso/VistaModeloOtroDocumentoIngreso.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/OtroDocumentoIngreso/ModeloOtroDocumentoIngreso.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/OtroDocumentoEgreso/VistaModeloOtroDocumentoEgreso.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/OtroDocumentoEgreso/ModeloOtroDocumentoEgreso.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/AperturaCaja/VistaModeloAperturaCaja.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/AperturaCaja/ModeloAperturaCaja.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/TransferenciaCaja/VistaModeloTransferenciaCaja.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/TransferenciaCaja/ModeloTransferenciaCaja.js"></script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/MappingCaja.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/ConsultaComprobanteCaja/ModeloConsultaComprobanteCaja.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Caja/ConsultaComprobanteCaja/VistaModeloConsultaComprobanteCaja.js"></script>
<script src="<?php echo base_url()?>assets/js/template/comprobantesventa.js"></script>


<script>
  var koNode = document.getElementById('maincontent');
  var ViewModels = new VistaModeloConsultaComprobanteCaja(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(ViewModels, koNode);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>
