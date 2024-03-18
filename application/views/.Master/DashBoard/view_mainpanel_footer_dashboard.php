<script>
    var dataInicio = <?php echo json_encode($dataInicio); ?>
</script>
<script type="text/javascript">
  var CantidadFacturas = dataInicio.data.FacturacionElectronica.CantidadFacturas;
  var CantidadDias = parseInt(dataInicio.data.FacturacionElectronica.Dias) - 1;
  var msgFacturas = CantidadFacturas > 1 ? "Facturas no enviadas a SUNAT y se vencerán" : "Factura no enviada a SUNAT y se vencerá";
  var msgDias = CantidadDias == 0 ? "hoy." : CantidadDias == 1 ? "mañana." : "en "+CantidadDias+" días." ;

  if (CantidadFacturas > 0) {
    $("#FacturasPendienteEnvio").removeClass('hide');
    $("#MsgFacturas").text(msgFacturas);
    $("#MsgDias").text(msgDias);
    $("#cantidadFacturaPendienteEnvio").text(CantidadFacturas);
    $("#cantidadDiasPendienteEnvio").text(CantidadDias);
  }

  var msgVencimiento = dataInicio.data.CertificadoDigital.Vencimiento.msg;
  if (msgVencimiento != "") {
    alertify.alert("CERTIFICADO DIGITAL",msgVencimiento, function () { });
  }
</script>
<script>
setTimeout(function(){
  document.getElementById("audio-bienvenida").play();
}, 250);
</script>
