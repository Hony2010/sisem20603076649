<script>
  var data=<?php echo json_encode($data); ?>;
</script>

<script>
  var existecambio = false;
  var editor = CodeMirror.fromTextArea(document.getElementById("editor"), {
    mode: "application/xml",
    lineWrapping: true,
    readOnly: true
  });

</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/FacturacionElectronica/ConsultarGuiaRemisionRemitenteElectronica/ModeloVistaConsultaGuiaRemisionRemitenteElectronica.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/FacturacionElectronica/ConsultarGuiaRemisionRemitenteElectronica/ConsultaGuiaRemisionRemitenteElectronica.js"></script>

<script>
  var koNode = document.getElementById('maincontent');
  var Models = new Index(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(Models, koNode);
</script>

<script type="text/javascript">
  Models.Inicializar();
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $('body').on('click', '.DescargaXML',function(){
      var url = $(this).attr('name');
      var name = $(this).val();
      var data = {};
      data.url = url;
      data.name = name;
      Models.ValidarXML(data, window);
    });

    $('body').on('click', '.DescargaCDR',function(){
      var url = $(this).attr('name');
      var name = $(this).val();
      var data = {};
      data.url = url;
      data.name = name;
      Models.ValidarCDR(data, window);
    });

  });
</script>

<script type="text/javascript">
AccesoKey.Agregar("BuscadorEnvio", 66);
</script>
